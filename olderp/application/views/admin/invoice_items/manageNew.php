<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="searchh2" style="display:none;">Please wait fetching data...</div>
                        <div class="searchh3" style="display:none;">Please wait Create new ItemID...</div>
                    </div>
                    <br>
                    <div class="col-md-2">
                        <?php echo render_input('item_code1','Item Code','','text'); ?>
                        <input type="hidden" id="item_code" name="item_code" class="form-control" value="0">
                        <span id="lblError" style="color: red"></span>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('description','invoice_item_add_edit_description'); ?>
                        <input type="hidden" id="rate" name="rate" class="form-control" value="0">
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="tax"><?php echo _l('gst'); ?></label>
                            <select class="selectpicker display-block" data-width="100%" id="tax" name="tax" data-none-selected-text="<?php echo _l('no_gst'); ?>">
                                <!--<option value=""></option>-->
                                <?php foreach($taxes as $tax){ ?>
                                <option value="<?php echo $tax['id']; ?>" ><?php echo $tax['taxrate']; ?>%</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label" for="unit"><?php echo 'Unit'; ?></label>
                            <select class="selectpicker display-block" data-width="100%" name="unit" id="unit" data-none-selected-text="<?php echo _l('no_measured_in'); ?>">
                                <option value="Pcs">Pcs</option>
                                <option value="Kgs">Kgs</option>
                                <option value="Gms">Gms</option>
                                <option value="Ltrs">Ltrs</option>
                                <option value="Mtrs">Mtrs</option>
                                <option value="MT">MT</option>
                                <option value="FT">Feet</option>
                                <option value="Nos">Nos</option>
                                <option value="Inches">Inches</option>
                                <option value="Set">Set</option>
                                
                            </select>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                         <?php echo render_select('subgroup_id',$items_sub_groups,array('id','name'),'item_sub_group'); ?>
                    </div>
                    
                    <div class="col-md-3">
                         <?php echo render_select('group_id',$items_groups,array('id','name'),'item_group'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Local Supply In</label>
                             <select class="selectpicker" name="local_supply_in" id="local_supply_in" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="CR">Crate</option> 
                                <option value="CS">Case</option> 
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                           <label class="form-label">Outst. Supply In</label>
                            <select class="selectpicker" name="outst_supply_in" id="outst_supply_in" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="CR">Crate</option> 
                                <option value="CS">Case</option> 
                            </select> 
                        </div> 
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Crate Qty</label>
                            <input type="text" name="crate_qty" id="crate_qty" class="form-control" value="0"  onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Case Qty</label>
                            <input type="text" name="case_qty" id="case_qty" class="form-control" value="0" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Bowl Qty</label>
                            <input type="text" name="bowl_qty" id="bowl_qty" class="form-control" value="0" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Min Qty</label>
                            <input type="text" name="min_qty" id="min_qty" class="form-control" value="0" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Case Weight</label>
                            <input type="text" name="case_weight" id="case_weight" class="form-control" value="0" >
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Min Days</label>
                            <input type="text" name="min_day" id="min_day" class="form-control" value="0" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">MonitorStock?</label>
                            <select class="selectpicker" id= "monitorstock" name="monitorstock" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="Y">Y</option> 
                                <option value="N">N</option> 
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">HSN Code</label>
                            <select class="selectpicker" name="hsn_code" id="hsn_code" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                            <?php
                            foreach ($hsn as $key => $value) {
                            ?>
                                <option value="<?php echo $value['name']; ?>"><?php echo $value['name']; ?></option>   
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Rack ID</label>
                            <select class="selectpicker" name="rack_id" id="rack_id" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                            <?php
                            foreach ($items_rack as $key => $value) {
                            ?>
                                <option value="<?php echo $value['RackID']; ?>"><?php echo $value['RackName']; ?></option>   
                            <?php  
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Sub RackId</label>
                            <select class="selectpicker" name="subrack_id" id="subrack_id" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                                <option value="A">A</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Is Active?</label>
                            <select class="selectpicker" name="isactive" id="isactive" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="Y">Active</option> 
                                <option value="N">Deactive</option> 
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <div class="col-md-9">
                        <table>
                            <thead>
                                <tr>
                                    <th width="40%">CompanyName</th>
                                    <th width="30%">OpeningStock</th>
                                    <th width="30%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($RootCompany as $key => $value) {
                                $staff_user_id = $this->session->userdata('staff_user_id');
                                $selected_company = $this->session->userdata('root_company');
                                /*$showstatus = 'disabled';
                                if($staff_user_id == "3"){
                                    $showstatus = '';
                                }else{
                                    if($value['id'] == $selected_company){
                                        if (has_permission_new('items', '', 'edit')) {
                                            $showstatus = '';
                                        }
                                    }
                                }*/
                                
                            ?>
                                <tr>
                                    <td width="40%"><?php echo $value['company_name']; ?></td>
                                    <td width="30%"><input type="text" name="OQTY" id="OQTY<?php echo $value['id']; ?>" value="" class="form-control OQTY" <?php if($staff_user_id !== "3"){ echo "disabled";}?> style="height: 35px;"></td>
                                    <td width="30%">
                                        <select class="selectpicker" name="isactiveAll"  id="isactive<?php echo $value['id']; ?>" data-width="100%" data-none-selected-text="-- Item not found --" data-live-search="false">
                                            <option value= ''>-- Select status --</option>
                                            <option value="Y">Active</option> 
                                            <option value="N">Deactive</option> 
                                        </select>
                                    </td>
                                </tr>
                            <?php  
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="clearfix"></div>
                    <br><br>
                    <div class="col-md-12">
                        <?php if (has_permission_new('items', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission_new('items', '', 'edit')) {
                        ?>
                        <button type="button" class="btn btn-info updateBtn" style="margin-right: 25px;">Update</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
                        <?php
                        }?>
                        
                        <button type="button" class="btn btn-default cancelBtn" >Cancel</button>
                    </div>
                </div>
                
                <div class="clearfix"></div>
            <!-- Iteme List Model-->
            
                <div class="modal fade Item_List" id="Item_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Item List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Item_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-Item_List tableFixHead2" id="table_Item_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">Item Code <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>
                                            <th style="text-align:left;">Item Name</th>
                                            <th style="text-align:left;">MeasuredIn</th>
                                            <th style="text-align:left;">Division Name</th>
                                            <th style="text-align:left;">Group Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_ItemID" data-id="<?php echo $value["item_code"]; ?>">
                                            <td><?php echo $value['item_code'];?></td>
                                            <td><?php echo $value['description'];?></td>
                                            <td><?php echo $value["unit"];?></td>
                                            <td><?php echo $value["group_name"];?></td>
                                            <td><?php echo $value["subgroup_name"];?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>   
                            </div>
                        </div>
                        <div class="modal-footer" style="padding:0px;">
                            <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
                        </div>
                        </div>
                    <!-- /.modal-content -->
                    </div>
                <!-- /.modal-dialog -->
                </div>
            <!-- /.modal -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

<script>
    $(document).ready(function(){
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#item_code1").dblclick(function(){
            $('#Item_List').modal('show');
            $('#Item_List').on('shown.bs.modal', function () {
              $('#myInput1').focus();
            })
        });
    // ItemID Typing Validation
        $("#item_code1").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            if(keyCode == ""){
                $("#lblError").html("");
            }else{
                //Regex for Valid Characters i.e. Alphabets and Numbers.
                var regex = /^[A-Za-z0-9]+$/;
                //Validate TextBox value against the Regex.
                var isValid = regex.test(String.fromCharCode(keyCode));
                if (!isValid) {
                    $("#lblError").html("Only Alphabets and Numbers allowed.");
                }else{
                    $("#lblError").html("");
                }
                return isValid;
            }
        });
        
    // Empty and open create mode
        $("#item_code1").focus(function(){
            $('#item_code1').val('');
            $('#item_code').val('');
            $('#description').val('');
            $('#crate_qty').val('');
            $('#case_qty').val('');
            $('#bowl_qty').val('');
            $('#min_qty').val('');
            $('#case_weight').val('');
            $('#min_day').val('');
            $('#OQTY').val('');
            $('input[name=OQTY]').val('');
            
            $('select[name=isactiveAll]').val('');
            $('.selectpicker').selectpicker('refresh');
                        
            $('select[name=tax]').val('1');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=unit]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=subgroup_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=group_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=local_supply_in]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=outst_supply_in]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=monitorstock]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=hsn_code]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=rack_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=subrack_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=isactive]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            $('#item_code1').val('');
            $('#item_code').val('');
            $('#description').val('');
            $('#crate_qty').val('');
            $('#case_qty').val('');
            $('#bowl_qty').val('');
            $('#min_qty').val('');
            $('#case_weight').val('');
            $('#min_day').val('');
            $('#OQTY').val('');
            $('input[name=OQTY]').val('');
            
            $('select[name=isactiveAll]').val('');
            $('.selectpicker').selectpicker('refresh');
                        
            $('select[name=tax]').val('1');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=unit]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=subgroup_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=group_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=local_supply_in]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=outst_supply_in]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=monitorstock]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=hsn_code]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=rack_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=subrack_id]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=isactive]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // On Blur ItemID Get All Date
        $('#item_code1').blur(function(){ 
            ItemID = $(this).val();
            if(ItemID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/GetItemDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{ItemID:ItemID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                    init_selectpicker();
                    if(data == null){
                        alert('Item not found...')
                        $('#item_code').val('');
                        $('#description').val('');
                        $('#crate_qty').val('');
                        $('#case_qty').val('');
                        $('#bowl_qty').val('');
                        $('#min_qty').val('');
                        $('#case_weight').val('');
                        $('#min_day').val('');
                        $('input[name=OQTY]').val('');
                        
                        $('select[name=isactiveAll]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=tax]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=unit]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subgroup_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=group_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=local_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=outst_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=monitorstock]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=hsn_code]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=rack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subrack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=isactive]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else{
                        $('#item_code1').val(data.item_code);
                       $('#item_code').val(data.item_code);
                       $('#description').val(data.description);
                       $('#crate_qty').val(data.crate_qty);
                       $('#case_qty').val(data.case_qty);
                       $('#bowl_qty').val(data.bowl_qty);
                       $('#min_qty').val(data.min_qty);
                       $('#case_weight').val(data.case_weight);
                       $('#min_day').val(data.min_day);
                       
                       
                       $('select[name=tax]').val(data.taxid);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=unit]').val(data.unit);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=subgroup_id]').val(data.subgroup_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=group_id]').val(data.group_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=local_supply_in]').val(data.local_supply_in);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=outst_supply_in]').val(data.outst_supply_in);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=monitorstock]').val(data.monitorstock);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=hsn_code]').val(data.hsn_code);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=rack_id]').val(data.rack_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=subrack_id]').val(data.subrack_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=isactive]').val(data.isactive);
                       $('.selectpicker').selectpicker('refresh');
                       
                       if(data.stocks){
                           let stockArray = data.stocks;
                           for(var count = 0; count < stockArray.length; count++)
                            {
                                var PlantID = stockArray[count].PlantID;
                                $('#OQTY'+PlantID+'').val(stockArray[count].OQty);
                            }
                       }
                        if(data.itemStatus){
                           let itemStatusArray = data.itemStatus;
                           for(var count = 0; count < itemStatusArray.length; count++)
                            {
                                var PlantID = itemStatusArray[count].PlantID;
                                var stvalue = itemStatusArray[count].isactive;
                                $('select[id=isactive'+PlantID+']').val(stvalue);
                                $('.selectpicker').selectpicker('refresh');
                            }
                        }
                       
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                    } 
                }
            });
            }
            
        });
        
        $('.get_ItemID').on('click',function(){ 
            ItemID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/GetItemDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{ItemID:ItemID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                    init_selectpicker();
                       $('#item_code1').val(data.item_code);
                       $('#item_code').val(data.item_code);
                       $('#description').val(data.description);
                       $('#crate_qty').val(data.crate_qty);
                       $('#case_qty').val(data.case_qty);
                       $('#bowl_qty').val(data.bowl_qty);
                       $('#min_qty').val(data.min_qty);
                       $('#case_weight').val(data.case_weight);
                       $('#min_day').val(data.min_day);
                       
                       
                        
                       $('select[name=tax]').val(data.taxid);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=unit]').val(data.unit);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=subgroup_id]').val(data.subgroup_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=group_id]').val(data.group_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=local_supply_in]').val(data.local_supply_in);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=outst_supply_in]').val(data.outst_supply_in);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=monitorstock]').val(data.monitorstock);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=hsn_code]').val(data.hsn_code);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=rack_id]').val(data.rack_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=subrack_id]').val(data.subrack_id);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=isactive]').val(data.isactive);
                       $('.selectpicker').selectpicker('refresh');
                       
                       let stockArray = data.stocks;
                       for(var count = 0; count < stockArray.length; count++)
                        {
                            var PlantID = stockArray[count].PlantID;
                            $('#OQTY'+PlantID+'').val(stockArray[count].OQty);
                        }
                        if(data.itemStatus){
                           let itemStatusArray = data.itemStatus;
                           for(var count = 0; count < itemStatusArray.length; count++)
                            {
                                var PlantID = itemStatusArray[count].PlantID;
                                var stvalue = itemStatusArray[count].isactive;
                                $('select[id=isactive'+PlantID+']').val(stvalue);
                                $('.selectpicker').selectpicker('refresh');
                            }
                        }
                       
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                }
            });
            $('#Item_List').modal('hide');
        });
        
    // Save New Item
        $('.saveBtn').on('click',function(){ 
            item_code = $('#item_code1').val();
            description = $('#description').val();
            crate_qty = $('#crate_qty').val();
            case_qty = $('#case_qty').val();
            bowl_qty = $('#bowl_qty').val();
            min_qty = $('#min_qty').val();
            case_weight = $('#case_weight').val();
            min_day = $('#min_day').val();
            tax = $('#tax').val();
            unit = $('#unit').val();
            subgroup_id = $('#subgroup_id').val();
            group_id = $('#group_id').val();
            local_supply_in = $('#local_supply_in').val();
            outst_supply_in = $('#outst_supply_in').val();
            monitorstock = $('#monitorstock').val();
            hsn_code = $('#hsn_code').val();
            rack_id = $('#rack_id').val();
            subrack_id = $('#subrack_id').val();
            isactive = $('#isactive').val();
            OQty = $('input[name=OQTY]').val();
            var OQty = '';
	        var favorite = [];
            $.each($("input[name='OQTY']"), function(){
                if($(this).val() == ''){
                    favorite.push('0');
                }else{
                    favorite.push($(this).val());
                }
            });
	        var OQty_new = favorite.join(",");
	        
	        ItemStatus = $('input[name=isactiveAll]').val();
            var ItemStatus = '';
	        var favorite1 = [];
            $.each($("select[name='isactiveAll']"), function(){
                if($(this).val() == ''){
                    favorite1.push('0');
                }else{
                    favorite1.push($(this).val());
                }
            });
	        var ItemStatus_new = favorite1.join(",");
	        
	        ItemID = $(this).val();
        if(item_code == ''){
            alert('please enterItemID');
            $('#item_code1').focus();
        }else{
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/SaveItemID",
                dataType:"JSON",
                method:"POST",
                data:{item_code:item_code,description:description,crate_qty:crate_qty,case_qty:case_qty,bowl_qty:bowl_qty,min_qty:min_qty,
                    min_day:min_day,case_weight:case_weight,tax:tax,unit:unit,subgroup_id:subgroup_id,group_id:group_id,local_supply_in:local_supply_in,outst_supply_in:outst_supply_in,
                    monitorstock:monitorstock,hsn_code:hsn_code,rack_id:rack_id,subrack_id:subrack_id,isactive:isactive,OQty:OQty_new,ItemStatus_new:ItemStatus_new
                },
                beforeSend: function () {
                $('.searchh3').css('display','block');
                $('.searchh3').css('color','blue');
                },
                complete: function () {
                $('.searchh3').css('display','none');
                },
                success:function(data){
                   if(data == true){
                       alert_float('success', 'Record created successfully...');
                       $('#item_code1').val('');
                       $('#item_code').val('');
                        $('#description').val('');
                        $('#crate_qty').val('');
                        $('#case_qty').val('');
                        $('#bowl_qty').val('');
                        $('#min_qty').val('');
                        $('#case_weight').val('');
                        $('#min_day').val('');
                        $('input[name=OQTY]').val('');
                        
                        $('select[name=isactiveAll]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=tax]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=unit]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subgroup_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=group_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=local_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=outst_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=monitorstock]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=hsn_code]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=rack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subrack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=isactive]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });    
        }
            
        });
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            item_code = $('#item_code1').val();
            description = $('#description').val();
            crate_qty = $('#crate_qty').val();
            case_qty = $('#case_qty').val();
            bowl_qty = $('#bowl_qty').val();
            min_qty = $('#min_qty').val();
            case_weight = $('#case_weight').val();
            min_day = $('#min_day').val();
            tax = $('#tax').val();
            unit = $('#unit').val();
            subgroup_id = $('#subgroup_id').val();
            group_id = $('#group_id').val();
            local_supply_in = $('#local_supply_in').val();
            outst_supply_in = $('#outst_supply_in').val();
            monitorstock = $('#monitorstock').val();
            hsn_code = $('#hsn_code').val();
            rack_id = $('#rack_id').val();
            subrack_id = $('#subrack_id').val();
            isactive = $('#isactive').val();
            OQty = $('input[name=OQTY]').val();
            var OQty = '';
	        var favorite = [];
            $.each($("input[name='OQTY']"), function(){
                if($(this).val() == ''){
                    favorite.push('0');
                }else{
                    favorite.push($(this).val());
                }
            });
	        var OQty_new = favorite.join(",");
	        
	        ItemStatus = $('input[name=isactiveAll]').val();
            var ItemStatus = '';
	        var favorite1 = [];
            $.each($("select[name='isactiveAll']"), function(){
                if($(this).val() == ''){
                    favorite1.push('0');
                }else{
                    favorite1.push($(this).val());
                }
            });
	        var ItemStatus_new = favorite1.join(",");
	        
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/UpdateItemID",
                dataType:"JSON",
                method:"POST",
                data:{item_code:item_code,description:description,crate_qty:crate_qty,case_qty:case_qty,bowl_qty:bowl_qty,min_qty:min_qty,
                    min_day:min_day,case_weight:case_weight,tax:tax,unit:unit,subgroup_id:subgroup_id,group_id:group_id,local_supply_in:local_supply_in,outst_supply_in:outst_supply_in,
                    monitorstock:monitorstock,hsn_code:hsn_code,rack_id:rack_id,subrack_id:subrack_id,isactive:isactive,OQty:OQty_new,ItemStatus_new:ItemStatus_new
                },
                beforeSend: function () {
                $('.searchh3').css('display','block');
                $('.searchh3').css('color','blue');
                },
                complete: function () {
                $('.searchh3').css('display','none');
                },
                success:function(data){
                   if(data == true){
                       alert_float('success', 'Record updated successfully...');
                       $('#item_code1').val('');
                       $('#item_code').val('');
                        $('#description').val('');
                        $('#crate_qty').val('');
                        $('#case_qty').val('');
                        $('#bowl_qty').val('');
                        $('#min_qty').val('');
                        $('#case_weight').val('');
                        $('#min_day').val('');
                        $('input[name=OQTY]').val('');
                        
                        $('select[name=isactiveAll]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=tax]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=unit]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subgroup_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=group_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=local_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=outst_supply_in]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=monitorstock]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=hsn_code]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=rack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=subrack_id]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('select[name=isactive]').val('');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });
        });
    });
</script>

<script>
     function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_Item_List");
  tr = table.getElementsByTagName("tr");
   for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];
      td2 = tr[i].getElementsByTagName("td")[2];
      td3 = tr[i].getElementsByTagName("td")[3];
      td4 = tr[i].getElementsByTagName("td")[4];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td1){
         txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td2){
         txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td3){
         txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td4){
         txtValue = td4.textContent || td4.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else{
           tr[i].style.display = "none";
      } 
    }
    }
    }
    }     
  }
}
}
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

<script type="text/javascript">
   $('.OQTY').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3 )) {
        event.preventDefault();
    }
});
</script>
<style>

#item_code1 {
    text-transform: uppercase;
}
#table_Item_List td:hover {
    cursor: pointer;
}
#table_Item_List tr:hover {
    background-color: #ccc;
}

    .table-Item_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Item_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Item_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>