<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             <?php hooks()->do_action('before_items_page_content'); ?>
            
              <div class="_buttons">
				<h4>Bill Of Material</h4>
				<hr>
			  </div>
              <div class="clearfix"></div>
			   <?php echo form_open('admin/production/add_bom',array('id'=>'receipe_add_form')); ?>
			   
			  <div class="row">
			  
			    <div class="col-md-2">
				<?php
                    $selected_company = $this->session->userdata('root_company');
                    if($selected_company == 1){
                        $new_BOMNumbar = get_option('next_bom_number_for_cspl');
                    }elseif($selected_company == 2){
                        $new_BOMNumbar = get_option('next_bom_number_for_cff');
                    }elseif($selected_company == 3){
                        $new_BOMNumbar = get_option('next_bom_number_for_cbu');
                    }
                    
                    $format = get_option('invoice_number_format');
               
					$prefix = "BOM";
					$prefix = $prefix.'<span id="prefix_year">'.$this->session->userdata('finacial_year').'</span>';
					
                    $new_BOMNumbar = str_pad($new_BOMNumbar, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                 ?> 
					<div class="form-group">
                        <label for="number"> BOM Number</label>
                        <div class="input-group">
                            <span class="input-group-addon"><?php echo $prefix; ?></span>
                            <input type="text" name="pro_orderid" id="pro_orderid" class="form-control receiptsid" value="<?php echo $new_BOMNumbar; ?>"  data-original-number="<?php echo $new_BOMNumbar; ?>" >
                        </div>
                    </div>
						
                </div>
                
			    <div class="col-md-2">
                    <?php echo render_input('item_code','BOM For'); ?>
                    
                  <div class="" id="serchh" style="display:none;">Serching</div>
                </div>
                <div class="col-md-2">
                    <?php
                    $attr = array('disabled'=>true);
                    ?>
                    <?php echo render_input('ItemName','BOM Name','','',$attr); ?>
                </div>
               		
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="qtytoproduce">
                        <label for="qtytoproduce" class="control-label">FG Quantity </label>
                        <input type="text" name="qtytoproduce" id="qtytoproduce" class="form-control" value="" >
                                        
                    </div>
                </div>  
				
				<div class="col-md-2">
				    <?php 
				        $attr = array(
				            'disabled' =>true
				        );
				    ?>
				    <?php echo render_input('unit1','Unit','','',$attr); ?>
                    <input type="hidden" name="unit_f_g" id="unit_f_g" value="">    
                    <input type="hidden" name="item_desc" id="item_desc" value="">    
                    </div>
				<div class="col-md-1" style="margin-top: 20px;">
                <?php if(has_permission_new('recipe','','view')){ ?>
                     <a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>
                     <?php
                    }
                     ?>
                 </div>	
					<input type="hidden" value="0" name="countof_record" id="countof_record">
					<input type="hidden" value="2" name="sub_group" id="sub_group">
            </div>
            
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="fg_store">
                        <small class="req text-danger">* </small>
                        <label for="fg_store" class="form-label">FG Store</label> 
                        <select name="fg_store" id="fg_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>"><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="rm_store">
                        <small class="req text-danger">* </small>
                        <label for="rm_store" class="form-label">RM Store</label> 
                        <select name="rm_store" id="rm_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>"><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="scrap_store">
                        <small class="req text-danger">* </small>
                        <label for="scrap_store" class="form-label">Scrap Store</label> 
                        <select name="scrap_store" id="scrap_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>"><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="cost_allocation">
                        <label for="cost_allocation" class="control-label">Cost Allocation </label>
                        <input type="text" name="cost_allocation" id="cost_allocation" class="form-control" value="" >
                    </div>
                </div> 
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="bom_comments">
                        <label for="bom_comments" class="control-label">Comment </label>
                        <input type="text" name="bom_comments" id="bom_comments" class="form-control" value="" >
                    </div>
                </div> 
                
                
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="labour_cost">
                        <label for="labour_cost" class="control-label">Labour Cost</label>
                        <input type="text" name="labour_cost" id="labour_cost" class="form-control" value="" >
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="electricity_cost">
                        <label for="electricity_cost" class="control-label">Electricity Cost</label>
                        <input type="text" name="electricity_cost" id="electricity_cost" class="form-control" value="" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="machinery_cost">
                        <label for="machinery_cost" class="control-label">Machinery Cost</label>
                        <input type="text" name="machinery_cost" id="machinery_cost" class="form-control" value="" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="other_cost">
                        <label for="other_cost" class="control-label">Other Cost</label>
                        <input type="text" name="other_cost" id="other_cost" class="form-control" value="" >
                    </div>
                </div>
                
            </div>
		
			   <div class="row">
			       
                <div class="col-md-12">
                    <h5>Raw Material</h5>
                    <table class="table table-striped table-bordered" id="data_table" width="100%">
                        <thead>
                            <tr>
                                
                                <th style="width:12%">Item ID</th>
                                <th style="width:30%">Item Description</th>
                                <th style="width:12%">Item Category</th>
                                <th style="width:5%">Quantity</th>
                                <th style="width:5%">Unit</th>
                                <th style="width:20%">Comment</th>
                                <th style="width:10%">Child BOM Number</th>
								<th style="width:6%">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr id="R1">
                               
                                <td><input type="text" name="item_id" id="item_id" style="border-radius: 2px;height: 30px;">
								<div class="" id="search_item" style="display:none;">Serching</div></td>
								
                                <td><input type="text" readonly name="item_name" id="item_name" class="form-control" style="border-radius: 2px;height: 30px;"></td>
								<td><input type="text" readonly name="item_cat" id="item_cat" class="form-control" style="border-radius: 2px;height: 30px;">
								    <input type="hidden" name="item_main_group" id="item_main_group" value="">
								</td>
                                <td><input type="text" name="req_qty" id="req_qty" class="form-control" style="border-radius: 2px;height: 30px;"></td>
								
                                <td><input type="text" readonly name="unit" id="unit" class="form-control" style="border-radius: 2px;height: 30px;"></td>
                                <td><input type="text" name="item_comm" id="item_comm" class="form-control" style="border-radius: 2px;height: 30px;"></td>
                                <td><input type="text" name="item_child_bom" id="item_child_bom" class="form-control" style="border-radius: 2px;height: 30px;"></td>
                                <td><button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button></td>
                                <!--<td></td>-->
                                </tr>
                        </tbody>
                    </table>
                </div>
               
            </div>
		
			<div class="row">
				<div class="col-md-3" style="margin-top:10px;">
                    <br>
                <?php if(has_permission_new('recipe','','create')){ ?>
                    <button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                     <?php } ?>
                </div>
			</div>
			 <?php echo form_close(); ?>
			
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="transfer-modal" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Recipe List</h4>
         </div>
         <div class="modal-body">
             
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <lebel for="Status" class="form-label">Status</lebel>
                        <select name="Status" id="Status" class="form-control">
                            <option value="Y">Active</option>
                            <option value="N">DeActive</option>
                            <option value="YN">All</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3"><br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
                </div>
                <!--<div class="col-md-6"><br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>-->
                
                <div class="col-md-12">
                 
            <div class="table_recipe_report">
             
              <table class="tree table table-striped table-bordered table_recipe_report" id="table_recipe_report" width="100%">
                  
                <thead>
                    <tr style="display:none;">
                      <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                    <th style="text-align:left;">BOMID</th>
                    <th style="text-align:left;">BOM Date</th>
                    <th style="text-align:left;">Item ID</th>
                    <th style="text-align:left;">Item Name</th>
                    <th style="text-align:left;">Quantity</th>
                    <th style="text-align:left;">Unit</th>
                    <th style="text-align:left;">ActiveDate</th>
                    <th style="text-align:left;">DeActiveDate</th>
                  </tr>
                </thead>
                <tbody>
                    
                </tbody>
              </table>   
            </div>
            <span id="searchh2" style="display:none;">
                                Loading.....
                            </span>
                    
                </div>
              </div>
              
            </div>
            <div class="modal-footer" style="padding:0px;">
                <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
            </div>
        
      </div>
   </div>
</div>
<?php init_tail(); ?>

<style>
    .table_recipe_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_recipe_report thead th { position: sticky; top: 0; z-index: 1; }
.table_recipe_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table_recipe_report table  { border-collapse: collapse; width: 100%; }
.table_recipe_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.table_recipe_report th     { background: #50607b;color: #fff !important; }

table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }


.modal-body {
    padding: 2px 5px;
}
.modal-header{
    padding: 5px 10px;
}
#table_recipe_report tr:hover {
    background-color: #ccc;
}

