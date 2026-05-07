<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
/*echo "<pre>";
        print_r($BOMDetails);
        echo $BOMDetails->BOMID;
        die;*/
 ?>
<div id="wrapper">
  <div class="content">
    <div class="row" style="display:none;">
		<div class="col-md-12">
		    <table id="print_table">
			     <thead>
			         <tr>
			             <th align="center" colspan="8"><?php echo $company_detail->company_name; ?></th>
			         </tr>
			         <tr>
			             <th align="center" colspan="8"><?php echo $company_detail->address; ?></th>
			         </tr>
			     </thead>
			 <tbody>
			         <tr>
			             <td colspan="8"><b>BOM Details</b></td>
			         </tr>
			         <tr>
			             <td ><b>BOM Number</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->BOMID; ?></b></td>
			             <td><b>FG Store</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->FG_Godown; ?></b></td>
			         </tr>
			         <tr>
			             <td><b>BOM Name</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->item_code; ?></b></td>
			             <td><b>RM Store</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->RM_Godown; ?></b></td>
			         </tr>
			         <tr>
			             <td><b>Description</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->item_description; ?></b></td>
			             <td><b>Scrap Store</b></td>
			             <td colspan="3"><b><?php echo $BOMDetails->Scrap_Godown; ?></b></td>
			         </tr>
			         <tr>
			             <td colspan="8"><b>FG Details</b></td>
			         </tr>
			         <tr>
			             <td><b>#</b></td>
                         <td><b>ItemID</b></td>
                         <td><b>Item Description</b></td>
                         <td><b>Item Category</b></td>
                         <td><b>Quantity</b></td>
                         <td><b>Unit</b></td>
                         <td><b>Cost Allocation</b></td>
                         <td><b>Comment</b></td>
			         </tr>
			         <tr>
			             <td>1</td>
			             <td><?php echo $BOMDetails->item_code; ?></td>
			             <td><?php echo $BOMDetails->item_description; ?></td>
			             <td><?php echo $BOMDetails->SubGroupName; ?></td>
			             <td><?php echo $BOMDetails->qty; ?></td>
			             <td><?php echo $BOMDetails->unit; ?></td>
			             <td><?php echo $BOMDetails->cost_allocation; ?></td>
			             <td><?php echo $BOMDetails->bom_comments; ?></td>
			         </tr>
			         
			         <tr>
			             <td colspan="8"><b>RM Details</b></td>
			         </tr>
			         <tr>
			             <td><b>#</b></td>
                         <td><b>ItemID</b></td>
                         <td><b>Item Description</b></td>
                         <td><b>Item Category</b></td>
                         <td><b>Quantity</b></td>
                         <td><b>Unit</b></td>
                         <td><b>Comment</b></td>
                         <td><b>Child BOM Number</b></td>
			         </tr>
			         <?php 
			            $i = 1;
			            foreach($BOMFGDetails as $Key=>$val){
			         ?>
			            <tr>
			                <td><?php echo $i ; ?></td>
			                <td><?php echo $val['item_id'] ; ?></td>
			                <td><?php echo $val['item_name'] ; ?></td>
			                <td><?php echo $val['ItemSubGroup'] ; ?></td>
			                <td><?php echo $val['req_qty'] ; ?></td>
			                <td><?php echo $val['unit'] ; ?></td>
			                <td><?php echo $val['Item_comments'] ; ?></td>
			                <td><?php echo $val['child_bom'] ; ?></td>

			            </tr>
			         <?php
			                $i++;
			            }
			         ?>
			     
			</tbody>
		</table>
		</div>
	</div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              
              <div class="_buttons">
				<h4>Edit Recipe</h4>
			  </div>
              <div class="clearfix"></div>
			   <?php echo form_open('admin/production/EditBOM/'.$BOMDetails->id,array('id'=>'receipe_edit_form'));
			  
			   ?>
			   
                    <input type="hidden" name="updated_record" value=" " id="updated_record">
                    <input type="hidden" name="deleted_record" value=" " id="deleted_record">
                    <input type="hidden" name="new_record" value=" " id="new_record">
                
			   <input type="hidden" name="item_desc" id="item_desc" value="<?php echo $BOMDetails->item_description?>">
			   <input type="hidden" name="id" id="id" value="<?php echo $BOMDetails->id; ?>">
			   
			  <div class="row">
			      
			    <div class="col-md-2">
				    <?php
				        $BOMID = $BOMDetails->BOMID; 
				    ?>
					<div class="form-group" app-field-wrapper="BOMID_raad">
                        <label class="control-label" for="BOMID_raad"> BOM Number</label>
                        <input type="text" name="BOMID_raad" readonly  id="BOMID_raad" class="form-control " value="<?php echo $BOMID; ?>"  >
                        <input type="hidden" name="BOMID"  id="BOMID" value="<?php echo $BOMID; ?>"  >
                    </div>
						
                </div>
                <?php
                    $attr = array('disabled'=>true);
                    ?>
			    <div class="col-md-2">
                    <?php 
					$value = $BOMDetails->item_code; 
					echo render_input('item_code','BOM For',$value,'',$attr); ?>
                  <div class="" id="serchh" style="display:none;">Serching</div>
                </div>
               	<div class="col-md-2">
                    <?php 
                    $value=$BOMDetails->item_description; 
                    echo render_input('ItemName','BOM Name',$value,'',$attr); ?>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="qtytoproduce">
					<label class="control-label" for="qtytoproduce"><?php echo "FG Quantity"; ?></label>
				   <input type="text" value="<?php echo $BOMDetails->qty;?>" name="qtytoproduce" id="qtytoproduce" class="form-control">
				   </div>
                </div>  
				
				<div class="col-md-2">
				<label class="control-label" for=""><?php echo 'Unit'; ?></label>
				<input type="text" value="<?php echo $BOMDetails->unit;?>" name="unit_f_g" id="unit_f_g" class="form-control" readonly>
				<input type="hidden" value="<?php echo $BOMDetails->unit;?>" name="unit_f_g1" id="unit_f_g1" class="form-control">
                </div>
				
				<?php
					$value = count($BOMFGDetails);
				?>
				<div class="col-md-1" style="margin-top: 20px;">
                <?php if(has_permission_new('recipe','','view')){ ?>
                     <a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>
                     <?php
                    }
                     ?>
                 </div>	
                <input type="hidden" value="<?php echo $value + 1;?>" name="countof_record" id="countof_record">
				<input type="hidden" value="2" name="sub_group" id="sub_group">
				
            </div>
            
            <div class="row">
                 <?php $FG_Godown = $BOMDetails->FG_Godown; ?>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="fg_store">
                        <small class="req text-danger">* </small>
                        <label for="fg_store" class="form-label">FG Store</label> 
                        <select name="fg_store" id="fg_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($FG_Godown == $value['AccountID']){ echo 'selected';}?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <?php $RM_Godown = $BOMDetails->RM_Godown; ?>
                    <div class="form-group" app-field-wrapper="rm_store">
                        <small class="req text-danger">* </small>
                        <label for="rm_store" class="form-label">RM Store</label> 
                        <select name="rm_store" id="rm_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($RM_Godown == $value['AccountID']){ echo 'selected';}?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <?php $Scrap_Godown = $BOMDetails->Scrap_Godown; ?>
                    <div class="form-group" app-field-wrapper="scrap_store">
                        <small class="req text-danger">* </small>
                        <label for="scrap_store" class="form-label">Scrap Store</label> 
                        <select name="scrap_store" id="scrap_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($Scrap_Godown == $value['AccountID']){ echo 'selected';}?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <?php $cost_allocation = $BOMDetails->cost_allocation; ?>
                    <div class="form-group" app-field-wrapper="cost_allocation">
                        <label for="cost_allocation" class="control-label">Cost Allocation </label>
                        <input type="text" name="cost_allocation" id="cost_allocation" class="form-control" value="<?php echo $cost_allocation;?>" >
                    </div>
                </div> 
                
                <div class="col-md-2">
                    <?php $bom_comments = $BOMDetails->bom_comments; ?>
                    <div class="form-group" app-field-wrapper="bom_comments">
                        <label for="bom_comments" class="control-label">Comment </label>
                        <input type="text" name="bom_comments" id="bom_comments" class="form-control" value="<?php echo $bom_comments;?>" >
                    </div>
                </div> 
                
                
            </div>
            
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="labour_cost">
                        <label for="labour_cost" class="control-label">Labour Cost</label>
                        <input type="text" name="labour_cost" id="labour_cost" class="form-control" value="<?php echo $BOMDetails->conv_cost;?>" >
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="electricity_cost">
                        <label for="electricity_cost" class="control-label">Electricity Cost</label>
                        <input type="text" name="electricity_cost" id="electricity_cost" class="form-control" value="<?php echo $BOMDetails->st_cost;?>" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="machinery_cost">
                        <label for="machinery_cost" class="control-label">Machinery Cost</label>
                        <input type="text" name="machinery_cost" id="machinery_cost" class="form-control" value="<?php echo $BOMDetails->frt_cost;?>" >
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="other_cost">
                        <label for="other_cost" class="control-label">Other Cost</label>
                        <input type="text" name="other_cost" id="other_cost" class="form-control" value="<?php echo $BOMDetails->mrkt_cost;?>" >
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="status">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Y" <?php if($BOMDetails->status == "Y") { echo 'selected'; }?>>Active</option>
                            <option value="N" <?php if($BOMDetails->status == "N") { echo 'selected'; }?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
		 
			   <div class="row">
                <div class="col-md-12">
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
                        <?php
                        $i=1;
                        $ReceipeItemID = array();
                        array_push($ReceipeItemID,' ');
                        foreach ($BOMRMDetails as $value) {
                            if($value["unit"] == "Kgs"){
                                $cond = 'onkeypress = "return decimalNumber(event)"';
                            }else{
                                $cond = 'onkeypress="return isNumber(event)"';
                            }
                            array_push($ReceipeItemID,$value["item_id"]);
                        ?>
                        <tr id='row<?php echo $i;?>'>
                        <td align="center"  id='item_id<?php echo $i;?>'><?php echo $value["item_id"]; ?><input type='hidden' name='item_id<?php echo $i;?>' value='<?php echo $value["item_id"]; ?>' style="border-radius: 2px;height: 30px;"></td>
						
                        <td  id='item_name<?php echo $i;?>'><?php echo $value["item_name"]; ?><input type='hidden' name='item_name<?php echo $i;?>' value='<?php echo $value["item_name"]; ?>' style="border-radius: 2px;height: 30px;"></td>
						<td id='item_cat<?php echo $i;?>'><?php echo $value["ItemSubGroup"]; ?><input type="hidden" name="item_cat<?php echo $i;?>" id="item_cat<?php echo $i;?>" value="<?php echo $value["ItemSubGroup"]; ?>" style="border-radius: 2px;height: 30px;">
							
						</td>
                        <td align="right" id='req_qty<?php echo $i;?>'><input type='text' name='req_qty<?php echo $i;?>' id="<?php echo $value["item_id"]; ?>" <?php echo $cond;?> value='<?php echo $value["req_qty"]; ?>' style="border-radius: 2px;height: 30px;" onblur="update_record(this.id)"></td>
                   
                        <td align="center" id='unit<?php echo $i;?>'><?php echo $value["unit"]; ?><input type='hidden' name='unit<?php echo $i;?>' value='<?php echo $value["unit"]; ?>' style="border-radius: 2px;height: 30px;" readonly></td>
						<td><input type="text" name="item_comm<?php echo $i;?>" id="item_comm<?php echo $i;?>" class="form-control" value='<?php echo $value["Item_comments"]; ?>' style="border-radius: 2px;height: 30px;"></td>
                        <td><input type="text" name="item_child_bom<?php echo $i;?>" id="item_child_bom<?php echo $i;?>" class="form-control" value='<?php echo $value["child_bom"]; ?>' style="border-radius: 2px;height: 30px;"></td>
                        <td align="center"><button type="button" name="edit" id="remove_exiting_item" class="btn btn-xs btn-danger remove_exiting_item" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum<?php echo $i;?>" id="rownum" value="<?php echo $i;?>"></td>
                        </tr>
                         <?php
                         $i++;
                            }     
                           ?>
                            <tr id="R1">
                                
                                <td><input type="text" name="item_id" id="item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
								
								<td><input type="text" name="item_name" id="item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name" id="item_name" value=""></td>
			                    <td><input type="text" readonly name="item_cat" id="item_cat" class="form-control" style="border-radius: 2px;height: 30px;">
								    <input type="hidden" name="item_main_group" id="item_main_group" value="">
								</td>
                                <td><input type="text" name="req_qty" id="req_qty" value="" style="height: 30px;" /><input type="hidden" name="req_qty" id="req_qty" value=""></td>
								
                                <td ><input type="text" name="unit" id="unit" value="" style="height: 30px;width: 100%;" readonly/><input type="hidden" name="unit" id="unit" value=""></td>
                                <td><input type="text" name="item_comm" id="item_comm" class="form-control" style="border-radius: 2px;height: 30px;"></td>
                                <td><input type="text" name="item_child_bom" id="item_child_bom" class="form-control" style="border-radius: 2px;height: 30px;"></td>
                                
                                <td><button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button></td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
               
            </div>
		
			<div class="row">
				<div class="col-md-2" style="margin-top:10px;">
				    <?php $AllItemIDStr = implode(',', $ReceipeItemID); ?>
				    <input type="hidden" name="AllItemID" id="AllItemID" value="<?php echo $AllItemIDStr; ?>">
                <?php
                    if (has_permission_new('recipe', '', 'edit')) {
                    ?>
                    <button type="submit" class="btn btn-info"><?php echo 'Update'; ?></button>
                <?php } ?>
                </div>
                <div class="col-md-2" style="margin-top:10px;">
                    <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
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
                <!--<div class="col-md-6">
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

