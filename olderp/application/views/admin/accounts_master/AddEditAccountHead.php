<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-9">
        <div class="panel_s">
          <div class="panel-body">
                
                  <?php //echo form_open('admin/accounts_master/',array('id'=>'accounting_head')); ?>
                <div class="row">
                    <div class="col-md-3">
                        <?php $value = (isset($account_detail) ? $account_detail->AccountID : ''); ?>
                        <?php
                        $date_attrs = array();
                        $date_attrs2 = array();
                            if(isset($account_detail)){
                                
                                $date_attrs['disabled'] = true;
                                $opening_bal = get_opening_bal($value);
                                //$bal_on_bill = get_bal_on_bill($value);
                                
                            }
                            if(is_admin() || has_permission_new('account_head', '', 'edit')){
                                
                            }else{
                                $date_attrs2['disabled'] = true;
                            }
                        ?>
                       
                       
                        <input type="hidden" value="<?php echo $value; ?>" name="edit_account_id">
                        
                        <?php echo render_input('account_id','Account ID',$value,'',$date_attrs); ?>
                        <?php $selected_company = $this->session->userdata('root_company');
                            ?>
                        <input type="hidden" id="PlantID" name="PlantID" class="form-control" value="<?php echo $selected_company;?>">
                    </div>
                    <div class="col-md-3">
                        <?php $value = (isset($account_detail) ? $account_detail->company : ''); ?>
                        <?php echo render_input('account_name','Account Name',$value); ?>
                    </div>
                    
                    <div class="col-md-3">
                       <?php $value = (isset($account_detail) ? $account_detail->SubActGroupID : ''); ?>
                        <?php echo render_select('Account_Group',$account_subgroup,array('SubActGroupID','SubActGroupName'),'ActGroup Name',$value,$date_attrs2); ?>
                    </div>
                    
                    
                    <div class="col-md-3">
                        <?php
                            $staff_user_id = $this->session->userdata('staff_user_id');
                        ?>
                        <?php //echo render_input('opening_bal','Opening Bal.'); ?>
                        <label for="opening_bal" class="control-label">Opening Bal.</label>
                            <input type="text" maxlength="12"  name="opening_bal" pattern="[0-9]" id="opening_bal" class="form-control" value="" >
                            <span class="opening_bal_denger" style="color:red;"></span>
                            <?php $staff_user_id = $this->session->userdata('staff_user_id'); ?>
                            <input type="hidden" name="staffid" value="<?php echo $staff_user_id; ?>" id="staffid">
                    </div>
                </div>
                
                
                <div class="row">
                    <!--<div class="col-md-3">
                        <?php echo render_input('security_dep','Security Dep.'); ?>
                    </div>-->
                    <div class="col-md-3">
                        <?php $value = (isset($account_detail) ? $account_detail->Blockyn : ''); ?>
                        <div class="form-group">
						<label for="block_ac" class="control-label">Block A/C</label>
						<select class="form-control " name="block_ac" data-live-search="true" id="block_ac">
						    <option value="N" <?php if($value == "N") echo "selected";?>>No</option>
						    <option value="Y" <?php if($value == "Y") echo "selected";?>>Yes</option>
						</select>
						</div>
                    </div>
                    <!--<div class="col-md-3">
                        <?php //$value1 = $bal_on_bill->BalancesYN; 
                        
                        ?>
                        <div class="form-group">
						<label for="bal_on_bill" class="control-label">Balance on Bill</label>
						<select class="form-control " name="bal_on_bill" data-live-search="true" id="bal_on_bill">
						    
						    <option value="Y" <?php if($value1 == "Y") echo "selected";?>>Yes</option>
						    <option value="N" <?php if($value1 == "N") echo "selected";?>>No</option>
						</select>
						</div>
                    </div>-->
                    <div class="col-md-3">
                        <?php $value = (isset($account_detail) ? $account_detail->StartDate : date('Y-m-d')); 
                        if(isset($account_detail)){
                            $new_val = substr($value,0,10);
                        }else{
                            $new_val = $value;
                        }
                        
                        ?>
                        
                        <?php echo render_date_input('start_date','Start Date',$new_val,$date_attrs2); ?>
                    </div>
                </div>
               
                <div class="row">
                    
                    <div class="col-md-12">
                        <?php if (has_permission('account_head', '', 'create')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Save</button>
                        <?php
                        }else{
                        ?>
                        <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                        <?php
                        }?>
                        
                        <?php if (has_permission('account_head', '', 'edit')) {
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
                    
                   <!-- <div class="col-md-1" >
                       <br>
                    <?php
                        if(isset($account_detail) && has_permission_new('account_head', '', 'edit')){
                    ?>
                    <button type="submit" class="btn btn-info">Update</button>
                    <?php
                        }
                    if( has_permission_new('account_head', '', 'create') && !isset($account_detail)) { ?>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                    <?php } ?>
                    </div>-->
                     <!--<div class="col-md-2" style="margin-top: 10px;">
                       
                       <?php $redUrl = admin_url('accounts_master/manage_accounts'); 
                       if(has_permission_new('account_head', '', 'view')) { ?>
                        <a href="<?php echo $redUrl;?>" class="btn btn-info">Account list</a>
                        <?php } ?>
                    </div>-->
                </div>
                
                  <?php //echo form_close(); ?>
                  
                <div class="clearfix"></div>
                <!-- Account Head List Model-->
            
                <div class="modal fade AccountHead_List" id="AccountHead_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">AccountHead List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-AccountHead_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-AccountHead_List tableFixHead2" id="table_AccountHead_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">AccountID <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span></th>
                                            <th style="text-align:left;">AccountName</th>
                                            <th style="text-align:left;">SubGroup Name</th>
                                            <th style="text-align:left;">MainGroup Name</th>
                                            <th style="text-align:left;">Bloked</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($accounts as $key => $value) {
                                    ?>
                                        <tr class="get_AccountID" data-id="<?php echo $value["AccountID"]; ?>">
                                            <td><?php echo $value['AccountID'];?></td>
                                            <td><?php echo $value['company'];?></td>
                                            <td><?php echo $value["SubActGroupName"];?></td>
                                            <td><?php echo $value["ActGroupName"];?></td>
                                            <td><?php echo $value["Blockyn"];?></td>
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
$(function(){
        'use strict';
        appValidateForm($('#accounting_head'), {
            
            account_id: {
				required: true,
				remote: {
					url: site_url + "admin/misc/accountID_exists",
					type: 'post',
					data: {
						account_id: function() {
							return $('input[name="account_id"]').val();
						},
					}
				}
			},
            account_name: 'required',
            Account_Group: 'required',
            
        });
        
        
    });
</script>

<script>
    $(document).ready(function(){
        var SessionID = "<?php echo $this->session->userdata('AccountIDSet');?>";
        if(SessionID !== ""){
            $.ajax({
                url:"<?php echo admin_url(); ?>Accounts_master/GetAccountDetailByID",
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
                        //alert("AccoutID not found...");
                        $('#account_name').val('');
                        $('#opening_bal').val('');
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                        $('#start_date').val(today);
                       $('select[name=Account_Group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=block_ac]').val('Y');
                       $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $("#opening_bal").prop("readonly", false);
                        }
                        $('.saveBtn').removeAttr('disabled');
                    }else{
                        if(data.AccountType == 'Staff'){
                            alert('This AccountID Use for Staff');
                            $('#account_id').focus();
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                        }else{
                            const SubActGroupIDArray = ["30000004","10022004","10022005","1002504","1002503","1002506","30000007","30001002","50003002","60001004"];
                            if(!SubActGroupIDArray.includes(data.SubActGroupID)){
                                var PlantID = $('#PlantID').val();
                                if(PlantID == data.PlantID){
                                    $('.saveBtn').hide();
                                    $('.updateBtn').show();
                                    $('.saveBtn2').hide();
                                    $('.updateBtn2').show();
                                    $('#account_id').val(data.AccountID);
                                    $('#account_name').val(data.company);
                                    $('#opening_bal').val(data.BAL1);
                                    if(data.StartDate !== null){
                                        var date = data.StartDate.substring(0, 10)
                                        var date_new = date.split("-").reverse().join("/");
                                        $('#start_date').val(date_new);
                                    }
                                    $('select[name=Account_Group]').val(data.SubActGroupID);
                                    $('.selectpicker').selectpicker('refresh');
                                       
                                    $('select[name=block_ac]').val(data.Blockyn);
                                    $('.selectpicker').selectpicker('refresh');
                                    var staffid = $('#staffid').val();
                                    if(staffid !== "3"){
                                        $("#opening_bal").prop("readonly", true);
                                    }
                                    
                                }else{
                                    alert('This AccountID Use for Other Plant');
                                    $('#account_id').focus();
                                    $('.saveBtn').show();
                                    $('.updateBtn').hide();
                                    $('.saveBtn2').show();
                                    $('.updateBtn2').hide();
                                }
                                    
                            }else{
                                alert('This AccountID already Use...');
                                $('#account_id').focus();
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
        
        $("#account_id").dblclick(function(){
            $('#AccountHead_List').modal('show');
            $('#AccountHead_List').on('shown.bs.modal', function () {
                $('#myInput1').val('');
                $('#myInput1').focus();
            })
        });
    // ItemID Typing Validation
        $("#account_id").keypress(function (e) {
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
        $("#account_id").focus(function(){
            $('#account_id').val('');
            $('#account_name').val('');
            $('#opening_bal').val('');
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            
            today = dd + '/' + mm + '/' + yyyy;
            $('#start_date').val(today);
                       
                       
            $('select[name=Account_Group]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=block_ac]').val('Y');
            $('.selectpicker').selectpicker('refresh');
                       
            /*$('select[name=bal_on_bill]').val('Y');
            $('.selectpicker').selectpicker('refresh');*/
            
            var staffid = $('#staffid').val();
            if(staffid !== "3"){
                $("#opening_bal").prop("readonly", false);
            }
            $('.saveBtn').removeAttr('disabled');           
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // Cancel selected data
        $(".cancelBtn").click(function(){
            $('#account_id').val('');
            $('#account_name').val('');
            $('#opening_bal').val('');
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            
            today = dd + '/' + mm + '/' + yyyy;
            $('#start_date').val(today);
                       
                       
            $('select[name=Account_Group]').val('');
            $('.selectpicker').selectpicker('refresh');
                       
            $('select[name=block_ac]').val('Y');
            $('.selectpicker').selectpicker('refresh');
                       
            /*$('select[name=bal_on_bill]').val('Y');
            $('.selectpicker').selectpicker('refresh');*/
            
            var staffid = $('#staffid').val();
            if(staffid !== "3"){
                $("#opening_bal").prop("readonly", false);
            }
            $('.saveBtn').removeAttr('disabled');            
            $('.saveBtn').show();
            $('.saveBtn2').show();
            $('.updateBtn').hide();
            $('.updateBtn2').hide();
            
        });
        
    // On Blur ItemID Get All Date
        $('#account_id').blur(function(){ 
            AccountID = $(this).val();
            if(AccountID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>Accounts_master/GetAccountDetailByID",
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
                        //alert("AccoutID not found...");
                        $('#account_name').val('');
                        $('#opening_bal').val('');
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                        $('#start_date').val(today);
                       $('select[name=Account_Group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=block_ac]').val('Y');
                       $('.selectpicker').selectpicker('refresh');
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $("#opening_bal").prop("readonly", false);
                        }
                        $('.saveBtn').removeAttr('disabled');
                    }else{
                        if(data.AccountType == 'Staff'){
                            alert('This AccountID Use for Staff');
                            $('#account_id').focus();
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                        }else{
                            const SubActGroupIDArray = ["30000004","10022004","10022005","1002504","1002503","1002506","30000007","30001002","50003002","60001004"];
                            if(!SubActGroupIDArray.includes(data.SubActGroupID)){
                                var PlantID = $('#PlantID').val();
                                if(PlantID == data.PlantID){
                                    $('.saveBtn').hide();
                                    $('.updateBtn').show();
                                    $('.saveBtn2').hide();
                                    $('.updateBtn2').show();
                                    $('#account_id').val(data.AccountID);
                                    $('#account_name').val(data.company);
                                    $('#opening_bal').val(data.BAL1);
                                    if(data.StartDate !== null){
                                        var date = data.StartDate.substring(0, 10)
                                        var date_new = date.split("-").reverse().join("/");
                                        $('#start_date').val(date_new);
                                    }
                                    $('select[name=Account_Group]').val(data.SubActGroupID);
                                    $('.selectpicker').selectpicker('refresh');
                                       
                                    $('select[name=block_ac]').val(data.Blockyn);
                                    $('.selectpicker').selectpicker('refresh');
                                    var staffid = $('#staffid').val();
                                    if(staffid !== "3"){
                                        $("#opening_bal").prop("readonly", true);
                                    }
                                    
                                }else{
                                    alert('This AccountID Use for Other Plant');
                                    $('#account_id').focus();
                                    $('.saveBtn').show();
                                    $('.updateBtn').hide();
                                    $('.saveBtn2').show();
                                    $('.updateBtn2').hide();
                                }
                                    
                            }else{
                                alert('This AccountID already Use...');
                                $('#account_id').focus();
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
                url:"<?php echo admin_url(); ?>Accounts_master/GetAccountDetailByID",
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
                       $('#account_id').val(data.AccountID);
                       $('#account_name').val(data.company);
                       $('#opening_bal').val(data.BAL1);
                       if(data.StartDate !== null){
                           var date = data.StartDate.substring(0, 10)
                            var date_new = date.split("-").reverse().join("/");
                            $('#start_date').val(date_new);
                       }
                       $('select[name=Account_Group]').val(data.SubActGroupID);
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=block_ac]').val(data.Blockyn);
                       $('.selectpicker').selectpicker('refresh');
                       
                       /*$('select[name=bal_on_bill]').val(data.BalancesYN);
                       $('.selectpicker').selectpicker('refresh');*/
                       
                       var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $("#opening_bal").prop("readonly", true);
                        }
                        
                       $('.saveBtn').hide();
                       $('.updateBtn').show();
                       $('.saveBtn2').hide();
                       $('.updateBtn2').show();
                }
            });
            $('#AccountHead_List').modal('hide');
        });
        
    // Save New Item
        $('.saveBtn').on('click',function(){ 
            AccountID = $('#account_id').val();
            company = $('#account_name').val();
            SubActGroupID = $('#Account_Group').val();
            BAL1 = $('#opening_bal').val();
            Blockyn = $('#block_ac').val();
            /*BalancesYN = $('#bal_on_bill').val();*/
            StartDate = $('#start_date').val();
        if(AccountID == ''){
            alert('please enter AccountID');
            $('.saveBtn').removeAttr('disabled');
            $('#account_id').focus();
        }else if(company == ""){
            alert('please enter Account Name');
            $('.saveBtn').removeAttr('disabled');
            $('#account_name').focus();
        }else if(SubActGroupID == ""){
            alert('please Select Account Group');
            $('.saveBtn').removeAttr('disabled');
            $('#Account_Group').focus();
        }else{
            $.ajax({
                url:"<?php echo admin_url(); ?>Accounts_master/SaveItemID",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,company:company,SubActGroupID:SubActGroupID,BAL1:BAL1,Blockyn:Blockyn,
                    StartDate:StartDate
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
                       $('#account_id').val('');
                        $('#account_name').val('');
                        $('#opening_bal').val('');
                        var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            
            today = dd + '/' + mm + '/' + yyyy;
            $('#start_date').val(today);
                       
                       
                       $('select[name=Account_Group]').val('');
                       $('.selectpicker').selectpicker('refresh');
                       
                       $('select[name=block_ac]').val('Y');
                       $('.selectpicker').selectpicker('refresh');
                       
                       /*$('select[name=bal_on_bill]').val('Y');
                       $('.selectpicker').selectpicker('refresh');*/
                        $('.saveBtn').removeAttr('disabled');           
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                        var staffid = $('#staffid').val();
                        if(staffid !== "3"){
                            $("#opening_bal").prop("readonly", false);
                        }
                                   
                        $('.saveBtn').show();
                        $('.updateBtn').hide();
                        $('.saveBtn2').show();
                        $('.updateBtn2').hide();
                   }else{
                       $('.saveBtn').removeAttr('disabled');
                       alert_float('warning', 'Something went wrong...');
                   }
                }
            });    
        }
            
        });
    // Update Exiting Item
        $('.updateBtn').on('click',function(){ 
            AccountID = $('#account_id').val();
            company = $('#account_name').val();
            SubActGroupID = $('#Account_Group').val();
            BAL1 = $('#opening_bal').val();
            Blockyn = $('#block_ac').val();
            /*BalancesYN = $('#bal_on_bill').val();*/
            StartDate = $('#start_date').val();
            if(AccountID == ''){
                alert('please enter AccountID');
                $('.saveBtn').removeAttr('disabled');
                $('#account_id').focus();
            }else if(company == ""){
                alert('please enter Account Name');
                $('.saveBtn').removeAttr('disabled');
                $('#account_name').focus();
            }else if(SubActGroupID == ""){
                alert('please Select Account Group');
                $('.saveBtn').removeAttr('disabled');
                $('#Account_Group').focus();
            }else{
                $.ajax({
                    url:"<?php echo admin_url(); ?>Accounts_master/UpdateAccountID",
                    dataType:"JSON",
                    method:"POST",
                    data:{AccountID:AccountID,company:company,SubActGroupID:SubActGroupID,BAL1:BAL1,Blockyn:Blockyn,
                        StartDate:StartDate
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
                           $('#account_id').val('');
                            $('#account_name').val('');
                            $('#opening_bal').val('');
                            var today = new Date();
                            var dd = String(today.getDate()).padStart(2, '0');
                            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                            var yyyy = today.getFullYear();
                            
                            today = dd + '/' + mm + '/' + yyyy;
                            $('#start_date').val(today);
                            
                           $('select[name=Account_Group]').val('');
                           $('.selectpicker').selectpicker('refresh');
                           
                           $('select[name=block_ac]').val('Y');
                           $('.selectpicker').selectpicker('refresh');
                           
                           /*$('select[name=bal_on_bill]').val('Y');
                           $('.selectpicker').selectpicker('refresh');*/
                            
                            var staffid = $('#staffid').val();
                            if(staffid !== "3"){
                                $("#opening_bal").prop("readonly", false);
                            }
                            $('.updateBtn').removeAttr('disabled');
                            $('.saveBtn').show();
                            $('.updateBtn').hide();
                            $('.saveBtn2').show();
                            $('.updateBtn2').hide();
                       }else{
                           $('.updateBtn').removeAttr('disabled');
                           alert_float('warning', 'Data not updated...');
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
  table = document.getElementById("table_AccountHead_List");
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
   $('#opening_bal').on('keypress',function (event) {
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

#account_id {
    text-transform: uppercase;
}
#table_AccountHead_List td:hover {
    cursor: pointer;
}
#table_AccountHead_List tr:hover {
    background-color: #ccc;
}

    .table-AccountHead_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-AccountHead_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-AccountHead_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>
 
</body>
</html>