#table_recipe_report td:hover {
    cursor: pointer;
}
</style>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
<script>
    $('.add-new-transfer').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
       $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
      //init_journal_entry_table();
    });
</script>
<script type='text/javascript'>

$(document).ready(function () {
   var rowIdx = 1; 
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
  
    // Removing the current row.
    $(this).closest('tr').remove();
  
    // Decreasing the total number of rows by 1.
    rowIdx--;
});


// new code 

    /*$('#req_qty').on('blur', function () {
        var req_qty = $("#req_qty").val();
    	var item_id = $("#item_id").val();
    	var item_name = $("#item_name").val();
    	//alert(item_id);
    	if(item_id == "" || item_id == null ){
            alert("Select Item ID.");
        }else if(item_name == "" || item_name == null ){
            alert("Select Item Name.");
        }else if(req_qty == "" || req_qty == null ){
            alert("Add Require Quantity.");
        }else{
            add_row();
        }
    });*/
    
    $('#add').on('click', function () {
        var req_qty = $("#req_qty").val();
    	var item_id = $("#item_id").val();
    	var item_name = $("#item_name").val();
    	var item_child_bom = $("#item_child_bom").val();
    	var item_main_group = $("#item_main_group").val();
    	if(item_id == "" || item_id == null ){
            alert("Select Item ID.");
        }else if(item_name == "" || item_name == null ){
            alert("Select Item Name.");
        }else if(req_qty == "" || req_qty == null ){
            alert("Add Require Quantity.");
        }else if(item_main_group == "1" && item_child_bom == ""){
            alert('selected item is FG please add BOM');
            $('#item_child_bom').focus();
        }else{
            add_row();
        }
    });

