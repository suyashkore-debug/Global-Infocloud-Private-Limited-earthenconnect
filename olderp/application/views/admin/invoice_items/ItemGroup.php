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
                        <div class="searchh3" style="display:none;">Please wait Create new ItemIDGroup...</div>
                        <div class="searchh4" style="display:none;">Please wait update ItemIDGroup...</div>
                    </div>
                    <br>
                    <div class="col-md-2">
                        <?php
                            $nextItemGroupID = $lastId + 1;
                        ?>
                        <?php //echo render_input('ItemGroupID','MainItemGroup ID',$nextItemGroupID,'text'); ?>
                        <div class="form-group" app-field-wrapper="ItemGroupID">
                            <label for="ItemGroupID" class="control-label">ItemGroup ID</label>
                            <input type="text" id="ItemGroupID" name="ItemGroupID" class="form-control" value="<?= $nextItemGroupID;?>" onkeypress="return isNumber(event)">
                        </div>
                        
                        <input type="hidden" id="NextItemGroupID" name="NextItemGroupID" class="form-control" value="<?php echo $nextItemGroupID; ?>">
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('ItemGroupName','ItemGroup Name'); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">MainItem Group</label>
                            <select class="selectpicker" name="MainItemGroup" id="MainItemGroup" data-width="100%" data-none-selected-text="-- Select --" data-live-search="true">
                            <?php
                            foreach ($MainItemGroup as $key => $value) {
                            ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></option>   
                            <?php   
                                }
                            ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="clearfix"></div>
                    <br><br>
                    <div class="col-md-12">
                        <?php if (has_permission('itemsmaingrp', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission('itemsmaingrp', '', 'edit')) {
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
            
                <div class="modal fade ItemGroup_List" id="ItemGroup_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">ItemGroup List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-ItemGroup_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-ItemGroup_List tableFixHead2" id="table_ItemGroup_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">ItemGroup ID <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>
                                            <th style="text-align:left;">ItemGroup Name</th>
                                            <th style="text-align:left;">MainItemGroup Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_ItemGroup" data-id="<?php echo $value["id"]; ?>">
                                            <td><?php echo $value['id'];?></td>
                                            <td><?php echo $value['name'];?></td>
                                            <td><?php echo $value['MainGroupName'];?></td>
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
        $("#ItemGroupID").dblclick(function(){
            $('#ItemGroup_List').modal('show');
            $('#ItemGroup_List').on('shown.bs.modal', function () {
              $('#myInput1').focus();
            })
        });
    // ItemID Typing Validation
        /*$("#item_code1").keypress(function (e) {
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
        });*/
        
    // Empty and open create mode
        $("#ItemGroupID").focus(function(){
            var NextItemGroupID = $('#NextItemGroupID').val();
            $('#ItemGroupID').val(NextItemGroupID);
            $('#ItemGroupName').val('');
            $('select[name=MainItemGroup]').val(1);
            $('.selectpicker').selectpicker('refresh');           
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            var NextItemGroupID = $('#NextItemGroupID').val();
            $('#ItemGroupID').val(NextItemGroupID);
            $('#ItemGroupName').val('');
            $('select[name=MainItemGroup]').val(1);
            $('.selectpicker').selectpicker('refresh');   
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // On Blur ItemID Get All Date
        $('#ItemGroupID').blur(function(){ 
            ItemGroupID = $(this).val();
            if(ItemGroupID == ''){
                var NextItemGroupID = $('#NextItemGroupID').val();
                $('#ItemGroupID').val(NextItemGroupID);
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/GetItemGroupDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{ItemGroupID:ItemGroupID},
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
                        var NextItemGroupID = $('#NextItemGroupID').val();
                        $('#ItemGroupID').val(NextItemGroupID);
                        $('#ItemGroupName').val('');
                        $('select[name=MainItemGroup]').val(1);
                        $('.selectpicker').selectpicker('refresh');              
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else{
                       $('select[name=MainItemGroup]').val(data.main_group_id);
                        $('.selectpicker').selectpicker('refresh');   
                       $('#ItemGroupName').val(data.name);
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                    } 
                }
            });
            }
            
        });
        
        $('.get_ItemGroup').on('click',function(){ 
            ItemGroupID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/GetItemGroupDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{ItemGroupID:ItemGroupID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                       $('#ItemGroupID').val(data.id);
                       $('#ItemGroupName').val(data.name);
                       
                       $('select[name=MainItemGroup]').val(data.main_group_id);
                       $('.selectpicker').selectpicker('refresh');
            
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                }
            });
            $('#ItemGroup_List').modal('hide');
        });
        
    // Save New MainItemGroup
        $('.saveBtn').on('click',function(){ 
            ItemGroupID = $('#ItemGroupID').val();
            ItemGroupName = $('#ItemGroupName').val();
            MainItemGroupName = $('#MainItemGroup').val();
            
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/SaveItemGroup",
                dataType:"JSON",
                method:"POST",
                data:{ItemGroupID:ItemGroupID,ItemGroupName:ItemGroupName,MainItemGroupName:MainItemGroupName
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
                       var NextItemGroupID = $('#NextItemGroupID').val();
                       var newGroupID = parseInt(NextItemGroupID) + 1;
                        $('#ItemGroupID').val(newGroupID);
                        $('#NextItemGroupID').val(newGroupID);
                        $('select[name=MainItemGroup]').val(1);
                       $('.selectpicker').selectpicker('refresh');
                        $('#ItemGroupName').val('');
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
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            ItemGroupID = $('#ItemGroupID').val();
            ItemGroupName = $('#ItemGroupName').val();
            MainItemGroup = $('#MainItemGroup').val();
            
            $.ajax({
                url:"<?php echo admin_url(); ?>invoice_items/UpdateItemGroup",
                dataType:"JSON",
                method:"POST",
                data:{ItemGroupID:ItemGroupID,ItemGroupName:ItemGroupName,MainItemGroup:MainItemGroup
                },
                beforeSend: function () {
                $('.searchh4').css('display','block');
                $('.searchh4').css('color','blue');
                },
                complete: function () {
                $('.searchh4').css('display','none');
                },
                success:function(data){
                   if(data == true){
                       alert_float('success', 'Record updated successfully...');
                       var NextItemGroupID = $('#NextItemGroupID').val();
                        $('#ItemGroupID').val(NextItemGroupID);
                       $('#ItemGroupName').val('');
                       $('select[name=MainItemGroup]').val(1);
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
  table = document.getElementById("table_ItemGroup_List");
  tr = table.getElementsByTagName("tr");
   for (i = 1; i < tr.length; i++) {
      td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];
      td2 = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td1){
         txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td2){
         txtValue = td2.textContent || td2.innerText;
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
<style>

#item_code1 {
    text-transform: uppercase;
}
#table_ItemGroup_List td:hover {
    cursor: pointer;
}
#table_ItemGroup_List tr:hover {
    background-color: #ccc;
}

    .table-ItemGroup_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-ItemGroup_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-ItemGroup_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>