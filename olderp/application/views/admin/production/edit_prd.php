<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 
$receipe_item = array();
foreach ($production->items as $key=>$value) {
   array_push($receipe_item, $value["item_id"]);
}
 $item_string = implode(',', $receipe_item);
 $selected_company = $this->session->userdata('root_company');
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
			             <td colspan="8"><b>Production Details</b></td>
			         </tr>
			         <tr>
			             <td colspan="2"><b>BOM No.</b></td>
			             <td colspan="2"><b><?php echo $production->BOMID; ?></b></td>
			             <td colspan="2"><b>FG Store</b></td>
			             <td colspan="2"><b><?php echo $production->FG_Godown; ?></b></td>
			         </tr>
			         <tr>
			             <td colspan="2"><b>Production No</b></td>
			             <td colspan="2"><b><?php echo $production->pro_order_id; ?></b></td>
			             <td colspan="2"><b>RM Store</b></td>
			             <td colspan="2"><b><?php echo $production->RM_Godown; ?></b></td>
			         </tr>
			         <tr>
			             <td colspan="2"><b>Description</b></td>
			             <td colspan="2"><b><?php echo $production->description; ?></b></td>
			             <td colspan="2"><b>Scrap Store</b></td>
			             <td colspan="2"><b><?php echo $production->Scrap_Godown; ?></b></td>
			         </tr>
			         
			         <tr>
			             <td colspan="2"><b>Status</b></td>
			             <td colspan="2"><b><?php echo $production->production_status; ?></b></td>
			             <td colspan="2"><b>Date Time</b></td>
			             <td colspan="2"><b><?php echo _d($production->TransDate); ?></b></td>
			         </tr>
			         
			         <tr>
			             <td colspan="8"><b>FG Details</b></td>
			         </tr>
			         <tr>
			             <td style="width:5%"><b>#</b></td>
                         <td style="width:8%"><b>ItemID</b></td>
                         <td style="width:30%"><b>Item Description</b></td>
                         <td style="width:15%"><b>Item Category</b></td>
                         <td style="width:8%"><b>Quantity</b></td>
                         <td style="width:8%"><b>Unit</b></td>
                         <td style="width:8%"><b>Cost Allocation</b></td>
                         <td style="width:13%"><b>Comment</b></td>
			         </tr>
			         <tr>
			             <td>1</td>
			             <td><?php echo $production->recipeID; ?></td>
			             <td><?php echo $production->description; ?></td>
			             <td><?php echo $production->SubGroupName; ?></td>
			             <td style="text-align:right;"><?php echo $production->Finish_good_qty; ?></td>
			             <td><?php echo $production->finish_good_unit; ?></td>
			             <td style="text-align:right;"><?php echo $production->cost_allocation; ?></td>
			             <td><?php echo $production->comment; ?></td>
			         </tr>
			         <tr>
			         <td colspan="8" style="height:30px;"></td>
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
			            foreach($production->BOMDetails as $Key=>$val){
			                if($val['item_id'] == "SCRAP"){
			                    
			                }else{
			         ?>
			            <tr>
			                <td><?php echo $i ; ?></td>
			                <td><?php echo $val['item_id']; ?></td>
			                <td><?php echo $val['item_name']; ?></td>
			                <td><?php echo $val['ItemSubGroup']; ?></td>
			                <td style="text-align:right;"><?php echo number_format($val['req_qty'], 2) ?></td>
			                <td><?php echo $val['unit']; ?></td>
			                <td><?php echo $val['Item_comments']; ?></td>
			                <td><?php echo $val['child_bom']; ?></td>

			            </tr>
			         <?php
			                $i++;
			                }
			            }
			         ?>
			         
			         <tr>
			         <td colspan="8" style="height:30px;"></td>
			     </tr>
			         <tr>
			             <td colspan="8"><b>Scrap Details</b></td>
			         </tr>
			         <tr>
			             <td><b>#</b></td>
                         <td><b>ItemID</b></td>
                         <td colspan="2"><b>Item Description</b></td>
                         <td><b>Quantity</b></td>
                         <td colspan="3"><b>Unit</b></td>
			         </tr>
			         <?php 
			            $i = 1;
			            foreach($production->items as $Key=>$val){
			                if($val['item_id'] == "SCRAP"){
			         ?>
			            <tr>
			                <td><?php echo $i ; ?></td>
			                <td><?php echo $val['item_id'] ; ?></td>
			                <td colspan="2"><?php echo $val['item_name'] ; ?></td>
			                <td style="text-align:right;"><?php echo number_format($val['production_req_qty'], 2); ?></td>
			                <td colspan="3"><?php echo $val['unit'] ; ?></td>
			            </tr>
			         <?php
			                $i++;
			                }
			            }
			         ?>
			     <tr>
			         <td colspan="8" style="height:30px;"></td>
			     </tr>
			     <tr>
			         <td>#</td>
			         <td colspan="2">Routing Number</td>
			         <td colspan="2">Routing Name</td>
			         <td colspan="3">Comment</td>
			     </tr>
			     
			     <tr>
			         <td>1</td>
			         <td colspan="2">WELDING</td>
			         <td colspan="2">WELDING</td>
			         <td colspan="3"><?php echo $production->welding_remark; ?></td>
			     </tr>
			     
			     <tr>
			         <td>2</td>
			         <td colspan="2">ASSEMBLY</td>
			         <td colspan="2">ASSEMBLY</td>
			         <td colspan="3"><?php echo $production->assembly_remark; ?></td>
			     </tr>
			     <tr>
			         <td>3</td>
			         <td colspan="2">PAINTING</td>
			         <td colspan="2">PAINTING</td>
			         <td colspan="3"><?php echo $production->painting_remark; ?></td>
			     </tr>
			     <tr>
			         <td>4</td>
			         <td colspan="2">MOVEMENT FOR GODOWN</td>
			         <td colspan="2">MOVEMENT FOR GODOWN</td>
			         <td colspan="3"><?php echo $production->move_for_godown_remark; ?></td>
			     </tr>
			     <tr>
			         <td colspan="8" style="height:30px;"></td>
			     </tr>
			     <tr>
			         <td>#</td>
			         <td colspan="3">Other Cost Type</td>
			         <td colspan="5">Cost</td>
			     </tr>
			     
			     <tr>
			         <td>1</td>
			         <td colspan="3">Labour</td>
			         <td colspan="5" style="text-align:right;"><?php echo $production->conv_cost; ?></td>
			     </tr>
			     
			     <tr>
			         <td>2</td>
			         <td colspan="3">Electricity</td>
			         <td colspan="5" style="text-align:right;"><?php echo $production->st_cost; ?></td>
			     </tr>
			     <tr>
			         <td>3</td>
			         <td colspan="3">Machinery</td>
			         <td colspan="5" style="text-align:right;"><?php echo $production->frt_cost; ?></td>
			     </tr>
			     <tr>
			         <td>4</td>
			         <td colspan="3">Others</td>
			         <td colspan="5" style="text-align:right;"><?php echo $production->mrkt_cost; ?></td>
			     </tr>
			     
			</tbody>
		</table>
			         <!--<table id="print_table">
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
			                     <td colspan="8"><b>Production Summery</b></td>
			                 </tr>
			                 
			                 <tr>
			                     <td><?php echo $production->pro_order_id ; ?></td>
			                     <td colspan="2"><b>Date : </b><?php echo $production->TransDate ; ?></td>
			                     <td colspan="2"><b>ItemId : </b><?php echo $production->recipeID; ?></td>
			                     <td colspan="3"><?php echo $production->description;?></td>
			                     
			                 </tr>
			                 <tr>
			                     <td><b>BatchQty : </b><?php echo $production->batch_qty ; ?></td>
			                     <td colspan="2"><b>Std FG : </b> <?php echo $production->Finish_good_qty; ?></td>
			                     <td colspan="2"><b>FG Unit : </b><?php echo $production->finish_good_unit; ?></td>
			                     <td colspan="3"><b>STD. Time : </b><?php echo $production->required_time; ?></td>
			                 </tr>
			                 <tr>
			                     <td><b>PRD Time : </b><?php echo $production->production_time ; ?></td>
			                     <td colspan="2"><b>PRD STRT Time : </b><?php echo $production->p_start_time; ?></td>
			                     <td colspan="2"><b>PRD END Time : </b><?php echo $production->p_end_time; ?></td>
			                     <td colspan="3"><b>Status : </b><?php echo $production->production_status; ?></td>
			                 </tr>
			                 <tr>
			                     <?php
			                     if(is_null($production->manager_name)){
			                         $name = $production->contractor_name;
			                     }else{
			                         $name = $production->manager_name;
			                     }
			                     ?>
			                     <td><b>Final FG : </b><?php echo $production->Finish_good_qty_new ; ?></td>
			                     <td colspan="2"><b>Res. Per : </b><?php echo $name; ?></td>
			                     <td colspan="2"><b>Comments : </b><?php echo $production->comment; ?></td>
			                     <td colspan="3"><b>Remark : </b><?php echo $production->remark; ?></td>
			                 </tr>
			                 <tr class="print_item_h">
			                     <td align="center">ItemID</td>
			                     <td align="center">Item Name</td>
			                     <td align="center">Std. Qty</td>
			                     <td align="center">PRD Qty</td>
			                     <td align="center">Rtn Qty</td>
			                     <td align="center">Extra Qty</td>
			                     <td align="center">Acctual qty</td>
			                     <td align="center">MesuredIn</td>
			                 </tr>
			             <?php
			                foreach ($production->items as $key => $value) {
			             ?>
			                 <tr>
			                     <td align="center"><?php echo $value['item_id'];?></td>
			                     <td><?php echo $value['item_name'];?></td>
			                     <td align="right"><?php echo $value['req_qty'];?></td>
			                     <td align="right"><?php echo $value['production_req_qty'];?></td>
			                     <td align="right"><?php echo $value['return_req_qty'];?></td>
			                     
			                     <td align="right"><?php echo number_format($value['ExtraQty'], 2); ?></td>
			                     <td align="right"><?php echo $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];?></td>
			                     <td align="center"><?php echo $value['unit'];?></td>
			                 </tr>
			             <?php } ?>
			             </tbody>
			         </table>-->
			     </div>
			 </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              
              <div class="_buttons">
				<h4>Production Order</h4>
			  </div>
              <div class="clearfix"></div>
			   <?php echo form_open('admin/production/edit_prd',array('id'=>'productionedit_form')); ?>
			  <div class="row">
			  
			  <?php if(isset($production)){ ?>
                    <input type="hidden" name="new_record" value=" " id="new_record">
					<input type="hidden" name="new_record1" value=" " id="new_record1">
					<input type="hidden" name="defualt_finishgood_qty" value="<?php echo $ReceipeDetails->qty;?>" id="defualt_finishgood_qty">
                <?php } ?>
			  
			    <div class="col-md-2">
				   <label class="control-label" for=""><?php echo _l('Production Order Number'); ?></label>
				   <input type="text" value="<?php echo $production->pro_order_id;?>" name="sr_no" readonly class="form-control">
				   <input type="hidden" value="<?php echo $production->pro_order_id;?>" name="pid" id="pid">
				   <input type="hidden" value="<?php echo $production->recipeID;?>" name="ItemID" id="ItemID">
				   <input type="hidden" value="<?php echo $item_string;?>" name="item_string" id="item_string">
				   <input type="hidden" value="<?php echo $selected_company;?>" name="PlantID" id="PlantID">
				</div>  
               
                <div class="col-md-2">
                    <?php
                        if($production->production_status == "pending"){
                            
                        }else{
                            $attrDate = array(
    				            'disabled' =>true
    				        );
                        }
                    ?>
				   <?php $date = _d(substr($production->Date,0,10));?>
				   <?php echo render_date_input('start_date','Date',$date,$attrDate); ?>
                </div>
				
				<div class="col-md-2">
					<label class="control-label" for=""><?php echo 'BOM ID'; ?></label>
				   <input type="text" value="<?php echo $production->BOMID;?>" name="recipeID" readonly class="form-control">
				   <input type="hidden" value="<?php echo $production->recipeID;?>" name="recipeID1" id="recipeID1" class="form-control">
                </div>
                <div class="col-md-2">
					<label class="control-label" for="recipeName">BOM Name</label>
				   <input type="text" value="<?php echo $production->description;?>" name="recipeName" readonly class="form-control">
                </div>
                 
                <div class="col-md-2">
				    <label class="control-label" for=""><?php echo "FG Quantity"; ?></label>
					<input type="text" value="<?php echo $production->Finish_good_qty;?>" name="qty_product" id="qty_product" readonly class="form-control">
				</div>
				<div class="col-md-2">
				    <label class="control-label" for=""><?php echo "Unit"; ?></label>
					<input type="text" value="<?php echo $production->finish_good_unit;?>" name="unit" readonly class="form-control">
				    <input type="hidden" name="unit_new" id="unit_new" value="<?php echo $production->finish_good_unit;?>">
				</div>	
			</div>	
			
			
			<div class="row" style="margin-top:10px;">
			    
			
                
			 <?php 
			       //if($production->production_status == "pending" || $production->production_status == "cancel"){
			           $value = $production->production_status;
			           if($value =="pending"){
			               $GodownStatus = '';
			           }else{
			               $GodownStatus = 'disabled';
			           }
			           ?>
			    <div class="col-md-2">
			        <input type="hidden" name="OldStatus" id="OldStatus" value="<?php echo $value; ?>">
			        <div class="form-group" app-field-wrapper="status">
                       <label for="status" class="control-label"><?php echo _l('Order Status'); ?></label>
                       <select name="status" id="status" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
                           
                        <?php
                            if($value == "pending"){
                        ?>
                            <option value="pending" <?php if($value == "pending"){ echo "selected";}?>>PENDING</option>
                           <option value="WELDING" <?php if($value == "WELDING"){ echo "selected";}?>>WELDING</option>
                           <option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>CANCEL</option>
                           <?php } ?>
                        <?php
                            if($value == "WELDING"){
                        ?>   
                            <option value="WELDING" <?php if($value == "WELDING"){ echo "selected";}?>>WELDING</option>
                            <option value="ASSEMBLY" <?php if($value == "ASSEMBLY"){ echo "selected";}?>>ASSEMBLY</option>
                            <option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>CANCEL</option>
                           <?php } ?>
                        <?php
                            if($value == "cancel"){
                        ?>
                        <option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>CANCEL</option>
                        <?php } ?>
                        <?php
                            if($value == "ASSEMBLY"){
                        ?>
                            <option value="ASSEMBLY" <?php if($value == "ASSEMBLY"){ echo "selected";}?>>ASSEMBLY</option>
                            <option value="PAINTING" <?php if($value == "PAINTING"){ echo "selected";}?>>PAINTING</option>
                            <option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>CANCEL</option>
                        <?php } ?>
                        
                        <?php
                            if($value == "PAINTING"){
                        ?>
                            <option value="PAINTING" <?php if($value == "PAINTING"){ echo "selected";}?>>PAINTING</option>
                            <option value="MOVEMENT FOR GODOWN" <?php if($value == "MOVEMENT FOR GODOWN"){ echo "selected";}?>>MOVEMENT FOR GODOWN</option>
                            <option value="cancel" <?php if($value == "cancel"){ echo "selected";}?>>CANCEL</option>
                        <?php } ?>
                        
                        <?php
                            if($value == "MOVEMENT FOR GODOWN"){
                        ?>
                            <option value="MOVEMENT FOR GODOWN" <?php if($value == "MOVEMENT FOR GODOWN"){ echo "selected";}?>>MOVEMENT FOR GODOWN</option>
                        <?php } ?>
                        
                       </select>
                   </div>
                   </div>
			 <?php
			       /*}else */if($production->production_status == "MOVEMENT FOR GODOWN" || $production->production_status == "PAINTING"){
			           
			           ?>
			        
			        <div class="col-md-2">
			           <label class="control-label" for="">Actual Finish Goods</label>
			           
				        <input type="text"  name="finish_outcome" class="form-control"  value="<?php echo $production->Finish_good_qty_new;?>" onkeypress="return isNumber(event)">
                        
			        </div> 
			     <?php
			       }
			 ?>
				  
                <div class="col-md-2">
                    <?php $values = $production->FG_Godown; ?>
                    <div class="form-group" app-field-wrapper="fg_store">
                        <small class="req text-danger">* </small>
                        <label for="fg_store" class="form-label">FG Store</label> 
                        <select name="fg_store" id="fg_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($values == $value['AccountID']) echo "selected";?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <?php $values = $production->RM_Godown; ?>
                    <div class="form-group" app-field-wrapper="rm_store">
                        <small class="req text-danger">* </small>
                        <label for="rm_store" class="form-label">RM Store</label> 
                        <select name="rm_store" id="rm_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($values == $value['AccountID']) echo "selected";?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                
                <div class="col-md-2">
                    <?php $values = $production->Scrap_Godown; ?>
                    <div class="form-group" app-field-wrapper="scrap_store">
                        <small class="req text-danger">* </small>
                        <label for="scrap_store" class="form-label">Scrap Store</label> 
                        <select name="scrap_store" id="scrap_store" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="">Non Selected</option>
                        <?php
                            foreach ($GodownData as $key => $value) {
                        ?>
                                <option value="<?php echo $value['AccountID'];?>" <?php if($values == $value['AccountID']) echo "selected";?>><?php echo $value['AccountName'];?></option>
                        <?php
                            }
                        ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
				    <div class="form-group" app-field-wrapper="operator_name">
                       <label for="operator_name" class="control-label">Production Manager</label>
                       <select name="operator_name" id="operator_name" data-live-search="true" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
                           <option value=""></option>
                           <?php
                                foreach ($manager as $key => $value) {
                                ?>
                                <option value="<?php echo $value["AccountID"];?>" <?php if($value["AccountID"] == $production->manager_name){ echo "selected"; }?>><?php echo $value['firstname']." ".$value['lastname']?></option>
                            <?php
                                }
                           ?>
                       </select>
                   </div>
				</div>
                
                <div class="col-md-2">
                    <?php $value = $production->cost_allocation; ?>
                    <div class="form-group" app-field-wrapper="cost_allocation">
                        <label for="cost_allocation" class="control-label">Cost Allocation </label>
                        <input type="text" name="cost_allocation" readonly id="cost_allocation" class="form-control" value="<?php echo $value;?>" >
                    </div>
                </div>    
                	
            </div>
			
			<div class="row" style="margin-top:10px;">	
			  
				<div class="col-md-3 ">
				    <small class="req text-danger">* </small>
				  <label class="control-label" for="comments"><?php echo "BOM Comments"; ?></label>
					<textarea id="comments" name="comments" rows="4" cols="50" class="form-control" ><?php echo $production->comment;?></textarea>
				</div>
				<?php
				    if($production->production_status == "pending"){
				        $welding_style="display: none";
				        $assembly_style="display: none";
				        $painting_style="display: none";
				        $move_for_godown_style="display: none";
				    }elseif($production->production_status == "WELDING"){
				        $welding_style="display: block";
				        $assembly_style="display: none";
				        $painting_style="display: none";
				        $move_for_godown_style="display: none";
				    }elseif($production->production_status == "ASSEMBLY"){
				        $welding_style="display: block";
				        $assembly_style="display: block";
				        $painting_style="display: none";
				        $move_for_godown_style="display: none";
				    }elseif($production->production_status == "PAINTING"){
				        $welding_style="display: block";
				        $assembly_style="display: block";
				        $painting_style="display: block";
				        $move_for_godown_style="display: none";
				    }elseif($production->production_status == "MOVEMENT FOR GODOWN"){
				        $welding_style="display: block";
				        $assembly_style="display: block";
				        $painting_style="display: block";
				        $move_for_godown_style="display: block";
				    }
				?>
			
				<div class="col-md-2 welding_remark" style="<?php echo $welding_style;?>">
				  <label class="control-label" for="">Welding Remark</label>
					<textarea id="welding_remark" name="welding_remark" rows="4" cols="50" class="form-control"><?php  echo $production->welding_remark; ?></textarea>
				</div>
				<div class="col-md-2 assembly_remark" style="<?php echo $assembly_style;?>">
				  <label class="control-label" for="">Assembly Remark</label>
					<textarea id="assembly_remark" name="assembly_remark" rows="4" cols="50" class="form-control"><?php  echo $production->assembly_remark; ?></textarea>
				</div>
				<div class="col-md-2 painting_remark" style="<?php echo $painting_style;?>">
				  <label class="control-label" for="">Painting Remark</label>
					<textarea id="painting_remark" name="painting_remark" rows="4" cols="50" class="form-control"><?php  echo $production->painting_remark; ?></textarea>
				</div>
				<div class="col-md-2 move_for_godown_remark" style="<?php echo $move_for_godown_style;?>">
				  <label class="control-label" for="">Move For Godown Remark</label>
					<textarea id="move_for_godown_remark" name="move_for_godown_remark" rows="4" cols="50" class="form-control"><?php  echo $production->move_for_godown_remark; ?></textarea>
				</div>
				
			</div>
			
			
			  
			  <input type="hidden" value="1" name="countof_record" id="countof_record">
			  <input type="hidden" value="1" name="countof_record1" id="countof_record1">
			  <input type="hidden" value="2" name="sub_group" id="sub_group">
			<div class="row">
			       <div class="col-md-6">
			           <div class="_buttons">
				        <h4>BOM Raw Materials</h4>
			        </div>
			        <table class="table table-striped table-bordered" id="data_table_ex" width="100%">
			             <thead>
			                 <tr>
			                     <th align="center">ItemID</th>
			                     <th align="center">Item Name</th>
			                     <th align="center">Std. Qty</th>
			                     <th align="center">Req Qty</th>
			                     <th align="center">Rtn Qty</th>
			                     <th align="center">Extra Qty</th>
			                     <th align="center">Acctual qty</th>
			                     <th align="center">StockQty</th>
			                     <th align="center">Unit</th>
			                 </tr>
			             </thead>
			             <tbody id="data_table_exBody">
			                
			             <?php
			             $ScrapQty = 0;
			             $StockMatch = 0;
			                foreach ($production->items as $key => $value) {
			                    $PQty = 0;
                                $PRQty = 0;
                                $IQty = 0;
                                $PRDQty = 0;
                                $SQty = 0;
                                $SRTQty = 0;
                                $AQty = 0;
                                $OQty = 0;
                                $GOQty = 0;
                                $GIQty = 0;
                                
                                foreach ($OQtyItems as $qty) {
                                    if($qty['ItemID']==$value['item_id']){
                                        $OQty = $qty['OQty'];
                                    }
                                }
                                foreach ($ItemStocks as $stock) {
                                    if($stock['ItemID']==$value['item_id']){
                                        //$OQty = $stock['OQty'];
                                        if($stock['TType'] == 'P'){
                                            $PQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'N'){
                                            $PRQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'A'){
                                            $IQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'B'){
                                            $PRDQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'O' && $stock['TType2'] == 'Order'){
                                            $SQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'R' && $stock['TType2'] == 'Fresh'){
                                            $SRTQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Free Distribution'){
                                            $AQty += $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Promotional Activity'){
                                            $AQty += $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock Adjustment'){
                                            $AQty += $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'X' && $stock['TType2'] == 'Stock distribution'){
                                            $AQty += $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'In'){
                                            $GIQty = $stock['BilledQty'];
                                        }elseif($stock['TType'] == 'T' && $stock['TType2'] == 'Out'){
                                            $GOQty = $stock['BilledQty'];
                                        }
                                    }
                                }
                            $stockQty = $OQty + $PQty - $PRQty - $IQty + $PRDQty - $SQty + $SRTQty - $AQty + $GIQty - $GOQty;
                            if($value['item_id'] == "SCRAP"){
                                $ScrapQty = $value['production_req_qty'];
                            }else{
                            ?>
                                <tr>
			                     <td align="center"><?php echo $value['item_id'];?></td>
			                     <td><?php echo $value['item_name'];?></td>
			                     <?php
			                        if(strtoupper($value['unit']) == "KGS"){
			                            $PRDStd = number_format($value['req_qty'],3);
			                            $PRDReq = number_format($value['production_req_qty'],3);
			                            $PRDRTN = number_format($value['return_req_qty'],3);
			                            $PRDEXT = number_format($value['ExtraQty'],3);
			                            $PRDAct = number_format($value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'],3);
			                        }else if(strtoupper($value['unit']) == "PCS"){
			                            $PRDStd = (int) $value['req_qty'];
			                            $PRDReq = (int) $value['production_req_qty'];
			                            $PRDRTN = (int) $value['return_req_qty'];
			                            $PRDEXT = (int) $value['ExtraQty'];
			                            $PRDAct = (int) $value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'];
			                        }else{
			                            $PRDStd = number_format($value['req_qty'],3);
			                            $PRDReq = number_format($value['production_req_qty'],3);
			                            $PRDRTN = number_format($value['return_req_qty'],3);
			                            $PRDEXT = number_format($value['ExtraQty'],3);
			                            $PRDAct = number_format($value['production_req_qty'] - $value['return_req_qty'] + $value['ExtraQty'],3);
			                        }
			                     ?>
			                     <td align="right"><?php echo $PRDStd;?></td>
			                     <td align="right"><?php echo $PRDReq;?></td>
			                     <td align="right"><?php echo $PRDRTN;?></td>
			                     <td align="right"><?php echo $PRDEXT; ?></td>
			                     <td align="right"><?php echo $PRDAct;?></td>
			                     <?php
			                     $style = '';
			                        if($PRDAct >$stockQty){
			                            $StockMatch++;
			                            if($production->production_status == "In-Progress" || $production->production_status == "pending"){
			                                $style = 'style = "color:red;border-color:red"';
			                            }
			                        }
			                     ?>
			                     <td align="right" <?php echo $style; ?>><?php echo number_format($stockQty,2);?></td>
			                     <td align="center"><?php echo $value['unit'];?></td>
			                 </tr>
                            <?php
                            }
			             ?>
			                 
			             <?php } ?>
			             </tbody>
			         </table>
			         
			         <div class="_buttons">
				        <h4>Add Scrap Material</h4>
			        </div>
                    <table class="table table-striped table-bordered" id="data_table" width="100%">
                        <thead>
                            <tr> 
								<th>ItemCode</th>
                                <th>Item Name</th>
                                <th>Old Scrap</th>
                                <th>Req.Qty</th>
                                <th>Unit</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr id="R1">
                                <td style="width:10%">SCRAP<input type="hidden" name="Scrap_item_id" id="Scrap_item_id" value="SCRAP"></td>
								
								<td style="width:40%" >SCRAP Item<input type="hidden" name="Scrap_item_name" id="Scrap_item_name" value="SCRAP Item"  /></td>
			                    <td style="width:7%" ><?php echo $ScrapQty;?><input type="hidden" name="Scrap_old_qty" id="Scrap_old_qty"  value="<?php echo $ScrapQty;?>" ></td>
								
                                <td style="width:7%" ><input type="text" name="Scrap_new_qty" id="Scrap_new_qty" style="height: 30px;width: 100%;" value="" ></td>
								
                                <td style="width:7%" >KG<input type="hidden" name="scrap_unit" id="scrap_unit" value="Kg" /></td>
                               
                                <!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
                            </tr>
                        </tbody>
                    </table>
			    </div>	
			    <?php 
                    /*if($production->production_status == "Completed" || $production->production_status == "cancel"){*/
                    if($production->production_status == "cancel"){
                        
                    }else{
                        ?>
			   
			    <div class="col-md-6">
			    </div>
                <div class="col-md-6">
                    <div class="_buttons">
				        <h4>Add Extra Material</h4>
			        </div>
                    <table class="table table-striped table-bordered" id="data_table" width="100%">
                        <thead>
                            <tr> 
								<th>ItemCode</th>
                                <th>ItemName</th>
                                <th>Req.Qty</th>
                                <th>Unit</th>
								<th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr id="R1">
                                <input type="hidden" class="form-control" name="PRDR_ItemID_list" id="PRDR_ItemID_list" value="">
                                <td style="width:10%"><input type="text" name="item_id" id="item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
								
								<td style="width:40%" ><input type="text" name="item_name" id="item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="item_name" id="item_name" value=""><input type="hidden" name="ItemStocks" id="ItemStocks" value=""></td>
			
                                <td style="width:7%" ><input type="text" name="req_qty" id="req_qty"  value="" style="height: 30px;width: 100%;" /><input type="hidden" name="req_qty" id="req_qty" value=""></td>
								
                                <td style="width:7%" ><input type="text" name="unit" id="unit" value="" style="height: 30px;width: 100%;" readonly/><input type="hidden" name="unit" id="unit" value=""></td>
                               
                                <td style="width:5%"></td>
                                <!--<button type="button" name="addBtn" id="add" class="btn btn-xs btn-succes add" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
                            </tr>
                        </tbody>
                    </table>
                    <div class="_buttons">
				        <h4>Return Extra Material</h4>
			        </div>
                    <table class="table table-striped table-bordered" id="data_table1" width="100%">
                        <thead>
                            <tr> 
								<th>ItemCode</th>
                                <th>ItemName</th>
                                <th>Req.Qty</th>
								<th>PRD Req.Qty</th>
								<th>Rtn Req.Qty</th>
                                <th>Unit</th>
								<th>Action</th>
                            </tr>
                        </thead> 
                        <tbody id="tbody1">
                            <input type="hidden" class="form-control" name="PRD_ItemID_list" id="PRD_ItemID_list" value="">
                            <tr id="R2">
                              
                                <td style="width:10%"><input type="text" name="pro_item_id" id="pro_item_id" style="width: 100%;border-radius: 2px;height: 30px;"></td>
								
								<td style="width:30%" ><input type="text" name="pro_item_name" id="pro_item_name" value="" style="height: 30px;width: 100%;" /><input type="hidden" name="pro_item_name" id="pro_item_name" value=""></td>
			
                                <td style="width:7%" ><input type="text" name="pro_req_qty" id="pro_req_qty" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="pro_req_qty" id="pro_req_qty" value=""></td>
								
								<td style="width:7%" ><input type="text" name="production_req_qty" id="production_req_qty" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="production_req_qty_h" id="production_req_qty_h" value=""></td>
								
								<td style="width:7%" ><input type="text" name="return_pro_req_qty" id="return_pro_req_qty" value="" style="height: 30px;width: 100%;"  /><input type="hidden" name="return_pro_req_qty" id="return_pro_req_qty" value=""></td>
								
                                <td style="width:7%" ><input type="text" name="pro_unit" id="pro_unit" value="" style="height: 30px;width: 100%;" readonly /><input type="hidden" name="pro_unit" id="pro_unit" value=""></td>
                               
                                <td style="width:5%"></td>
                                <!--<button type="button" name="addBtn1" id="add1" class="btn btn-xs btn-succes add1" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button>-->
                            </tr>
                        </tbody>
                    </table>
                </div>
			<?php } ?>
			</div>
				
			<div class="row">
			    <br>
				<div class="col-md-1" >
                    <?php 
                    /*if($production->production_status=="Completed"){
                        
                    }else{*/
                if(has_permission_new('production', '', 'edit')){
                
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $lgstaff = $this->session->userdata('staff_user_id');
                $PRDdate = substr($production->TransDate,0,10);
                
                $PRDdate_new    = new DateTime($PRDdate);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                /*$sql = 'SELECT * FROM tblproduction WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblproduction.pro_order_id DESC ';
                $result_data = $this->db->query($sql)->row();
                $lastdate = substr($result_data->TransDate,0,10);*/
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $this->db->select('*');
                $this->db->where('plant_id', $selected_company);
                $this->db->where('year', $fy);
                $this->db->where('staff_id', $lgstaff);
                $this->db->LIKE('feature', "production");
                $this->db->LIKE('capability', "view");
                $this->db->from(db_prefix() . 'staff_permissions');
                $result2 = $this->db->get()->row();
                $day = $result2->days;
                
                if($day == 0){
                            $return = '';
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($PRDdate_new < $tillDate_new) {
                                $return = 'disabled';
                            }else{
                                $return = '';
                            }
                        }
            ?>
            <?php if($return == "disabled"){
            ?>
            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
            <?php
            }else{
                if($production->production_status == "cancel"){
                    
                }else{
            ?>
            <button type="submit" class="btn btn-info">Update</button> 
            <?php }} ?> 
            <?php
                }
            //}
            ?>
                
                </div>
                <div class="col-md-1" >
                    
                    <!--<a href="#" class="btn btn-info add-new-transfer mbot15">show list</a>-->
                    <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                </div>
			</div>
			 <?php echo form_close(); ?>
			 <!-- Hidden table for print-->
			 
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
            <h4 class="modal-title">Production List</h4>
         </div>
         <div class="modal-body">
             
            <div class="row">
                <?php $current_date = date('d/m/Y'); 
                $from_date = '01/'.date('m').'/'.date('Y');
                ?>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date','From',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date','To',$current_date);
                   ?>
                </div>
                <div class="col-md-2">
                    <div class="form-group" app-field-wrapper="status_list">
                       <label for="status_list" class="control-label"><?php echo _l('Order Status'); ?></label>
                       <select name="status_list" id="status_list" class="selectpicker" data-width="100%" data-none-selected-text="Non selected">
                           <option value="pending">Pending</option>
                           <option value="In-Progress">In-Progress</option>
                           <option value="Completed">Completed</option>
                       </select>
                   </div>
                </div>
                <div class="col-md-1">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                <div class="col-md-12">
                 
            <div class="table_production_report">
             
              <table class="tree table table-striped table-bordered table_production_report" id="table_production_report" width="100%">
                  
                <thead>
                    <tr style="display:none;">
                      <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                    <th style="text-align:left;">ProductionID</th>
                    <th style="text-align:left;">PRDDate</th>
                    <th style="text-align:left;">RecipeName</th>
                    <th style="text-align:left;">BatchQty</th>
                    <th style="text-align:left;">FGQty</th>
                    <th style="text-align:left;">ReqTM</th>
                    <th style="text-align:left;">PRDTM</th>
                    <th style="text-align:left;">Man/Con Name</th>
                    <th style="text-align:left;">Status</th>
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
        
      </div>
   </div>