table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }

</style>
<script type="text/javascript">
 function printPage(){
      
	var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .print_item_h{ background: #505f7b;colr:#fff;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
    var print_data = stylesheet+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
 </script>
<script>
    $('.add-new-transfer').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
      //init_journal_entry_table();
      $('#transfer-modal').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
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
    td4 = tr[i].getElementsByTagName("td")[4];
    td5 = tr[i].getElementsByTagName("td")[5];
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
      }else if(td4) {
      txtValue = td4.textContent || td4.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td5) {
      txtValue = td5.textContent || td5.innerText;
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
}
}
</script>
<script type='text/javascript'>

$(document).ready(function () {
    var rowIdx = 1;
    

// remove new added row
$('#tbody').on('click', '.remove', function () {
  
    // Getting all the rows next to the 
    // row containing the clicked button
    var child = $(this).closest('tr').nextAll();
  
    // Iterating across all the rows 
    // obtained to change the index
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
  var  no = $(this).parents("tr").find('input[id="rownum"]').val();
  
    // Removing the current row.
    $(this).closest('tr').remove();
  
    // Decreasing the total number of rows by 1.
    rowIdx--;
    
    //counter update
    var countof_record = $("#countof_record").val();
    var new_cont = countof_record -1;
    $("#countof_record").val(new_cont);
    
    // new added records update
    var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
    
    var new_rec = $('#new_record').val();
    $new_item_name = ','+item_id;
    new_rec = new_rec.replace($new_item_name, " ");
    $('#new_record').val(new_rec);
    
    var newItemID = $('#AllItemID').val();
    $new_itemID = ','+item_id;
    newItemID = newItemID.replace($new_itemID, " ");
    $('#AllItemID').val(newItemID);
    
});

// remove existing row
$('#tbody').on('click', '.remove_exiting_item', function () {
    
    var child = $(this).closest('tr').nextAll();
  
    child.each(function () {
        
        var id = $(this).attr('id');
        var idx = $(this).children('.row-index').children('p');
        var dig = parseInt(id.substring(1));
        
        idx.html(`Row ${dig - 1}`);
        
        $(this).attr('id', `R${dig - 1}`);
    });
  var  no = $(this).parents("tr").find('input[id="rownum"]').val();
  
    // Removing the current row.
    $(this).closest('tr').remove();
  
    // Decreasing the total number of rows by 1.
    rowIdx--;
    
    //counter update
    var countof_record = $("#countof_record").val();
    var new_cont = countof_record -1;
    $("#countof_record").val(new_cont);
    
    // update deleted records
    var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
    
    var new_rec = $('#deleted_record').val();
    $new_item_name = new_rec+','+item_id;
    $('#deleted_record').val($new_item_name);
    
    var new_rec = $('#new_record').val();
    $new_item_name = ','+item_id;
    new_rec = new_rec.replace($new_item_name, " ");
    $('#new_record').val(new_rec);
    
    var newItemID = $('#AllItemID').val();
    $new_itemID = ','+item_id;
    newItemID = newItemID.replace($new_itemID, " ");
    $('#AllItemID').val(newItemID);
    
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
 
  var temp1 = parseFloat(countof_record) + parseFloat(1);
 
 document.getElementById("countof_record").value=temp1;

    var new_rec = $('#new_record').val();
    new_rec = new_rec +","+ item_id
    $('#new_record').val(new_rec);
    
    var NewItemID = $('#AllItemID').val();
    NewItemID = NewItemID +","+ item_id
    $('#AllItemID').val(NewItemID);
    
    
        $(this).parents("tr").find(".edit_row").show();  
        $(this).parents("tr").find(".btn-cancel").remove();  
        $(this).parents("tr").find(".btn-update").remove();
 
 
document.getElementById("item_id").value="";
document.getElementById("item_name").value="";
document.getElementById("item_cat").value="";
document.getElementById("item_main_group").value="";
document.getElementById("item_comm").value="";
document.getElementById("item_child_bom").value="";
document.getElementById("req_qty").value="";
document.getElementById("unit").value="";

document.getElementById("item_name").innerHTML="";
document.getElementById("item_cat").value="";
document.getElementById("req_qty").innerHTML="";
document.getElementById("unit").innerHTML="";
 
document.getElementById("item_id").focus(); 
}

 }); 

</script>
<script type="text/javascript">
   $('#qtytoproduce').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});
