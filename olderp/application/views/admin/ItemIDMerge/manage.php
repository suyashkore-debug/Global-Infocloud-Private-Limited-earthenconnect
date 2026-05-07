<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			//echo form_open($this->uri->uri_string(),array('id'=>'ItemIDMerge-form','class'=>'_transaction_form'));
			
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
                            <label for="exItemID" class="form-label">Existing ItemID</label>
                            <input type="text" name="exItemID" id="exItemID" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="exItemName" class="form-label">Existing Item Name</label>
                            <input type="text" name="exItemName" id="exItemName" class="form-control" required>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <small class="req text-danger">* </small>
                            <label for="newItemID" class="form-label">New ItemID</label>
                            <input type="text" name="newItemID" id="newItemID" class="form-control" required>
                            <div class="" id="serchh" style="display:none;">Serching</div>
                        </div>
                        
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="newItemName" class="form-label">New Item Name</label>
                            <input type="text" name="newItemName" id="newItemName" class="form-control" required>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-6">
                    <div class="" id="serchh2" style="display:none;">Modifying ItemID please wait...</div>
                    <div class="result" id="result"></div>
                    </div>
                    <br>
                     <div class="clearfix"></div>
                    <div class="col-md-1">
                        <?php
                        if (has_permission_new('ItemIDMerge', '', 'edit')) {  
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
    /*$(function(){
  "use strict";
		validate_transfer_form();
    function validate_transfer_form(selector) {
        selector = typeof(selector) == 'undefined' ? '#ItemIDMerge-form' : selector;
        appValidateForm($(selector), {
            exItemID: 'required',
            newItemID: {
				required: true,
				remote: {
					url: site_url + "admin/misc/ItemID_existsFrMearge",
					type: 'post',
					data: {
						ItemID: function() {
							return $('input[name="newItemID"]').val();
						}
					}
				}
			}
        });
    }
});*/
</script>
<script>
    $(document).ready(function(){
        $('#exItemID').on('focus',function(){
            $('#exItemID').val('');
            $('#exItemName').val('');
            $('#newItemID').val('');
    		$('#newItemName').val('');
        });
        
    // Initialize 
        $("#exItemID").autocomplete({
            source: function( request, response ) {
          // Fetch data
                $.ajax({
                    url: "<?=base_url()?>admin/ItemIDMerge/itemlist_using_itemcode",
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
                
                $('#exItemID').val(ui.item.value);
                $('#exItemName').val(ui.item.label);
                $('#newItemName').val(ui.item.label);
    			$('#newItemID').focus();
    			return false
            }
        });
        
    // Blur ExItemID
        $('#exItemID').on('blur',function(){
            var exItemID = $('#exItemID').val();
            if(exItemID == ''){
                
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/ItemIDMerge/exItemIDDetails",
                    type: 'post',
                    dataType: "json",
                    data: {
                        exItemID: exItemID
                    },
                    success: function( data ) {
                        if(data == false){
                            alert('ItemID not found...');
                            $('#exItemID').val('');
                            $('#exItemName').val('');
                            $('#newItemID').val('');
                    		$('#newItemName').val('');
                        }else{
                            $('#exItemID').val(data.item_code);
                            $('#exItemName').val(data.description);
                    		$('#newItemName').val(data.description);
                    		$('#newItemID').focus();
                        }
                    }
                });
            }
        })    
    // Check New ItemID 
        $('#newItemID').on('blur',function(){
            var newItemID = $('#newItemID').val();
            $.ajax({
                url: "<?=base_url()?>admin/ItemIDMerge/CheckNewItemID",
                type: 'post',
                dataType: "json",
                data: {
                    newItemID: newItemID
                },
                success: function( data ) {
                    if(data == false){
                        alert('ItemID already exit...');
                        $('#newItemID').val('');
                    }
                }
            });
        })
    // Form Submit
        $('.save_detail').on('click',function(){
            $('#result').html('');
            var exItemID = $('#exItemID').val();
            var exItemName = $('#exItemName').val();
            var newItemID = $('#newItemID').val();
            var newItemName = $('#newItemName').val();
            if(exItemID == '' || exItemName == '' || newItemID == '' || newItemName == ''){
                alert('please enter ItemID or ItemName');
            }else{
                $.ajax({
                    url: "<?=base_url()?>admin/ItemIDMerge/MergeItemID",
                    type: 'post',
                    dataType: "json",
                    data: {
                        exItemID: exItemID,
                        exItemName: exItemName,
                        newItemID: newItemID,
                        newItemName: newItemName,
                    },
                    beforeSend: function () {
                        $('#serchh2').css('display','block');
                    },
                    complete: function () {
                        $('#serchh2').css('display','none');
                    },
                    success: function( data ) {
                        $('#result').html(data);
                        $('#exItemID').val('');
                        $('#exItemName').val('');
                        $('#newItemID').val('');
                		$('#newItemName').val('');
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