</div>
<style>
    .table_production_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_production_report thead th { position: sticky; top: 0; z-index: 1; }
.table_production_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table_production_report table  { border-collapse: collapse; width: 100%; }
.table_production_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.table_production_report th     { background: #50607b;color: #fff !important; }


#table_production_report tr:hover {
    background-color: #ccc;
}

#table_production_report td:hover {
    cursor: pointer;
}
table  { border-collapse: collapse; width: 100%; }
 th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
 th     { background: #50607b;color: #fff !important; }

</style>
<?php init_tail(); ?>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    $("#productionedit_form").validate({
        rules: {
			batch_qty: "required"
			/*start_date: {
				remote: {
					url: site_url + "admin/misc/CheckPRDDate",
					type: 'post',
					data: {
						Prd_date: function() {
							return $('input[name="start_date"]').val();
						},
						PRDID: function() {
							return $('input[name="pid"]').val();
						}
					}
				}
			},*/
        },
    })
    
    $('#status').on('change',function(){
        var status = $(this).val();
        if(status == "WELDING"){
            $(".welding_remark").css("display", "block");
            $(".assembly_remark").css("display", "none");
            $(".painting_remark").css("display", "none");
            $(".move_for_godown_remark").css("display", "none");
        }else if(status == "ASSEMBLY"){
            $(".welding_remark").css("display", "block");
            $(".assembly_remark").css("display", "block");
            $(".painting_remark").css("display", "none");
            $(".move_for_godown_remark").css("display", "none");
        }else if(status == "PAINTING"){
            $(".welding_remark").css("display", "block");
            $(".assembly_remark").css("display", "block");
            $(".painting_remark").css("display", "block");
            $(".move_for_godown_remark").css("display", "none");
        }else if(status == "MOVEMENT FOR GODOWN"){
            $(".welding_remark").css("display", "block");
            $(".assembly_remark").css("display", "block");
            $(".painting_remark").css("display", "block");
            $(".move_for_godown_remark").css("display", "block");
        }else{
            $(".welding_remark").css("display", "none");
            $(".assembly_remark").css("display", "none");
            $(".painting_remark").css("display", "none");
            $(".move_for_godown_remark").css("display", "none");
        }
    });
    
 
  function load_data(from_date,to_date,status_list)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>production/load_data_for_production",
      dataType:"JSON",
      method:"POST",
      data:{from_date:from_date, to_date:to_date,status_list:status_list},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table_production_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table_production_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        var html = '';
      
        for(var count = 0; count < data.length; count++)
        {
          
        var url = "'<?php echo admin_url() ?>production/production_order/"+data[count].pro_order_id+"'";
        html += '<tr onclick="location.href='+url+'">';
        html += '<td style="text-align:center;">'+data[count].pro_order_id+'</td>';
          
        var date = data[count].TransDate.substring(0, 10);
        var date_new = date.split("-").reverse().join("/");
          
        html += '<td  style="text-align:center;">'+date_new+'</td>';
        html += '<td style="text-align:center;">'+data[count].recipeID+'</td>';
        html += '<td style="text-align:center;">'+data[count].batch_qty+'</td>';
        html += '<td style="text-align:center;">'+data[count].Finish_good_qty_new+'</td>';
        html += '<td style="text-align:center;">'+data[count].required_time+'</td>';
        html += '<td style="text-align:center;">'+data[count].production_time+'</td>';
          if(data[count].contractor_name == null){
              if(data[count].lastname == null){
                  var AccoutName = data[count].firstname;
              }else{
                  var AccoutName = data[count].firstname + data[count].lastname;
              }
              
          }else{
              var AccoutName = data[count].conName;
          }
          html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
          html += '<td  style="text-align:center;">'+data[count].production_status+'</td>';
          html += '</tr>';
        }
         $('.table_production_report tbody').html(html);
      
      }
    });
  }
  
    $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var status_list = $("#status_list").val();
        load_data(from_date,to_date,status_list);
        
    });

});
</script>
<script>
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_production_report");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
<script>
    $('.add-new-transfer').on('click', function(){
    $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
      $('#transfer-modal').modal('show');
    });