// For recipe blur
$('#item_code').on('blur', function () {
    
    var curr_val = $(this).val();
    if(curr_val == ""){
        
    }else{
        // Fetch data
          $.ajax({
            url: "<?=base_url()?>admin/production/itemDetails_by_itemcode",
            type: 'post',
            dataType: "json",
            data: {
              search: curr_val
            },
            success: function( data ) {
                if(data == null){
                    alert('Item not found...');
                    $('#item_code').val('');
                    $('#item_desc').val('');
                    $('#ItemName').val('');
                    $('#qtytoproduce').val('');
		            $('#unit1').val('');
		            $('#unit_f_g').val('');
		            $('#item_code').focus();
                }else{
                    $('#item_code').val(data.item_code);
                    $('#ItemName').val(data.description);
                    $('#item_desc').val(data.description);
				    $('#unit1').val(data.unit); // save selected id to input
				    $('#unit_f_g').val(data.unit); // save selected id to input
                }
              //response( data );
              
            }
            
          });
    }
    
});



$('#item_id').on('focus',function(){
        
        $('#item_id').val('');
        $('#item_name').val('');
        $('#req_qty').val('');
		$('#unit').val('');
     });


function delete_row(no)
{
 document.getElementById("row"+no+"").outerHTML="";
}

    function add_row()
    {
        var item_id = document.getElementById("item_id").value;
        var item_name = document.getElementById("item_name").value;
        var item_cat = document.getElementById("item_cat").value;
        var req_qty=document.getElementById("req_qty").value;
        var unit=document.getElementById("unit").value;
        var item_comm = document.getElementById("item_comm").value;
        var item_child_bom = document.getElementById("item_child_bom").value;
        var countof_record = document.getElementById("countof_record").value;
        
        var table=document.getElementById("data_table");
        var table_len=(table.rows.length)-1;
        var html = '';
        html += "<tr id='row"+table_len+"'>";
        html += "<td id='item_id"+table_len+"'>"+item_id+" <input type='hidden' name='item_id"+table_len+"' value='"+item_id+"'></td>";
        html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name"+table_len+"' value='"+item_name+"'></td>";
        html += "<td id='item_cat"+table_len+"'>"+item_cat+" <input type='hidden' name='item_cat"+table_len+"' value='"+item_cat+"'></td>";
        html += "<td id='req_qty"+table_len+"'>"+req_qty+" <input type='hidden' name='req_qty"+table_len+"' value='"+req_qty+"'></td>";
        html += "<td id='unit"+table_len+"'>"+unit+" <input type='hidden' name='unit"+table_len+"' value='"+unit+"'></td>";
        html += "<td id='item_comm"+table_len+"'>"+item_comm+" <input type='hidden' name='item_comm"+table_len+"' value='"+item_comm+"'></td>";
        html += "<td id='item_child_bom"+table_len+"'>"+item_child_bom+" <input type='hidden' name='item_child_bom"+table_len+"' value='"+item_child_bom+"'></td>";
        html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
        
        html += '</tr>';
        var row = table.insertRow(table_len).outerHTML=html;
        
        var countof_record = document.getElementById("countof_record").value;
        var temp1 = parseFloat(countof_record) + parseFloat(1);
        
        document.getElementById("countof_record").value=temp1;
        document.getElementById("item_id").value="";
        document.getElementById("item_name").value="";
        document.getElementById("item_cat").value="";
        document.getElementById("item_main_group").value="";
        document.getElementById("req_qty").value="";
        document.getElementById("unit").value="";
        document.getElementById("item_comm").value="";
        document.getElementById("item_child_bom").value="";
        document.getElementById("item_id").focus();
    }
}); 

