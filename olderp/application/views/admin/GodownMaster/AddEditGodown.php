<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="panel_s">
          <div class="panel-body">
              <?php //echo form_open('admin/accounts_master/manage_account_group',array('id'=>'account_group_form')); ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="searchh" style="display:none;">Please wait Exporting data...</div>
                        <div class="searchh2" style="display:none;">Please wait fetching data...</div>
                        <div class="searchh3" style="display:none;">Please wait Create new Godown...</div>
                        <div class="searchh4" style="display:none;">Please wait update Godown...</div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="AccountID">AccountID</label>
                            <input type="text" name="AccountID" id="AccountID" class="form-control" value="">
                                        
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="AccountName">Name</label>
                        <input type="text" name="AccountName" id="AccountName" class="form-control" value="">
                        <input type="hidden" name="form_mode" id="form_mode" value="add">
                        </div>
                    </div>
                    
                </div>
                
                
            <div class="row"> 
                <div class="col-md-12">
                        <?php if (has_permission('account_groups', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission('account_groups', '', 'edit')) {
                        ?>
                        <button type="button" class="btn btn-info updateBtn" style="margin-right: 25px;">Update</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info updateBtn2 disabled" style="margin-right: 25px;">Update</button>
                        <?php
                        }?>
                        
                        <button type="button" class="btn btn-default cancelBtn" style="margin-right: 25px;">Cancel</button>
                        <?php
                            $staffID = $this->session->userdata('staff_user_id');
                            if($staffID == '3'){
                                ?>
                                <button type="button" class="btn btn-danger DeleteBtn" style="margin-right: 25px;">Delete</button>
                            <?php
                            }
                        ?>
                        
                        
                        <button class="btn btn-default  " href="javascript:void(0);"    onclick="printPage();" style="margin-right: 25px;">Print</button>
                        <button class="btn btn-default  " href="#" id="caexcel">Export</button>
                    
                    </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-GodownList tableFixHead">
                        <table class="tree table table-striped table-bordered table-GodownList tableFixHead" id="table_GodownList1" width="100%">
                            <thead>
                                <tr style="display:none;">
                                    <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                </tr>
                                <tr>
                                    <th id="sl" style="text-align:left;">AccountID </th>
                                    <th style="text-align:left;">Name</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyid">
                            <?php
                                foreach ($TableData as $key => $value) {
                            ?>
                                <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                    <td><?php echo $value["AccountID"];?></td>
                                    <td><?php echo $value["AccountName"];?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>   
                    </div>
                </div>
            </div>
        
            <div class="clearfix"></div>
            
            <div class="modal fade GodownList" id="GodownList" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">GodownList <?php echo $staffID;?></h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-GodownList tableFixHead2">
                                <table class="tree table table-striped table-bordered table-GodownList tableFixHead2" id="table_GodownList" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">AccountID </th>
                                            <th style="text-align:left;">Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($TableData as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                            <td><?php echo $value["AccountID"];?></td>
                                            <td><?php echo $value["AccountName"];?></td>
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
<!--new update -->


<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    $("#caexcel").click(function(){
	  act = '1';
        $.ajax({
            url:"<?php echo admin_url(); ?>GodownMaster/exportGodown",
            method:"POST",
            data:{act:act},
            beforeSend: function () {
                $('#searchh').css('display','block');
            },
            complete: function () {
                $('#searchh').css('display','none');    
            },
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
            }
        });
    });
    
    $("#AccountID").dblclick(function(){
        $('#GodownList').modal('show');
        $('#GodownList').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
    });
    $('.updateBtn').hide();
    $('.updateBtn2').hide();
    $('.DeleteBtn').hide();
    
// Focus on GroupID
    $('#AccountID').on('focus',function(){
        $('#AccountID').val('');
        $('#AccountName').val('');
        $('.saveBtn').show();
        $('.saveBtn2').show();
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $('.DeleteBtn').hide();
    });

// Cancel selected data
    $(".cancelBtn").click(function(){
        $('#AccountID').val('');
        $('#AccountName').val('');
        $('.saveBtn').show();
        $('.saveBtn2').show();
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $('.DeleteBtn').hide();
    });
    
// Get Group Detail by Group ID
    $('#AccountID').on('blur',function(){
        var AccountID = $(this).val();
        if(AccountID == ""){
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            $('.DeleteBtn').hide();
            $('#AccountName').val('');
        }else{
            $.ajax({
                  url:"<?php echo admin_url(); ?>GodownMaster/GetAccountDetails",
                  dataType:"JSON",
                  method:"POST",
                  cache: false,
                  data:{AccountID:AccountID,},
                  beforeSend: function () {
                    $('#searchh2').css('display','block');
                    },
                    complete: function () {
                        $('#searchh2').css('display','none');    
                    },
                  success:function(data){
                    if(empty(data)){
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.updateBtn2').hide();
                        $('.DeleteBtn').hide();
                        $('#AccountName').val('');
                    }else{
                        $('#AccountName').val(data.AccountName);
                        $('.saveBtn').hide();
                        $('.updateBtn').show();
                        $('.DeleteBtn').show();
                        $('.saveBtn2').hide();
                        $('.updateBtn2').show();
                    }
                  }
            });
        }
     })
     
// Initialize For GroupID
    $( "#AccountID" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url: "<?=base_url()?>admin/GodownMaster/getAccountSerch",
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
          $('#AccountID').val(ui.item.value); // display the selected text
          $('#AccountName').val(ui.item.label); // display the selected text
          $('.saveBtn').hide();
          $('.updateBtn').show();
          $('.DeleteBtn').show();
          $('.saveBtn2').hide();
          $('.updateBtn2').show();
          $('#AccountName').focus();
            return false;  
        }
    });
    
    
    $('.get_AccountID').on('click',function(){ 
            AccountID = $(this).attr("data-id");
            $.ajax({
                  url:"<?php echo admin_url(); ?>GodownMaster/GetAccountDetails",
                  dataType:"JSON",
                  method:"POST",
                  cache: false,
                  data:{AccountID:AccountID,},
                  beforeSend: function () {
                    $('#searchh2').css('display','block');
                    },
                    complete: function () {
                        $('#searchh2').css('display','none');    
                    },
                  success:function(data){
                    if(empty(data)){
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.DeleteBtn').hide();
                        $('.updateBtn2').hide();
                        $('#AccountID').val('');
                        $('#AccountName').val('');
                    }else{
                        $('#AccountID').val(data.AccountID);
                        $('#AccountName').val(data.AccountName);
                        $('.saveBtn').hide();
                        $('.updateBtn').show();
                        $('.DeleteBtn').show();
                        $('.saveBtn2').hide();
                        $('.updateBtn2').show();
                    }
                  }
            });
            $('#GodownList').modal('hide');
        });
    
    // Save New Account
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            AccountName = $('#AccountName').val();
            $.ajax({
                url:"<?php echo admin_url(); ?>GodownMaster/SaveAccount",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccountName:AccountName
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
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.updateBtn2').hide();
                        $('.DeleteBtn').hide();
                        $('#AccountID').val('');
                        $('#AccountName').val('');
                        reloadtable();
                   }else{
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });
        }); 
        function reloadtable(){
            AccountID ='11';
            $.ajax({
                url:"<?php echo admin_url(); ?>GodownMaster/GetList",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID
                },
               /* beforeSend: function () {
                $('.searchh4').css('display','block');
                $('.searchh4').css('color','blue');
                },
                complete: function () {
                $('.searchh4').css('display','none');
                },*/
                success:function(data){
                    $('#tbodyid').html('')
                    $('#tbodyid').html(data);
                }
            });
        }
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            AccountName = $('#AccountName').val();
            
            $.ajax({
                url:"<?php echo admin_url(); ?>GodownMaster/UpdateAccount",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,AccountName:AccountName
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
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.updateBtn2').hide();
                        $('.DeleteBtn').hide();
                        $('#AccountID').val('');
                        $('#AccountName').val('');
                        reloadtable();      
                    }else{
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });
        });
    
    // Delete Account
        $('.DeleteBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            $.ajax({
                url:"<?php echo admin_url(); ?>GodownMaster/DeleteAccount",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID
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
                       alert_float('success', 'Record Deleted successfully...');
                        $('.saveBtn').show();
                        $('.saveBtn2').show();
                        $('.updateBtn').hide();
                        $('.updateBtn2').hide();
                        $('.DeleteBtn').hide();
                        $('#AccountID').val('');
                        $('#AccountName').val('');
                        reloadtable();      
                   }else{
                       alert_float('warning', data);
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
  table = document.getElementById("table_GodownList");
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
      }else{
           tr[i].style.display = "none";
      } 
    }
    }
    }    
  }
}
}
 </script>

<style>
    #table_GodownList td:hover {
    cursor: pointer;
}
#AccountID{
    text-transform: uppercase;
}
#table_GodownList tr:hover {
    background-color: #ccc;
}

    .table-GodownList          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-GodownList thead th { position: sticky; top: 0; z-index: 1; }
    .table-GodownList tbody th { position: sticky; left: 0; }
    .table-GodownList1          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-GodownList1 thead th { position: sticky; top: 0; z-index: 1; }
    .table-GodownList1 tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>

 <style type="text/css">
   body{
    overflow: hidden;
   }
 </style>