</script>


<script type='text/javascript'>

$(document).ready(function () {
    var rowIdx = 1;
    


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
  var  no = $(this).parents("tr").find('input[name="rownum"]').val();
  var item_name =$(this).parents("tr").find('input[name="item_name'+no+'"]').val();
  var item_id =$(this).parents("tr").find('input[name="item_id'+no+'"]').val();
   // alert(no);
	
   
    // Removing the current row.
    $(this).closest('tr').remove();
  
    // Decreasing the total number of rows by 1.
    rowIdx--;
    var countof_record = $("#countof_record").val();
    var new_cont = countof_record -1;
    $("#countof_record").val(new_cont);
    
    var new_rec = $('#new_record').val();
    $new_item_name = ','+item_name;
    new_rec = new_rec.replace($new_item_name, " ");
    $('#new_record').val(new_rec);
    
    var PRDR_ItemID_list = $('#PRDR_ItemID_list').val();
    var item_id = ','+item_id;
    var PRDR = PRDR_ItemID_list.replace(item_id, "");
    $('#PRDR_ItemID_list').val(PRDR);
    
});


// new code 

$('#data_table').on('click', '.add', function () {
    var req_qty = $("#req_qty").val();
	var item_id = $("#item_id").val();
	var item_name = $("#item_name").val();
	//alert(item_id);
	if(item_id == "" || item_id == null ){
        alert("Select Item ID.");
		focus(item_id);
    }else if(item_name == "" || item_name == null ){
        alert("Select Item Name.");
		focus(item_name);
    }else if(req_qty == "" || req_qty == null ){
        alert("Add Require Quantity.");
		focus(req_qty);
    }else{
        add_row();
    }  
})