</script>
<script type='text/javascript'>
       
$(document).ready(function(){
    
    // Set validation for Accout Group Name form
    appValidateForm($('#receipe_edit_form'), {
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
		            $('#unit1').val('');
		            $('#unit_f_g').val('');
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
     // Initialize 
	 //By Item Code
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
               //$("#ui-id-2").prepend("<li value='' class='ui-menu-item'>Serching</li>");
            },
            complete: function () {
                //$("#item_code").val("");
                $('#serchh').css('display','none');
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
          // Set selection
          $('#item_code').val(ui.item.value); // display the selected text
          $('#ItemName').val(ui.item.label); // save selected id to input
          $('#item_desc').val(ui.item.label); // save selected id to input
		  $('#unit_f_g').val(ui.item.units); // save selected id to input
		  $('#unit_f_g1').val(ui.item.units); // save selected id to input
		  
          return false;
        }
      });
	  
	   	 //By Item Code
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
          var AllItemIDs = $("#AllItemID").val();
          let AllItemIDs_array = AllItemIDs.split(",");
            if(AllItemIDs_array.includes(ui.item.value)){
              alert("Item already added");
              $('#item_id').val('');
              $('#item_name').val('');
              $('#item_cat').val('');
		      $('#item_main_group').val('');
              return false;
            }else{
                  $('#item_id').val(ui.item.value); // display the selected text
                  $('#item_name').val(ui.item.label); // save selected id to input
        		  $('#unit').val(ui.item.unit); // save selected id to input
        		  $('#item_cat').val(ui.item.sub_group_name);
		          $('#item_main_group').val(ui.item.main_group_name);
        		  $('#req_qty').focus();
                  return false;
            }
        }
    });
    
    // For Row Item blur
