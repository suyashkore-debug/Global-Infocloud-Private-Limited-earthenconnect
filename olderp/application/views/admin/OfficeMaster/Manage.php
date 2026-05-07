<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
-->	
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="searchh2" style="display:none;">Please wait fetching data...</div>
                        <div class="searchh3" style="display:none;">Please wait Create new Office...</div>
                    </div>
                    <br>
                    <div class="col-md-3">
                        <?php echo render_input('AccountID','Office Code','','text'); ?>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('OfficeName','Office Name'); ?>
                    </div>
                    
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label" for="Address">Address</label>
                            <input type="text" id="Address" name="Address" class="form-control" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input('City','City'); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input('Latitude','Latitude'); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input('Longitude','Longitude'); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input('Redius','Redius(m)'); ?>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label">Is Active?</label>
                            <select class="selectpicker" name="IsActive" id="IsActive" data-width="100%" data-none-selected-text="-- Select --" data-live-search="false">
                                <option value="Y">Active</option> 
                                <option value="N">Deactive</option> 
                            </select>
                        </div>
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
                        
                        <button type="button" class="btn btn-success cancelBtn" style="margin-right: 25px;">Cancel</button>
                        <button type="button" class="btn btn-default  GetMyLocation" onclick="getMyLocation();"><i class="fa fa-location-arrow"></i> My Location</button>
                    </div>
                </div>
                
                <div class="clearfix"></div>
            <!-- Iteme List Model-->
            
                <div class="modal fade Office_List" id="Office_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Office List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Office_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-Office_List tableFixHead2" id="table_Office_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">Office Code</th>
                                            <th style="text-align:left;">Office Name</th>
                                            <th style="text-align:left;">City</th>
                                            <th style="text-align:left;">Location</th>
                                            <th style="text-align:left;">IsActive</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                            <td><?php echo $value['AccountID'];?></td>
                                            <td><?php echo $value['OfficeName'];?></td>
                                            <td><?php echo $value["City"];?></td>
                                            <td><?php echo $value["Address"];?></td>
                                            <td><?php echo $value["IsActive"];?></td>
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
<style>
    #AccountID {
    text-transform: uppercase;
}
</style>
<script type="text/javascript">
   $('#Latitude,#Longitude,#Redius').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
   /* var input = $("Redius").val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }*/
});
</script>
<script>
    /**
 * getLocation
 */
 function getMyLocation() {
  'use strict';
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showMyPosition);
  } else { 
    alert("Geolocation is not supported by this browser.");
  }
}
/**
 * show position
 */
function showMyPosition(position) {
  'use strict';
  $('input[name="Latitude"]').val(position.coords.latitude);
  $('input[name="Longitude"]').val(position.coords.longitude);
}
</script>
<script>
    $(document).ready(function(){
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#AccountID").dblclick(function(){
            $('#Office_List').modal('show');
            $('#Office_List').on('shown.bs.modal', function () {
              $('#myInput1').focus();
            })
        });
    // ItemID Typing Validation
        $("#AccountID").keypress(function (e) {
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
        $("#AccountID").focus(function(){
            $('#AccountID').val('');
            $('#OfficeName').val('');
            $('#Address').val('');
            $('#City').val('');
            $('#Latitude').val('');
            $('#Longitude').val('');
            $('#Redius').val('');
            $('select[name=IsActive]').val('Y');
            $('.selectpicker').selectpicker('refresh');
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            $('#AccountID').val('');
            $('#OfficeName').val('');
            $('#Address').val('');
            $('#City').val('');
            $('#Latitude').val('');
            $('#Longitude').val('');
            $('#Redius').val('');
            $('select[name=IsActive]').val('Y');
            $('.selectpicker').selectpicker('refresh');
                       
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
        });
        
    // On Blur AccountID Get All Date
        $('#AccountID').blur(function(){ 
            AccountID = $(this).val();
            if(AccountID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>OfficeMaster/GetOfficeDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID},
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
                        //alert('Office not found...')
                        //$('#AccountID').val('');
                        $('#OfficeName').val('');
                        $('#Address').val('');
                        $('#City').val('');
                        $('#Latitude').val('');
                        $('#Longitude').val('');
                        $('#Redius').val('');
                        $('select[name=IsActive]').val('Y');
                        $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else{
                        $('#AccountID').val(data.AccountID);
                        $('#OfficeName').val(data.OfficeName);
                        $('#Address').val(data.Address);
                        $('#City').val(data.City);
                        $('#Latitude').val(data.Latitude);
                        $('#Longitude').val(data.Longitude);
                        $('#Redius').val(data.Redius);
                       
                       $('select[name=IsActive]').val(data.IsActive);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                    } 
                }
            });
            }
            
        });
        
        $('.get_AccountID').on('click',function(){ 
            AccountID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>OfficeMaster/GetOfficeDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                    $('#AccountID').val(data.AccountID);
                    $('#OfficeName').val(data.OfficeName);
                    $('#Address').val(data.Address);
                    $('#City').val(data.City);
                    $('#Latitude').val(data.Latitude);
                    $('#Longitude').val(data.Longitude);
                    $('#Redius').val(data.Redius);
                       
                    $('select[name=IsActive]').val(data.IsActive);
                    $('.selectpicker').selectpicker('refresh');
                       
                    $('.saveBtn').hide();
                    $('.updateBtn').show();
                    $('.saveBtn2').hide();
                    $('.updateBtn2').show();
                }
            });
            $('#Office_List').modal('hide');
        });
        
    // Save New Item
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            OfficeName = $('#OfficeName').val();
            Address = $('#Address').val();
            City = $('#City').val();
            Latitude = $('#Latitude').val();
            Longitude = $('#Longitude').val();
            Redius = $('#Redius').val();
            IsActive = $('#IsActive').val();
        if(AccountID == ''){
            alert('please enter AccountID');
            $('#AccountID').focus();
        }else{
            $.ajax({
                url:"<?php echo admin_url(); ?>OfficeMaster/SaveAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,OfficeName:OfficeName,Address:Address,City:City,Latitude:Latitude,Longitude:Longitude,Redius:Redius,IsActive:IsActive
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
                        $('#AccountID').val('');
                        $('#OfficeName').val('');
                        $('#Address').val('');
                        $('#City').val('');
                        $('#Latitude').val('');
                        $('#Longitude').val('');
                        $('#Redius').val('');
                        $('select[name=IsActive]').val('Y');
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
            AccountID = $('#AccountID').val();
            OfficeName = $('#OfficeName').val();
            Address = $('#Address').val();
            City = $('#City').val();
            Latitude = $('#Latitude').val();
            Longitude = $('#Longitude').val();
            Redius = $('#Redius').val();
            IsActive = $('#IsActive').val();
	        
            $.ajax({
                url:"<?php echo admin_url(); ?>OfficeMaster/UpdateAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,OfficeName:OfficeName,Address:Address,City:City,Latitude:Latitude,Longitude:Longitude,Redius:Redius,IsActive:IsActive
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
                       $('#AccountID').val('');
                        $('#OfficeName').val('');
                        $('#Address').val('');
                        $('#City').val('');
                        $('#Latitude').val('');
                        $('#Longitude').val('');
                        $('#Redius').val('');
                        $('select[name=IsActive]').val('Y');
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
  table = document.getElementById("table_Office_List");
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
#table_Office_List td:hover {
    cursor: pointer;
}
#table_Office_List tr:hover {
    background-color: #ccc;
}

    .table-Office_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Office_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Office_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>