$('#req_qty').on('blur', function () {
    
    var req_qty = $("#req_qty").val();
	var item_id = $("#item_id").val();
	var item_name = $("#item_name").val();
	var ItemStock = $("#ItemStocks").val();
	var PRDStatus = $("#OldStatus").val();
	var PlantID = $("#PlantID").val();
	var Val = $(this).val();
	//alert(item_id);
	if(item_id == "" || item_id == null ){
        alert("Select Item ID.");
		focus(item_id);
    }else if(item_name == "" || item_name == null ){
        alert("Select Item Name.");
		focus(item_name);
    }else if(req_qty == "" || req_qty == null ){
        alert("Add Require Quantity.");
		//focus(req_qty);
		focus(item_name);
    }else if(parseFloat(ItemStock) < parseFloat(Val) && PRDStatus == "In-Progress"){
        alert('Item Stock not available...'+ItemStock);
    }else{
        add_row();
    }  
});


function add_row()
{  
 var item_id =document.getElementById("item_id").value;
 var item_name =document.getElementById("item_name").value;
 var req_qty =document.getElementById("req_qty").value;
 var unit =document.getElementById("unit").value;
 
 var countof_record = document.getElementById("countof_record").value;
	
 var table=document.getElementById("data_table");
 var table_len=(table.rows.length)-1;
 var html = '';
 html += "<tr id='row"+table_len+"'>";
 html += "<td id='item_id"+table_len+"'>"+item_id+" <input type='hidden' name='item_id"+table_len+"' value='"+item_id+"'></td>";
 html += "<td id='item_name"+table_len+"'>"+item_name+" <input type='hidden' name='item_name"+table_len+"' value='"+item_name+"'></td>";
 html += "<td id='req_qty"+table_len+"'>"+req_qty+" <input type='hidden' name='req_qty"+table_len+"' value='"+req_qty+"'></td>";
 html += "<td id='unit"+table_len+"'>"+unit+" <input type='hidden' name='unit"+table_len+"' value='"+unit+"'></td>";
 
 //html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum' id='rownum'></td>";
 html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+countof_record+'"></td>';
 html += '</tr>';
 var row = table.insertRow(table_len).outerHTML=html;
 
  var temp1 = parseFloat(countof_record) + parseFloat(1);
 
 document.getElementById("countof_record").value=temp1;

      //  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
      //  }  else{
            var new_rec = $('#new_record').val();
            
                new_rec = new_rec +","+ item_id
            
              $('#new_record').val(new_rec);
			  //alert(new_rec);
      //  }
 
        $(this).parents("tr").find(".edit_row").show();  
        $(this).parents("tr").find(".btn-cancel").remove();  
        $(this).parents("tr").find(".btn-update").remove();
 
 
document.getElementById("item_id").value="";
document.getElementById("item_name").value="";
document.getElementById("req_qty").value="";
document.getElementById("unit").value="";

document.getElementById("item_name").innerHTML="";
document.getElementById("req_qty").innerHTML="";
document.getElementById("unit").innerHTML="";
 
document.getElementById("item_id").focus(); 
}

 }); 