</script>
<script type="text/javascript">
   $('#conv_cost,#st_cost,#frt_cost,#mrkt_cost,#dmg_cost').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});
</script>
<script>
    function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode = 46 && charCode > 31 
            && (charCode < 48 || charCode > 57)){
        return false;
    }
    return true;
    }
</script>

<script>
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_recipe_report");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    td3 = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td1) {
      txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td2) {
      txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td3) {
      txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
}
}
}
</script>
  
<script type='text/javascript'>
    $(document).ready(function(){
		
	// Set validation for Accout Group Name form
    appValidateForm($('#receipe_add_form'), {
            item_code: 'required',
            ItemName: 'required',
            qtytoproduce: 'required',
        });
    $('#item_code').on('focus',function(){
        
        $('#item_code').val('');
        $('#item_desc').val('');
        $('#ItemName').val('');
		$('#unit1').val('');
		$('#unit_f_g').val('');
        
     });
      // Initialize 
        $( "#item_code" ).autocomplete({
            source: function( request, response ) {
              // Fetch data
              $.ajax({
                url: "<?=base_url()?>admin/production/itemlist_using_itemcode",
                type: 'post',
                dataType: "json",
                data: {
                  search: request.term
                },
                beforeSend: function () {
                   
                   $('#serchh').css('display','block');
                },
                complete: function () {
                    $('#serchh').css('display','none');
                },
                success: function( data ) {
                  response( data );
                }
                
              });
            },
            select: function (event, ui) {
                $('#item_code').val(ui.item.value);
                $('#item_desc').val(ui.item.label);
                $('#ItemName').val(ui.item.label);
    			$('#unit1').val(ui.item.units); // save selected id to input
    			$('#unit_f_g').val(ui.item.units); // save selected id to input
    			//$('#item_code').focus();
    			return false
            }
        });
    });
   
    </script>
  
<script type='text/javascript'>
       
