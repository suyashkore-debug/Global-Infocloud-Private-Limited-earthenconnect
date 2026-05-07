<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			//echo form_open($this->uri->uri_string(),array('id'=>'AccountIDMerge-form','class'=>'_transaction_form'));
			
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
      
            <div class="tab-content">
                
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                    
                    <div class="col-md-2">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label for="exAccountID" class="form-label">Existing AccountID</label>
                            <input type="text" name="exAccountID" id="exAccountID" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exAccountName" class="form-label">Existing Account Name</label>
                            <input type="text" name="exAccountName" id="exAccountName" class="form-control" required>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label for="newAccountID" class="form-label">New AccountID</label>
                            <input type="text" name="newAccountID" id="newAccountID" class="form-control" required>
                            <div class="" id="serchh" style="display:none;">Serching</div>
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newAccountName" class="form-label">New Account Name</label>
                            <input type="text" name="newAccountName" id="newAccountName" class="form-control" required>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                        <div class="" id="serchh2" style="display:none;">Modifying AccountID please wait...</div>
                        <div class="result" id="result"></div>
                    </div>
                    <div class="clearfix"></div>
                    <br>
                    <div class="col-md-1">
                        <?php
                        if (has_permission_new('AccountIDMerge', '', 'edit')) {  
                        ?>
                            <button type="button"  class="btn-tr save_detail btn btn-info mleft10 ">
                            Save</button>
                        <?php } ?>
                    </div>
                    <div class="col-md-1">
                        <?php
                        //if (has_permission_new('stock_adjustment', '', 'create')) {  
                        ?>
                            <!--<button type="button"  class="btn-tr btn btn-warning mleft10 transaction-submit">Cancel</button>-->
                            <a href="#" class="btn-tr btn btn-default mleft10">Cancel</a>
                        <?php// } ?>
                    </div>
                        
                </div>
            </div>
            </div>
        </div>
              </div>
        </div>
        </div>

			</div>
			<?php //echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>



<?php init_tail(); ?>

</body>

</html>


<script>
    $(document).ready(function(){
        $('#exAccountID').on('focus',function(){
            $('#exAccountID').val('');
            $('#exAccountName').val('');
            $('#newAccountID').val('');
    		$('#newAccountName').val('');
        });
        
    // Initialize 
        $("#exAccountID").autocomplete({
            source: function( request, response ) {
          // Fetch data
                $.ajax({
                    url: "<?=base_url()?>admin/AccountIDMerge/AccountlistByAccountID",
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
                
                $('#exAccountID').val(ui.item.value);
                $('#exAccountName').val(ui.item.label);
                $('#newAccountName').val(ui.item.label);
    			$('#newAccount').focus();
    			return false
            }
        });
        
    // Blur exAccountID
        $('#exAccountID').on('blur',function(){
            var exAccountID = $('#exAccountID').val();
            if(exAccountID == ''){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/AccountIDMerge/exAccountIDDetails",
                    type: 'post',
                    dataType: "json",
                    data: {
                        exAccountID: exAccountID
                    },
                    success: function( data ) {
                        if(data == false){
                            alert('AccountID not found...');
                            $('#exAccountID').val('');
                            $('#exAccountName').val('');
                            $('#newAccountID').val('');
                    		$('#newAccountName').val('');
                        }else{
                            $('#exAccountID').val(data.AccountID);
                            $('#exAccountName').val(data.Name);
                    		$('#newAccountName').val(data.Name);
                    		$('#newAccountID').focus();
                        }
                    }
                });
            }
        })    
    // Check New ItemID 
        $('#newAccountID').on('blur',function(){
            var newAccountID = $('#newAccountID').val();
            $.ajax({
                url: "<?=base_url()?>admin/AccountIDMerge/CheckNewAccountID",
                type: 'post',
                dataType: "json",
                data: {
                    newAccountID: newAccountID
                },
                success: function( data ) {
                    if(data == true){
                    }else{
                        var msg = 'AccountID already exit for  '+data.Name+' ...';
                        alert(msg);
                        $('#newAccountID').val('');
                    }
                }
            });
        })
    // Form Submit
        $('.save_detail').on('click',function(){
            $('#result').html('');
            var exAccountID = $('#exAccountID').val();
            var exAccountName = $('#exAccountName').val();
            var newAccountID = $('#newAccountID').val();
            var newAccountName = $('#newAccountName').val();
            if(exAccountID == '' || exAccountName == '' || newAccountID == '' || newAccountName == ''){
                alert('please enter AccountID or ItemName');
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/AccountIDMerge/MergeAccountID",
                    type: 'post',
                    dataType: "json",
                    data: {
                        exAccountID: exAccountID,
                        exAccountName: exAccountName,
                        newAccountID: newAccountID,
                        newAccountName: newAccountName,
                    },
                    beforeSend: function () {
                        $('#serchh2').css('display','block');
                    },
                    complete: function () {
                        $('#serchh2').css('display','none');
                    },
                    success: function( data ) {
                        $('#result').html(data);
                        $('#exAccountID').val('');
                        $('#exAccountName').val('');
                        $('#newAccountID').val('');
                		$('#newAccountName').val('');
                    }
                });
            }
        })
    });
</script>
<style>
    .table-result          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-result thead th { position: sticky; top: 0; z-index: 1; }
.table-result tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