$('#item_id').on('blur', function () {
    
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
                    $('#item_id').val('');
                    $('#item_name').val('');
                    $('#req_qty').val('');
                    $('#unit').val('');
                    $('#item_cat').val('');
                    $('#item_main_group').val('');
		            $('#item_id').focus();
                }else{
                    var AllItemIDs = $("#AllItemID").val();
                    let AllItemIDs_array = AllItemIDs.split(",");
                    if(AllItemIDs_array.includes(data.item_code)){
                        alert("Item already added"); 
                        $('#item_id').val('');
                        $('#item_name').val('');
                        $('#item_cat').val('');
                        $('#item_main_group').val('');
                        //$('#item_id').focus();
                        return false;
                    }else{
                        $('#item_id').val(data.item_code);
                        $('#item_name').val(data.description);
    				    $('#unit').val(data.unit); // save selected id to input
    				    $('#item_cat').val(data.name);
    				    $('#item_main_group').val(data.main_group_id);
    				    $('#req_qty').focus();
    				    return false;
                    }
                }
            }
            
          });
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
            var AllItemIDs = $("#AllItemID").val();
            let AllItemIDs_array = AllItemIDs.split(",");
            if(AllItemIDs_array.includes(ui.item.value)){
                alert("Item already added"); 
                $('#item_id').val('');
                $('#item_name').val('');    
                $('#item_cat').val('');
		        $('#item_main_group').val('');
            }else{
                $('#item_id').val(ui.item.value); // display the selected text
                $('#item_name').val(ui.item.label); // save selected id to input
    		    $('#unit').val(ui.item.unit); // save selected id to input
    		    $('#item_cat').val(ui.item.sub_group_name);
		        $('#item_main_group').val(ui.item.main_group_name);
    		    $('#req_qty').focus();
                return false;
            }
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
        function decimalNumber(evt) {
        if ((evt.which != 46 || $(this).val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) {
        evt.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3)) {
            evt.preventDefault();
        }
    }
    
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
    </script>
    <script>
        function update_record(value){
            //alert(value);
            var update = $('#updated_record').val();
              update = update +","+ value
              $('#updated_record').val(update);
        }
    </script>