$(document).ready(function(){

    // get dropdown for row material 
    $( "#item_id" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
			url: "<?=base_url()?>admin/production/ItemListReceipe",
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
          // Set selection
          $('#item_id').val(ui.item.value); // display the selected text
          $('#item_name').val(ui.item.label); // save selected id to input
		  $('#unit').val(ui.item.unit); // save selected id to input
		  $('#item_cat').val(ui.item.sub_group_name);
		  $('#item_main_group').val(ui.item.main_group_name);
		  $('#req_qty').focus();
          return false;
        }
    });
	  
	//By Item Name
	$( "#item_name" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
			url: "<?=base_url()?>admin/production/ItemListReceipe",
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
          // Set selection
          $('#item_id').val(ui.item.value); // display the selected text
          $('#item_name').val(ui.item.label); // save selected id to input
		  $('#unit').val(ui.item.unit); // save selected id to input
		  $('#item_cat').val(ui.item.sub_group_name);
		  $('#item_main_group').val(ui.item.main_group_name);
		  $('#req_qty').focus();
          return false;
        }
    });
    
    
    // For Row Item blur
    $('#item_id').on('blur', function () {
        var curr_val = $(this).val();
        if(curr_val != ""){
            // Fetch data
            $.ajax({
                url: "<?=base_url()?>admin/production/itemDetails_by_itemcode",
                type: 'post',
                dataType: "json",
                data: {
                  search: curr_val
                },
                success: function( data ) {
                    if(data == null){
                        alert('Item not found...');
                        $('#item_id').val('');
                        $('#item_name').val('');
                        $('#req_qty').val('');
                        $('#unit').val('');
                        $('#item_cat').val('');
                        $('#item_main_group').val('');
    		            $('#item_id').focus();
                    }else{
                        $('#item_id').val(data.item_code);
                        $('#item_name').val(data.description);
    				    $('#unit').val(data.unit); // save selected id to input
    				    $('#item_cat').val(data.name);
    				    $('#item_main_group').val(data.main_group_id);
    				    $('#req_qty').focus();
                    }
                }
            });
        } 
    });
    
    function load_data(status)
    {
        $.ajax({
            url:"<?php echo admin_url(); ?>production/load_data_for_BOM",
            dataType:"JSON",
            method:"POST",
            data:{status:status},
            beforeSend: function () {
                $('#searchh2').css('display','block');
                $('.table_recipe_report tbody').css('display','none');
            },
            complete: function () {
                $('.table_recipe_report tbody').css('display','');
                $('#searchh2').css('display','none');
            },
            success:function(data){
            var html = '';
            for(var count = 0; count < data.length; count++)
            {
                var url = "'<?php echo admin_url() ?>production/EditBOM/"+data[count].id+"'";
                html += '<tr onclick="location.href='+url+'">';
                html += '<td style="text-align:center;">'+data[count].BOMID+'</td>';
                var date = data[count].TransDate.substring(0, 10)
                var date_new = date.split("-").reverse().join("/");
                
                html += '<td  style="text-align:center;">'+date_new+'</td>';
                html += '<td style="text-align:center;">'+data[count].item_code+'</td>';
                html += '<td >'+data[count].item_description+'</td>';
                html += '<td  style="text-align:center;">'+data[count].qty+'</td>';
                html += '<td style="text-align:right;">'+data[count].unit+'</td>';
                if(data[count].ActiveDate == null){
                var date_new = '';
                }else{
                var date = data[count].ActiveDate.substring(0, 10);
                var date_new = date.split("-").reverse().join("/");
                }
                html += '<td >'+date_new+'</td>';
                if(data[count].DeActiveDate == null){
                var date_new2 = '';
                }else{
                var date2 = data[count].DeActiveDate.substring(0, 10);
                var date_new2 = date2.split("-").reverse().join("/");
                }
                html += '<td >'+date_new2+'</td>';
                
                html += '</tr>';
                }
                $('.table_recipe_report tbody').html(html);
            }
        });
    }  
    
    $('#search_data').on('click',function(){
        var status = $('#Status').val();
        load_data(status);
    });

});
	   
</script>
<script type="text/javascript">
  

$('#qtytoproduce').on('keypress',function (event) {
    var unit = $('#unit_f_g').val();
    if(unit == "Kgs"){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
            event.preventDefault();
        }
    }else if(unit == "Pcs"){
        event = (event) ? event : window.event;
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
});

$('#req_qty').on('keypress',function (event) {
    var unit = $('#unit').val();
    if(unit == "Kgs"){
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
            event.preventDefault();
        }
    }else if(unit == "Pcs"){
        event = (event) ? event : window.event;
        var charCode = (event.which) ? event.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
});
</script>
	
    