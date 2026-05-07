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
                        <div class="searchh3" style="display:none;">Please wait Create new Vendor...</div>
                        <div class="searchh4" style="display:none;">Please wait update Vendor...</div>
                    </div>
                    <br>
                    <div class="col-md-2">
                        <?php
                            $nextItemGroupID = $lastId + 1;
                        ?>
                        
                        <div class="form-group" app-field-wrapper="ItemGroupID">
                            <small class="req text-danger">* </small>
                            <label for="AccountID" class="control-label">AccountID</label>
                            <input type="text" id="AccountID" name="AccountID" class="form-control" value="">
                            <?php $selected_company = $this->session->userdata('root_company');
                                  $UserID = $this->session->userdata('username');
                            ?>
                            <input type="hidden" id="PlantID" name="PlantID" class="form-control" value="<?php echo $selected_company;?>">
                            <input type="hidden" id="UserID" name="UserID" class="form-control" value="<?php echo $UserID;?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <?php echo render_input('AccountName','Account Name'); ?>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="Blockyn">GST Type</label>
                            <select name="gst_type" id="gst_type" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                                <option value="1" >Registered</option>
                                <option value="2">Un-registered</option>
                                <option value="3" >Composition</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php 
                            echo render_select( 'account_group',$SubGroup,array( 'SubActGroupID',array( 'SubActGroupName')), 'Account Group','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label for="state" class="control-label">state</label>
                            <select class="selectpicker display-block" data-width="100%" id="state" name="state" data-none-selected-text="<?php echo 'Select State'; ?>" data-live-search="true">
                                <option value=""></option>
                                <?php foreach($state as $st){ ?>
                                <option value="<?php echo $st['short_name']; ?>" ><?php echo $st['state_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label for="city" class="control-label">City</label>
                           <select class="selectpicker display-block" data-width="100%" id="city" name="city" data-none-selected-text="<?php echo 'Select City'; ?>" data-live-search="true">
                                <option value=""></option>    
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input( 'address', 'Address'); ?>
                    </div>
                    <div class="col-md-3">
                        <?php echo render_input( 'address2', 'Address2'); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="zip">
                            <label for="zip" class="control-label">Pin Code</label>
                            <input type="text"  name="zip" id= "zip" class="form-control" value="" maxlength="6" minlength="6" onkeypress="return isNumber(event)">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php //echo render_input('phonenumber','MobileNo'); ?>
                        <div class="form-group" app-field-wrapper="phonenumber">
							<label for="phonenumber" class="control-label">Mobile No</label>
							<small class="req text-danger">* </small>
							<input type="text" maxlength="10" pattern="[6789][0-9]{9}" id="phonenumber" name="phonenumber" class="form-control" autocomplete="off" value="" onkeypress="return isNumber(event)">
							<span class="mob_denger" style="color:red;"></span>
						</div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php //echo render_input('phonenumber','MobileNo'); ?>
                        <div class="form-group" app-field-wrapper="altphonenumber">
							<label for="altphonenumber" class="control-label">Alt Mobile No.</label>
							<input type="text" maxlength="10" pattern="[6789][0-9]{9}" id="altphonenumber" name="altphonenumber" class="form-control" autocomplete="off" value="" onkeypress="return isNumber(event)">
							<span class="mob_denger" style="color:red;"></span>
						</div>
                    </div>
                    
                    <div class="col-md-3">
                        <?php echo render_input('email','EmailID'); ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="vat">
                            <label for="vat" class="control-label">GST Number</label>
                            <input type="text" id="vat" name="vat" class="form-control" 
                            pattern="([0-9]){2}([A-Za-z]){5}([0-9]){4}([A-Za-z]){1}([0-9]{1})([0-9A-Za-z]){2}" maxlength="15" minlength="15" value="">
                            <span class="gst_denger" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">PAN</label>
                            <input type="text" name="pan" maxlength="10" minlength="10" pattern="[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}" id="pan" value="<?php echo html_entity_decode($client->Pan); ?>" class="form-control">
                            <span class="pan_denger" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" app-field-wrapper="adhaar">
                            <label for="adhaar">AADHAAR</label>
                            <input type="text" name="adhaar" maxlength="12" minlength="12" pattern="[0-9] {10}" id="adhaar" onkeypress="return isNumber(event)" value="" class="form-control" onkeypress="return isNumber(event)">
                            <span class="aadhar_denger" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Food Lic No</label>
                            <input type="text" name="food_lic_n" id="food_lic_n" maxlength="14" minlength="14" onkeypress="return isNumber(event)" value="" class="form-control">
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">Station Name</label>
                            <input type="text" name="StationName" id="StationName" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                <?php
                    if(isset( $client) && !is_admin()){
                        $ss = "disabled";
                    } ?>
                    <?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
                    <div class="form-group">
                        <label for="">Opening Balance</label>
                        <input type="text" name="opening_b" id="opening_b" value="" class="form-control" <?php if(isset($client) && $staff_user_id !== "3"){ echo "disabled";}?>>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                      <label for="active">status</label>
                      <select name="active" id="active" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true">
                            <option value="1" >Yes</option>
                            <option value="0" >No</option>
                      </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <?php
                        if(isset( $client) && is_admin()){
                        }else{
                            $attr_date = array('disabled'=>true);
                        }
                    ?>
                    <?php $value=  _d(date('Y-m-d')); ?>
                    <?php echo render_date_input( 'StartDate', 'Start Date',$value,'text',$attr_date); ?>
                </div>
                    
                    
                    <div class="clearfix"></div>
                    <br><br>
                    <div class="col-md-12">
                        <?php if (has_permission('vendors', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission('vendors', '', 'edit')) {
                        ?>
                        <button type="button" class="btn btn-info updateBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Update</button>
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
            
                <div class="modal fade Vendor_List" id="Vendor_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Vendor List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Vendor_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-Vendor_List tableFixHead2" id="table_Vendor_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">AccountID</th>
                                            <th style="text-align:left;">Account Name</th>
                                            <th style="text-align:left;">Station</th>
                                            <th style="text-align:left;">State</th>
                                            <th style="text-align:left;">City</th>
                                            <th style="text-align:left;">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($table_data as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                             <td><?php echo $value['AccountID'];?></td>
                                            <td><?php echo $value['company'];?></td>
                                            <td><?php echo $value['StationName'];?></td>
                                            <td><?php echo $value['state'];?></td>
                                            <td><?php echo $value['city'];?></td>
                                            <td><?php 
                                            if($value['active'] == '1'){
                                                $status = 'Active';
                                            }else{
                                                $status = 'Inactive';
                                            }
                                            echo $status;?></td>
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
        var SessionID = "<?php echo $this->session->userdata('AccountIDSet');?>";
        if(SessionID !== ""){
            $.ajax({
                url:"<?php echo admin_url(); ?>purchase/GetAccountID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:SessionID},
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
                        
                       // $('#AccountID').val('');
                       $('#AccountName').val('');
                       $('#address').val('');
                       $('#address2').val('');
                       $('#zip').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#pan').val('');
                       $('#adhaar').val('');
                       $('#food_lic_n').val('');
                       $('#StationName').val('');
                       $('#opening_b').val('');
                       $('#opening_b').removeAttr('disabled');
                       $('.saveBtn').removeAttr('disabled');
                      var today = new Date();
                       var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                       $('#StartDate').val(date);
                       
                       $('select[name=gst_type]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=account_group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=active]').val('1');
                       $('.selectpicker').selectpicker('refresh');              
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else{
                        if(data.AccountType == 'Staff'){
                            $('.saveBtn').removeAttr('disabled');
                            alert('This AccountID Use for Staff');
                            $('#AccountID').focus();
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                        }else{
                            var PlantID = $('#PlantID').val();
                            if(PlantID == data.PlantID){
                                if(data.SubActGroupID == '50003002' || data.SubActGroupID == '50003008' || data.SubActGroupID == '50003009'){
                                    $('#AccountID').val(data.AccountID);
                                   $('#AccountName').val(data.company);
                                   $('#address').val(data.address);
                                   $('#address2').val(data.Address3);
                                   $('#zip').val(data.zip);
                                   $('#phonenumber').val(data.phonenumber);
                                   $('#altphonenumber').val(data.altphonenumber);
                                   $('#email').val(data.email);
                                   $('#vat').val(data.vat);
                                   $('#pan').val(data.Pan);
                                   $('#adhaar').val(data.Aadhaarno);
                                   $('#food_lic_n').val(data.FLNO1);
                                   $('#StationName').val(data.StationName);
                                   $('#opening_b').val(data.BAL1);
                                   var UserID = $('#UserID').val();
                                   if(UserID !== "admin"){
                                    $('#opening_b').attr('disabled','disabled');    
                                   }
                                   StartDate = data.StartDate.substr(0, 10);
                                   StartDate = StartDate.split("-").reverse().join("/");
                                   $('#StartDate').val(StartDate);
                                   
                                   $('select[name=gst_type]').val(data.gsttype);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=account_group]').val(data.SubActGroupID);
                                   $('.selectpicker').selectpicker('refresh');
                                   $('select[name=state]').val(data.state);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $("#city").children().remove();
                                   var sel = '';
                                   for (i = 0; i < data.cityList.length; i++) {
                                        $('#city').append('<option value="' + data.cityList[i]['id'] + '" '+sel+'>' + data.cityList[i]['city_name'] + '</option>');        
                                    }
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=city]').val(data.city);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=active]').val(data.active);
                                   $('.selectpicker').selectpicker('refresh');
                                   $('.saveBtn').hide();
                                   $('.updateBtn').show();
                                   $('.saveBtn2').hide();
                                   $('.updateBtn2').show();
                                }else{
                                    $('.saveBtn').removeAttr('disabled');
                                    alert('This AccountID already Use...');
                                    $('#AccountID').focus();
                                    $('.saveBtn').show();
                                    $('.updateBtn').hide();
                                    $('.saveBtn2').show();
                                    $('.updateBtn2').hide();
                                }
                               
                            }else{
                                $('.saveBtn').removeAttr('disabled');
                                alert('This AccountID Use for Other Plant');
                                $('#AccountID').focus();
                                $('.saveBtn').show();
                                $('.updateBtn').hide();
                                $('.saveBtn2').show();
                                $('.updateBtn2').hide();
                            }
                        }
                    } 
                }
            });
        }
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
        $("#AccountID").dblclick(function(){
            $('#Vendor_List').modal('show');
            $('#Vendor_List').on('shown.bs.modal', function () {
                $('#myInput1').val('');
                $('#myInput1').focus();
            })
        });
   $('#state').on('change', function() {
			var id = $(this).val();
			var url = "<?php echo admin_url(); ?>purchase/GetCityListByStateID";
                jQuery.ajax({
                    type: 'POST',
                    url:url,
                    data: {id: id},
                    dataType:'json',
                    success: function(data) {
                        $("#city").children().remove();
                        $.each(data, function (index, value) {
                            // APPEND OR INSERT DATA TO SELECT ELEMENT.
                            $('#city').append('<option value="' + value.id + '">' + value.city_name + '</option>');
                        });
                        $("#city").selectpicker("refresh");
                    }
                });
		});
        
    // Empty and open create mode
        $("#AccountID").focus(function(){
                $('#AccountID').val('');
                $('#AccountName').val('');
                $('#address').val('');
                $('#address2').val('');
                $('#zip').val('');
                $('#phonenumber').val('');
                $('#altphonenumber').val('');
                $('#email').val('');
                $('#vat').val('');
                $('#pan').val('');
                $('#adhaar').val('');
                $('#food_lic_n').val('');
                $('#StationName').val('');
                $('#opening_b').val('');
                $('#opening_b').removeAttr('disabled');
                $('.saveBtn').removeAttr('disabled');
            var today = new Date();
                var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                $('#StartDate').val(date);
                       
                $('select[name=gst_type]').val('1');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=account_group]').val('');
                $('.selectpicker').selectpicker('refresh');
                $('select[name=state]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $("#city").children().remove();
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=active]').val('1');
                $('.selectpicker').selectpicker('refresh');           
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
                $('#AccountID').val('');
                $('#AccountName').val('');
                $('#address').val('');
                $('#address2').val('');
                $('#zip').val('');
                $('#phonenumber').val('');
                $('#altphonenumber').val('');
                $('#email').val('');
                $('#vat').val('');
                $('#pan').val('');
                $('#adhaar').val('');
                $('#food_lic_n').val('');
                $('#StationName').val('');
                $('#opening_b').val('');
                $('#opening_b').removeAttr('disabled');
                $('.saveBtn').removeAttr('disabled');
            var today = new Date();
                var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                $('#StartDate').val(date);
                       
                $('select[name=gst_type]').val('1');
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=account_group]').val('');
                $('.selectpicker').selectpicker('refresh');
                $('select[name=state]').val('');
                $('.selectpicker').selectpicker('refresh');
                       
                $("#city").children().remove();
                $('.selectpicker').selectpicker('refresh');
                       
                $('select[name=active]').val('1');
                $('.selectpicker').selectpicker('refresh');   
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // On Blur ItemID Get All Date
        $('#AccountID').blur(function(){ 
            AccountID = $(this).val();
            if(AccountID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>purchase/GetAccountID",
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
                        
                       // $('#AccountID').val('');
                       $('#AccountName').val('');
                       $('#address').val('');
                       $('#address2').val('');
                       $('#zip').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#pan').val('');
                       $('#adhaar').val('');
                       $('#food_lic_n').val('');
                       $('#StationName').val('');
                       $('#opening_b').val('');
                       $('#opening_b').removeAttr('disabled');
                       $('.saveBtn').removeAttr('disabled');
                      var today = new Date();
                       var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                       $('#StartDate').val(date);
                       
                       $('select[name=gst_type]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=account_group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=active]').val('1');
                       $('.selectpicker').selectpicker('refresh');              
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                    }else{
                        if(data.AccountType == 'Staff'){
                            $('.saveBtn').removeAttr('disabled');
                            alert('This AccountID Use for Staff');
                            $('#AccountID').focus();
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                        }else{
                            var PlantID = $('#PlantID').val();
                            if(PlantID == data.PlantID){
                                if(data.SubActGroupID == '50003002' || data.SubActGroupID == '50003008' || data.SubActGroupID == '50003009'){
                                    $('#AccountID').val(data.AccountID);
                                   $('#AccountName').val(data.company);
                                   $('#address').val(data.address);
                                   $('#address2').val(data.Address3);
                                   $('#zip').val(data.zip);
                                   $('#phonenumber').val(data.phonenumber);
                                   $('#altphonenumber').val(data.altphonenumber);
                                   $('#email').val(data.email);
                                   $('#vat').val(data.vat);
                                   $('#pan').val(data.Pan);
                                   $('#adhaar').val(data.Aadhaarno);
                                   $('#food_lic_n').val(data.FLNO1);
                                   $('#StationName').val(data.StationName);
                                   $('#opening_b').val(data.BAL1);
                                   var UserID = $('#UserID').val();
                                   if(UserID !== "admin"){
                                    $('#opening_b').attr('disabled','disabled');    
                                   }
                                   StartDate = data.StartDate.substr(0, 10);
                                   StartDate = StartDate.split("-").reverse().join("/");
                                   $('#StartDate').val(StartDate);
                                   
                                   $('select[name=gst_type]').val(data.gsttype);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=account_group]').val(data.SubActGroupID);
                                   $('.selectpicker').selectpicker('refresh');
                                   $('select[name=state]').val(data.state);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $("#city").children().remove();
                                   var sel = '';
                                   for (i = 0; i < data.cityList.length; i++) {
                                        $('#city').append('<option value="' + data.cityList[i]['id'] + '" '+sel+'>' + data.cityList[i]['city_name'] + '</option>');        
                                    }
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=city]').val(data.city);
                                   $('.selectpicker').selectpicker('refresh');
                                   
                                   $('select[name=active]').val(data.active);
                                   $('.selectpicker').selectpicker('refresh');
                                   $('.saveBtn').hide();
                                   $('.updateBtn').show();
                                   $('.saveBtn2').hide();
                                   $('.updateBtn2').show();
                                }else{
                                    $('.saveBtn').removeAttr('disabled');
                                    alert('This AccountID already Use...');
                                    $('#AccountID').focus();
                                    $('.saveBtn').show();
                                    $('.updateBtn').hide();
                                    $('.saveBtn2').show();
                                    $('.updateBtn2').hide();
                                }
                               
                            }else{
                                $('.saveBtn').removeAttr('disabled');
                                alert('This AccountID Use for Other Plant');
                                $('#AccountID').focus();
                                $('.saveBtn').show();
                                $('.updateBtn').hide();
                                $('.saveBtn2').show();
                                $('.updateBtn2').hide();
                            }
                        }
                    } 
                }
            });
            }
            
        });
        
        $('.get_AccountID').on('click',function(){ 
            AccountID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>purchase/GetAccountID",
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
                       $('#AccountName').val(data.company);
                       $('#address').val(data.address);
                       $('#address2').val(data.Address3);
                       $('#zip').val(data.zip);
                       $('#phonenumber').val(data.phonenumber);
                       $('#altphonenumber').val(data.altphonenumber);
                       $('#email').val(data.email);
                       $('#vat').val(data.vat);
                       $('#pan').val(data.Pan);
                       $('#adhaar').val(data.Aadhaarno);
                       $('#food_lic_n').val(data.FLNO1);
                       $('#StationName').val(data.StationName);
                       $('#opening_b').val(data.BAL1);
                       var UserID = $('#UserID').val();
                       if(UserID !== "admin"){
                        $('#opening_b').attr('disabled','disabled');    
                       }
                       StartDate = data.StartDate.substr(0, 10);
                       StartDate = StartDate.split("-").reverse().join("/");
                       $('#StartDate').val(StartDate);
                       
                       $('select[name=gst_type]').val(data.gsttype);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=account_group]').val(data.SubActGroupID);
                       $('.selectpicker').selectpicker('refresh');
                       $('select[name=state]').val(data.state);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       var sel = '';
                       for (i = 0; i < data.cityList.length; i++) {
                            $('#city').append('<option value="' + data.cityList[i]['id'] + '" '+sel+'>' + data.cityList[i]['city_name'] + '</option>');        
                        }
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=city]').val(data.city);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=active]').val(data.active);
                       $('.selectpicker').selectpicker('refresh');
            
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                }
            });
            $('#Vendor_List').modal('hide');
        });
        
    // Save New New Vendor
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            company = $('#AccountName').val();
            gsttype = $('#gst_type').val();
            SubActGroupID = $('#account_group').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#address2').val();
            zip = $('#zip').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            Pan = $('#pan').val();
            Aadhaarno = $('#adhaar').val();
            FLNO1 = $('#food_lic_n').val();
            StationName = $('#StationName').val();
            BAL1 = $('#opening_b').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
            if(AccountID == ''){
                alert('please enter AccountID');
                $('.saveBtn').removeAttr('disabled');
                $('#AccountID').focus();
            }else if(company == ''){
                alert('please enter Account Name');
                $('.saveBtn').removeAttr('disabled');
                $('#AccountName').focus();
            }else if(SubActGroupID == ''){
                alert('please Select Account Group');
                $('.saveBtn').removeAttr('disabled');
                $('#account_group').focus();
            }else if(state == ''){
                alert('please select State');
                $('.saveBtn').removeAttr('disabled');
                $('#state').focus();
            }else if(city == ''){
                alert('please select City');
                $('.saveBtn').removeAttr('disabled');
                $('#city').focus();
            }else if(phonenumber == ''){
                alert('please  enter mobile number');
                $('.saveBtn').removeAttr('disabled');
                $('#phonenumber').focus();
            }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
                alert("Enter valid GST no..");
                $('#vat').focus();
            }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
                alert('Enter valid Mobile number');
                $('.saveBtn').removeAttr('disabled');
                $('#phonenumber').focus();
            }else if(!$('#pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#pan').val() !== ""){
                alert('Enter valid PAN number');
                $('.saveBtn').removeAttr('disabled');
                $('#pan').focus();
            }else if(!$('#adhaar').val().match('[0-9]{12}') && $('#adhaar').val() !== ""){
                alert('Enter valid Aadhar number');
                $('.saveBtn').removeAttr('disabled');
                $('#adhaar').focus();
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>purchase/SaveVendor",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,company:company,gsttype:gsttype,SubActGroupID:SubActGroupID,
                      state:state,city:city,address:address,Address3:Address3,zip:zip,phonenumber:phonenumber,
                      altphonenumber:altphonenumber,email:email,vat:vat,Pan:Pan,Aadhaarno:Aadhaarno,
                      FLNO1:FLNO1,StationName:StationName,BAL1:BAL1,active:active,StartDate:StartDate,
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
                       $('#AccountName').val('');
                       $('#address').val('');
                       $('#address2').val('');
                       $('#zip').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#pan').val('');
                       $('#adhaar').val('');
                       $('#food_lic_n').val('');
                       $('#StationName').val('');
                       $('#opening_b').val('');
                       $('#opening_b').removeAttr('disabled');
                       $('.saveBtn').removeAttr('disabled');
                       var today = new Date();
                       var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                       $('#StartDate').val(date);
                       
                       $('select[name=gst_type]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=account_group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=active]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
                       $('.saveBtn').removeAttr('disabled');
                   }
                }
            });
            }
        });
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            AccountID = $('#AccountID').val();
            company = $('#AccountName').val();
            gsttype = $('#gst_type').val();
            SubActGroupID = $('#account_group').val();
            state = $('#state').val();
            city = $('#city').val();
            address = $('#address').val();
            Address3 = $('#address2').val();
            zip = $('#zip').val();
            phonenumber = $('#phonenumber').val();
            altphonenumber = $('#altphonenumber').val();
            email = $('#email').val();
            vat = $('#vat').val();
            Pan = $('#pan').val();
            Aadhaarno = $('#adhaar').val();
            FLNO1 = $('#food_lic_n').val();
            StationName = $('#StationName').val();
            BAL1 = $('#opening_b').val();
            active = $('#active').val();
            StartDate = $('#StartDate').val();
            if(AccountID == ''){
                alert('please enter AccountID');
                $('.updateBtn').removeAttr('disabled');
                $('#AccountID').focus();
            }else if(company == ''){
                alert('please enter Account Name');
                $('.updateBtn').removeAttr('disabled');
                $('#AccountName').focus();
            }else if(SubActGroupID == ''){
                alert('please Select Account Group');
                $('.updateBtn').removeAttr('disabled');
                $('#account_group').focus();
            }else if(state == ''){
                alert('please select State');
                $('.updateBtn').removeAttr('disabled');
                $('#state').focus();
            }else if(city == ''){
                alert('please select City');
                $('.updateBtn').removeAttr('disabled');
                $('#city').focus();
            }else if(phonenumber == ''){
                alert('please  enter mobile number');
                $('.updateBtn').removeAttr('disabled');
                $('#phonenumber').focus();
            }/*else if(!$('#vat').val().match('/^([0-9]){2}([a-zA-Z]){5}([0-9]){4}([a-zA-Z]){1}([0-9]){1}([a-zA-Z]){1}([0-9]){1}?$/') && $('#vat').val() !== '')  {
                alert("Enter valid GST no..");
                $('#vat').focus();
            }*/else if(!$('#phonenumber').val().match('[0-9]{10}') && $('#phonenumber').val() !== ""){
                alert('Enter valid Mobile number');
                $('.updateBtn').removeAttr('disabled');
                $('#phonenumber').focus();
            }else if(!$('#pan').val().match('[a-zA-Z]{5}[0-9]{4}[a-zA-Z]{1}') && $('#pan').val() !== ""){
                alert('Enter valid PAN number');
                $('.updateBtn').removeAttr('disabled');
                $('#pan').focus();
            }else if(!$('#adhaar').val().match('[0-9]{12}') && $('#adhaar').val() !== ""){
                alert('Enter valid Aadhar number');
                $('.updateBtn').removeAttr('disabled');
                $('#adhaar').focus();
            }else{
            
            $.ajax({
                url:"<?php echo admin_url(); ?>purchase/UpdateVendor",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,company:company,gsttype:gsttype,SubActGroupID:SubActGroupID,
                      state:state,city:city,address:address,Address3:Address3,zip:zip,phonenumber:phonenumber,
                      altphonenumber:altphonenumber,email:email,vat:vat,Pan:Pan,Aadhaarno:Aadhaarno,
                      FLNO1:FLNO1,StationName:StationName,BAL1:BAL1,active:active,StartDate:StartDate,
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
                       $('#AccountID').val('');
                       $('#AccountName').val('');
                       $('#address').val('');
                       $('#address2').val('');
                       $('#zip').val('');
                       $('#phonenumber').val('');
                       $('#altphonenumber').val('');
                       $('#email').val('');
                       $('#vat').val('');
                       $('#pan').val('');
                       $('#adhaar').val('');
                       $('#food_lic_n').val('');
                       $('#StationName').val('');
                       $('#opening_b').val('');
                       $('#opening_b').removeAttr('disabled');
                       var today = new Date();
                       var date = today.getDate()+'/'+(today.getMonth('mm')+1)+'/'+today.getFullYear();
                       $('#StartDate').val(date);
                       
                       $('select[name=gst_type]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=account_group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       $('select[name=state]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $("#city").children().remove();
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=active]').val('1');
                       $('.selectpicker').selectpicker('refresh');
                       $('.updateBtn').removeAttr('disabled');
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       alert_float('warning', 'Something went wrong...');
                       $('.updateBtn').removeAttr('disabled');
                   }
                }
            });
        }
        });
    });
</script>

<script>
     function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_Vendor_List");
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

#AccountID {
    text-transform: uppercase;
}
#pan {
    text-transform: uppercase;
}
#table_Vendor_List td:hover {
    cursor: pointer;
}
#table_Vendor_List tr:hover {
    background-color: #ccc;
}

    .table-Vendor_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Vendor_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Vendor_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>