</script>

<script type='text/javascript'>
    $(document).ready(function(){
		
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
                
                $('#item_code').val(ui.item.label);
				$('#item_desc').val(ui.item.label);
				$('#unit1').val(ui.item.units); // save selected id to input
            }
       
      });
    });
   
    </script>
 
	<script type='text/javascript'>
       
	   $(document).ready(function(){

     // Initialize 
	 //By Item Code
     $( "#item_id" ).autocomplete({
        source: function( request, response ) {
            var proId = $('#pid').val();
          // Fetch data
          $.ajax({
			url: "<?=base_url()?>admin/production/itemlist_subgroup",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term, 'ProductionId' : proId
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
          // Set selection
          var item_string = $("#item_string").val();
          let item_array = item_string.split(",");
          if(item_array.includes(ui.item.value)){
              
              $('#item_id').val(ui.item.value); // display the selected text
              $('#item_name').val(ui.item.label); // save selected id to input
    		  $('#unit').val(ui.item.unit); // save selected id to input
    		  $('#item_name').focus();
              return false;
          }else{
              alert("item not prasent in selected recipe");
          }
          
        }
      });
      
      // blur from ItemID 
      $('#item_id').on('blur',function(){
         
        var ItemID = $(this).val();
        if(ItemID == "" || ItemID == null){
            
        }else{
            var PRDR_ItemID_list = $("#PRDR_ItemID_list").val();
            let PRDR_ItemID_list_array = PRDR_ItemID_list.split(",");
            if(PRDR_ItemID_list_array.includes(ItemID.toUpperCase())){
              alert("item already added");
                    $('#item_id').val('');
                    $('#item_name').val('');
                    $('#unit').val("");
                    $('#item_id').focus();
            }else{
                var proId = $('#pid').val();
                $.ajax({
                  url:"<?php echo admin_url(); ?>production/get_item_details",
                  dataType:"JSON",
                  method:"POST",
                  cache: false,
                  data:{ItemID:ItemID,proId:proId},
                  
                  success:function(data){
                    if(empty(data)){
                        alert('Item not found...');
                        $('#item_id').val('');
                        $('#item_name').val('');
                        $('#unit').val("");
                        $('#item_id').focus();
                    }else{
                        $("#PRDR_ItemID_list").val(PRDR_ItemID_list+","+data.item_id);
                        $('#item_id').val(data.item_id);
                        $('#item_name').val(data.item_name);
                        $('#unit').val(data.unit);
                        $('#ItemStocks').val(parseFloat(data.ItemStocks).toFixed(2));
                        $('#req_qty').focus();
                    }
                    
                  }
                });
            }
            
        }
        
     })
     
       // Focus from ItemID 
      $('#item_id').on('focus',function(){
         
        $('#item_id').val('');
        $('#item_name').val('');
        $('#unit').val("");
        
     })
	  
    });
	   
    </script>
	
<script>
	function cal(){
	var req_qty = document.getElementById('req_qty').value;
	//alert(amount);
	var batch_qty = document.getElementById('batch_qty').value;
	//alert(batch_qty);
	var result = parseFloat(req_qty) * parseFloat(batch_qty);
	document.getElementById('pro_req_qty').value = result; 	
  }
</script>

<script>
$(document).ready(function () {
    var rowIdx = 1;
    


$('#tbody1').on('click', '.remove', function () {
  
    // Getting all the rows next to the 
    // row containing the clicked button
    var child1 = $(this).closest('tr').nextAll();
  
    // Iterating across all the rows 
    // obtained to change the index
    child1.each(function () {
          
        // Getting <tr> id.
        var id1 = $(this).attr('id');
  
        // Getting the <p> inside the .row-index class.
        var idx = $(this).children('.row-index').children('p');
  
        // Gets the row number from <tr> id.
        var dig = parseInt(id1.substring(1));
  
        // Modifying row index.
        idx.html(`Row ${dig - 1}`);
  
        // Modifying row id.
        $(this).attr('id', `R${dig - 1}`);
    });
  var  no1 = $(this).parents("tr").find('input[name="rownum1"]').val();
  var pro_item_name =$(this).parents("tr").find('input[name="pro_item_name'+no1+'"]').val();
  var pro_item_id =$(this).parents("tr").find('input[name="pro_item_id'+no1+'"]').val();
    //alert(no1);
	
   
    // Removing the current row.
    $(this).closest('tr').remove();
  
    // Decreasing the total number of rows by 1.
    rowIdx--;
    var countof_record1 = $("#countof_record1").val();
    var new_cont1 = countof_record1 -1;
    $("#countof_record1").val(new_cont1);
    
    var new_rec1 = $('#new_record1').val();
    $new_pro_item_name = ','+pro_item_name;
    new_rec1 = new_rec1.replace($new_pro_item_name, " ");
    $('#new_record1').val(new_rec1);
    
    var PRD_ItemID_list = $('#PRD_ItemID_list').val();
    var ItemID = ','+pro_item_id;
    var PRD = PRD_ItemID_list.replace(ItemID, "");
    $('#PRD_ItemID_list').val(PRD);
    
});

// new code 



$('#return_pro_req_qty').on('blur', function () {
    
    var return_pro_req_qty = $("#return_pro_req_qty").val();
    var prd_qty = $("#production_req_qty_h").val();
    //alert(return_pro_req_qty);
    //alert(prd_qty);
	var pro_item_id = $("#pro_item_id").val();
	var pro_item_name = $("#pro_item_name").val();
	
	//alert(pro_item_id);
	if(pro_item_id == "" || pro_item_id == null ){
        alert("Select Item ID.");
		focus(pro_item_id);
    }else if(pro_item_name == "" || pro_item_name == null ){
        alert("Select Item Name.");
		focus(pro_item_name);
    }else if(return_pro_req_qty == "" || return_pro_req_qty == null ){
        alert("Add Return Quantity.");
		focus(return_pro_req_qty);
    }else if(parseFloat(return_pro_req_qty) > parseFloat(prd_qty)){
	    alert("Please enter quantity less than or equal to production quantity");
		$("#return_pro_req_qty").val('');
		//$('#return_pro_req_qty').focus();
	}else{
        add_row1();
    }    
});



function add_row1()
{  
 var pro_item_id =document.getElementById("pro_item_id").value;
 var pro_item_name =document.getElementById("pro_item_name").value;
 var pro_req_qty =document.getElementById("pro_req_qty").value;
 var production_req_qty =document.getElementById("production_req_qty").value;
 var return_pro_req_qty =document.getElementById("return_pro_req_qty").value;
 var pro_unit =document.getElementById("pro_unit").value;
 
 var countof_record1 = document.getElementById("countof_record1").value;
	
 var table1=document.getElementById("data_table1");
 var table_len1=(table1.rows.length)-1;
 var html = '';
 html += "<tr id='row"+table_len1+"'>";
 html += "<td id='pro_item_id"+table_len1+"'>"+pro_item_id+" <input type='hidden' name='pro_item_id"+table_len1+"' value='"+pro_item_id+"'></td>";
 html += "<td id='pro_item_name"+table_len1+"'>"+pro_item_name+" <input type='hidden' name='pro_item_name"+table_len1+"' value='"+pro_item_name+"'></td>";
 html += "<td id='pro_req_qty"+table_len1+"'>"+pro_req_qty+" <input type='hidden' name='pro_req_qty"+table_len1+"' value='"+pro_req_qty+"'></td>";
 html += "<td id='production_req_qty"+table_len1+"'>"+production_req_qty+" <input type='hidden' name='production_req_qty"+table_len1+"' value='"+production_req_qty+"'></td>";
 html += "<td id='return_pro_req_qty"+table_len1+"'>"+return_pro_req_qty+" <input type='hidden' name='return_pro_req_qty"+table_len1+"' value='"+return_pro_req_qty+"'></td>";
 html += "<td id='pro_unit"+table_len1+"'>"+pro_unit+" <input type='hidden' name='pro_unit"+table_len1+"' value='"+pro_unit+"'></td>";
 
 //html += "<td><input type='button' value='Delete' class='remove' ><input type='hidden' name='rownum1' id='rownum1'></td>";
 html += '<td><button type="button" name="edit1" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum1" id="rownum1" value="'+countof_record1+'"></td>';
 html += '</tr>';
 var row = table1.insertRow(table_len1).outerHTML=html;
 var temp1 = parseFloat(countof_record1) + parseFloat(1);
 document.getElementById("countof_record1").value=temp1;

      //  if($('#ex_sale_return_id').val() == "" || $('#ex_sale_return_id').val() == null){
            
      //  }  else{
            var new_rec1 = $('#new_record1').val();
			
            
                new_rec1 = new_rec1 +","+ pro_item_id
              $('#new_record1').val(new_rec1);
			 // alert(new_rec1);
      //  }
 
        $(this).parents("tr").find(".edit1_row").show();  
        $(this).parents("tr").find(".btn-cancel").remove();  
        $(this).parents("tr").find(".btn-update").remove();
 
 
document.getElementById("pro_item_id").value="";
document.getElementById("pro_item_name").value="";
document.getElementById("pro_req_qty").value="";
document.getElementById("production_req_qty").value="";
document.getElementById("return_pro_req_qty").value="";
document.getElementById("pro_unit").value="";

document.getElementById("pro_item_name").innerHTML="";
document.getElementById("pro_req_qty").innerHTML="";
document.getElementById("production_req_qty").innerHTML="";
document.getElementById("pro_unit").innerHTML="";
 
document.getElementById("pro_item_id").focus(); 
}
 
 }); 

</script>

	<script type='text/javascript'>
       
	   $(document).ready(function(){

     // Initialize 
	 //By Item Code
     $( "#pro_item_id" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
		  var proId = $('#pid').val();
		  //alert(proId);
          $.ajax({
			url: "<?=base_url()?>admin/production/itemlist_subgroup1",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term, 'ProductionId' : proId
            },
			
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
                $('#pro_item_id').val(ui.item.value); // display the selected text
                $('#pro_item_name').val(ui.item.label); // save selected id to input pro_req_qty
    		    $('#pro_req_qty').val(ui.item.req_qty); // save selected id to input 
    		    $('#production_req_qty').val(ui.item.pro_req_qty); // save selected id to input
    		    $('#production_req_qty_h').val(ui.item.pro_req_qty); // save selected id to input
    		    $('#pro_unit').val(ui.item.unit); // save selected id to input
                $('#pro_item_name').focus();
                return false;
        }
      });
      
      // blur from ItemID 
      $('#pro_item_id').on('blur',function(){
         
        var ItemID = $(this).val();
        if(ItemID == "" || ItemID == null){
            
        }else{
            
            var PRD_ItemID_list = $("#PRD_ItemID_list").val();
            let PRD_ItemID_list_array = PRD_ItemID_list.split(",");
            if(PRD_ItemID_list_array.includes(ItemID.toUpperCase())){
              alert("item already added");
                $('#pro_item_id').val('');
                $('#pro_item_name').val('');
                $('#pro_req_qty').val("");
                $('#production_req_qty').val("");
                $('#pro_unit').val("");
                $('#pro_item_id').focus();
            }else{
                var proId = $('#pid').val();
                $.ajax({
                  url:"<?php echo admin_url(); ?>production/get_item_details",
                  dataType:"JSON",
                  method:"POST",
                  cache: false,
                  data:{ItemID:ItemID,proId:proId},
                  
                  success:function(data){
                    if(empty(data)){
                        alert('Item not found...');
                        $('#pro_item_id').val('');
                        $('#pro_item_name').val('');
                        $('#pro_req_qty').val("");
                        $('#production_req_qty').val("");
                        $('#pro_unit').val("");
                        $('#pro_item_id').focus();
                    }else{
                        $("#PRD_ItemID_list").val(PRD_ItemID_list+","+data.item_id);
                        $('#pro_item_id').val(data.item_id);
                        $('#pro_item_name').val(data.item_name);
                        $('#pro_req_qty').val(data.req_qty);
                        $('#production_req_qty').val(data.production_req_qty);
                        $('#production_req_qty_h').val(data.production_req_qty);
                        $('#pro_unit').val(data.unit);
                        $('#return_pro_req_qty').focus();
                    }
                    
                  }
                });  
            }
        }
        
     })
     
       // Focus from ItemID 
      $('#pro_item_id').on('focus',function(){
         
        $('#pro_item_id').val('');
        $('#pro_item_name').val('');
        $('#pro_req_qty').val("");
        $('#production_req_qty').val("");
        $('#pro_unit').val("");
        
     });
     
    // blur From batch quntity
    $('#batch_qty').on('change',function(){
        $('#batchqtyChg').val('1')
    })
    $('#batch_qty').on('blur',function(){
    var batch = $(this).val();
    
    var recipeID =$('#recipeID1').val();
        if(batch < 1 ){
            alert('please select atleast 1 qty..');
            $('#batch_qty').val('1');
            $.ajax({
                      url:"<?php echo admin_url(); ?>production/get_recipe_details",
                      dataType:"JSON",
                      method:"POST",
                      cache: false,
                      data:{recipeID:recipeID},
                      success:function(data){
            				$('#qty_product').val(data.qty);
            				$('#finishgood_qty').val(data.qty);
            				showData();
                      }
                });
        }else{
            var defualt_f_g_qty = $('#defualt_finishgood_qty').val();
            var final_f_g_qty = parseFloat(defualt_f_g_qty) * parseFloat(batch);
            $('#qty_product').val(final_f_g_qty);
        	showData();
        }
    
    });
     
});
	
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
function showData() {
    var recipeID =$('#recipeID1').val();
    var batch = $('#batch_qty').val();
    var PONumber = $('#pid').val();
        if(recipeID == ""){
            //$("#data_table_exBody").html();
        }else{
            
            $.ajax({
                type:'POST',
                url: "<?=base_url()?>admin/production/ReceipeData",
                data:{'recipeID':recipeID, 'batchQuantity' : batch || '1','PONumber':PONumber},
                
                success:function(data){
                
                $("#data_table_exBody").html(data);
                }  
            }); 
        }
        
}
</script>

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

<script type="text/javascript">
   $('#return_pro_req_qty').on('keypress',function (event) {
    var unit = $('#pro_unit').val();
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

<script>
    $(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y > fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    var minStartDate = new Date(year, 03);
   
    $('#start_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    });
</script> 
	
