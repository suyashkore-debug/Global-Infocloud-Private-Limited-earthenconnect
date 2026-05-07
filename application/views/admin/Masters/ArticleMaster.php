<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Article Master</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="row">
						    <div class="col-md-4">
						        <div class="form-group">
									<label class="form-label">Party Name</label>
									<small class="req text-danger">* </small>
									<select class="selectpicker" name="PartyName" id="PartyName" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
										<option></option>
										<?php
											foreach ($AllPartyList as $key => $value) {
											?>
											<option value="<?php echo $value['AccountID']; ?>"><?php echo $value['company']; ?></option>   
											<?php   
											}
										?>
									</select>
								</div>
						    </div>
						    
						    <div class="clearfix"></div>
						    <div class="col-md-10" style="margin-top:1%;">
						        <input type="hidden" name="ItemAdded" id="ItemAdded" value="">
						        <input type="hidden" name="ItemDeleted" id="ItemDeleted" value="">
                                <table class="table items table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="40%">Item Name</th>
                                            <th width="50%">Article No/Name</th>
                                            <th width="10%">&nbsp;</th>
                                        </tr>
                                    </thead>
                                        <tbody id="parameter_body">
                                            <?php
                                            $JsonAllFGList = json_encode($AllFGList);
                                            ?>
                                                <tr>
                                                    <td width="30%">
                                                        <select id="ItemID1" name="ItemID" class="selectpicker" data-width="100%" data-none-selected-text="Non Selected" data-live-search="true">
                                                            <option></option>
                                                            <?php foreach($AllFGList as $key=>$value){ ?>
                                                                <option value="<?= $value['item_code']; ?>"><?= $value['description']; ?></option> 
                                                            <?php } ?>
                                                        </select>
                                                    </td>
                                                    <td width="40%"><input id="ArticleName" class="form-control" name="ArticleName" type="text" value=""></td>
                                                    <td width="10%"><button type="button" onclick="add_row()" style="float:right;" class="btn btn-success" title="Add Article"><i class="fa fa-plus"></i></button></td>
                                                </tr>
                                        </tbody>
                                </table>
                            </div>
                            
                            <div class="col-md-12" style="margin-top:2%;">
                                <?php if (has_permission_new('ArticleMaster', '', 'create')) {
                                ?>
                                <button type="button" class="btn btn-info saveBtn" style="margin-right: 25px;">Save</button>
                                <?php
                                }else{
                                ?>
                                <button type="button" class="btn btn-info saveBtn2 disabled" style="margin-right: 25px;">Save</button>
                                <?php
                                }?>
                                  
                                <?php if (has_permission_new('ArticleMaster', '', 'edit')) {
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
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php init_tail(); ?>
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
<script>
    $(document).ready(function(){
        $('.updateBtn').hide();
        $('.updateBtn2').hide();
    });    
    $(".cancelBtn").click(function(){
        $("#ItemAdded").val("");
        $("#ItemDeleted").val('');
        $(".addedtr").remove();
        $("#ItemID1").val('');
		$('#ItemID1').selectpicker('refresh');
		$("#ArticleName").val('');
		$("#PartyName").val('');
		$('#PartyName').selectpicker('refresh');
		$('.saveBtn').show();
        $('.updateBtn').hide();
        $('.saveBtn2').show();
        $('.updateBtn2').hide();
    });
    $("#parameter_body").on('click','.removebtn',function(){
        // Add Id in deleted Item list
        var DeleteID = $(this).parents("tr").find(".uniqueID").val();
        var ItemDeleted = $("#ItemDeleted").val();
        $("#ItemDeleted").val(ItemDeleted+','+DeleteID);
        
        // Remove ItemID from Item Added List
        var ItemID = $(this).parents("tr").find(".ItemIDs").val();
		var ItemAdded = $("#ItemAdded").val();
		let result = ItemAdded.replace(ItemID, " ");
		$("#ItemAdded").val(result);
        $(this).parent().parent().remove();
	});
	
	function add_row(){
	    var PartyName = $("#PartyName").val();
		var ItemID = $("#ItemID1").val();
		var ArticleName = $("#ArticleName").val();
		var ItemAdded = $("#ItemAdded").val();
		var ItemAddedArray = ItemAdded.split(",");
		if(PartyName == ""){
		    alert("Please select Party");
		}else if(ItemID == ""){
		    alert("Please Select Item");
		}else if(ArticleName == ""){
		    alert("Please Enter Article Name");
		}else if(ItemAddedArray.includes(ItemID)){
		    alert("Item Already Added");
		}else{
		    var lasttr = $('#parameter_body tr:last td').find("select").attr('id');
			var num= lasttr.match(/-?\d+\.?\d*/);
			var newcount = parseInt(num)+parseInt(1);
			var allParameter = <?= $JsonAllFGList?>;
			
			markup = "<tr class='addedtr'><td><input type='hidden' name='ItemList[]' value='"+ItemID+"' class='ItemIDs'><select name='ItemID1[]'  disabled id='ItemID"+newcount+"' value='"+ItemID+"' class='form-control selectpicker' data-live-search='true'></select></td>";
			markup += "<td><input name='ArticleName1[]' id='ArticleName"+newcount+"' value='"+ArticleName+"' class='form-control'></td>";
			markup += "<td><a href='#' style='float:right;' id='removebtn' class='btn btn-danger removebtn'><i class='fa fa-times'></i></a></td></tr>";
			tableBody = $("#parameter_body");
			tableBody.append(markup);
			
			for (var i = 0; i < allParameter.length; i++) {
				$("#ItemID"+newcount).append(new Option(allParameter[i].description, allParameter[i].item_code));
			}
			
			$("#ItemID"+newcount).val(ItemID);
			$("#ItemID"+newcount).selectpicker('refresh');
			$("#ItemAdded").val(ItemAdded+','+ItemID);
			
			$("#ItemID1").val('');
			$('#ItemID1').selectpicker('refresh');
			$("#ArticleName").val('');
		}
	}
	
	$('#PartyName').on('change',function(){ 
        AccountID = $(this).val();
        $("#ItemAdded").val("");
        $(".addedtr").remove();
        $.ajax({
            url:"<?php echo admin_url(); ?>Masters/GetPartyWiseArticleList",
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
                if(!empty(data)){
                    init_selectpicker();
                    $('select[name=tax]').val(data.tax).selectpicker('refresh');
                    let ItemParameter = data;
    				for(var count = 0; count < ItemParameter.length; count++)
                    {
                        var ItemAdded = $("#ItemAdded").val();
                        var tblid = ItemParameter[count].id;
                        var ItemID = ItemParameter[count].ItemID;
                        var ArticleName = ItemParameter[count].ArticleName;
                        
    					var lasttr = $('#parameter_body tr:last td').find("select").attr('id');
    					var num= lasttr.match(/-?\d+\.?\d*/);
    					var newcount = parseInt(num)+parseInt(1);
    					
    					var allParameter = <?= $JsonAllFGList?>;
    					
    					markup = "<tr class='addedtr'><td><input type='hidden' name='addtblid[]' value='"+tblid+"' class='uniqueID'><input type='hidden' name='ItemList[]' value='"+ItemID+"' class='ItemIDs'><select disabled name='ItemID1[]' id='ItemID"+newcount+"' value='"+ItemID+"' class='form-control selectpicker ' data-live-search='true'></select></td>";
    					markup += "<td><input name='ArticleName1[]' id='ArticleName"+newcount+"' value='"+ArticleName+"' class='form-control '></td>";
    					markup += "<td><a href='#' style='float:right;' id='removebtn' class='btn btn-danger removebtn'><i class='fa fa-times'></i></a></td></tr>";
    					tableBody = $("#parameter_body");
    					tableBody.append(markup);
    					
    					for (var i = 0; i < allParameter.length; i++) {
    						$("#ItemID"+newcount).append(new Option(allParameter[i].description, allParameter[i].item_code));
    					}
    					$("#ItemID"+newcount).val(ItemID);
    					$("#ItemID"+newcount).selectpicker('refresh');
    					$("#ItemAdded").val(ItemAdded+','+ItemID);
    				}
                    $('.saveBtn').hide();
                    $('.updateBtn').show();
                    $('.saveBtn2').hide();
                    $('.updateBtn2').show();
                }else{
                    $('.saveBtn').show();
                    $('.updateBtn').hide();
                    $('.saveBtn2').show();
                    $('.updateBtn2').hide();
                }
            }
        });
    });
	
	// Save New Item
    $('.saveBtn').on('click',function(){ 
        AccountID = $('#PartyName').val();
        let ArticleArr = [];
	    var i = 1;
	    var ItemID = $("select[name='ItemID1[]']")
		.map(function(){return $(this).val();}).get();
		ItemID.forEach(function callback(value, index) {
			if(value != "")
			{
				var ArticleName = $("input[name='ArticleName1[]']")
				.map(function(){return $(this).val();}).get()[index];
				
				var ii = i - 1;
				ArticleArr[ii]=new Array();
				ArticleArr[ii][0]=value;
				ArticleArr[ii][1]=ArticleName;
				i++;
			}
		});
	
	    let ArticledataArraylength = ArticleArr.length;
	    var ArticledataSerializedArr = JSON.stringify(ArticleArr);
        if(AccountID == ''){
            alert('please Select Perty');
            $('#PartyName').focus();
        }else{
            $.ajax({
                url:"<?php echo admin_url(); ?>Masters/SaveItemWiseArticle",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,ArticledataArraylength:ArticledataArraylength,ArticledataSerializedArr:ArticledataSerializedArr
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
                        $('select[name=PartyName]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        $(".addedtr").remove();
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
    
    // Update Party Wise Item Wise Article List
    $('.updateBtn').on('click',function(){ 
        AccountID = $('#PartyName').val();
        ItemDeleted = $('#ItemDeleted').val();
        let ArticleArr = [];
	    var i = 1;
	    var ItemID = $("select[name='ItemID1[]']")
		.map(function(){return $(this).val();}).get();
		ItemID.forEach(function callback(value, index) {
			if(value != "")
			{
				var ArticleName = $("input[name='ArticleName1[]']")
				.map(function(){return $(this).val();}).get()[index];
				var addtblid = $("input[name='addtblid[]']")
				.map(function(){return $(this).val();}).get()[index];
				
				var ii = i - 1;
				ArticleArr[ii]=new Array();
				ArticleArr[ii][0]=value;
				ArticleArr[ii][1]=ArticleName;
				ArticleArr[ii][2]=addtblid;
				i++;
			}
		});
	
	    let ArticledataArraylength = ArticleArr.length;
	    var ArticledataSerializedArr = JSON.stringify(ArticleArr);
        if(AccountID == ''){
            alert('please Select Perty');
            $('#PartyName').focus();
        }else{
            $.ajax({
                url:"<?php echo admin_url(); ?>Masters/UpdateItemWiseArticle",
                dataType:"JSON",
                method:"POST",
                data:{AccountID:AccountID,ArticledataArraylength:ArticledataArraylength,
                        ArticledataSerializedArr:ArticledataSerializedArr,ItemDeleted:ItemDeleted
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
                        $('select[name=PartyName]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        $(".addedtr").remove();
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
</script>