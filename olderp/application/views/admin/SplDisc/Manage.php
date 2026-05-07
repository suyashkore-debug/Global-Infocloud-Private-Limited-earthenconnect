<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .No-Padding {
    padding:0px;
}
.fixTableHead {
      overflow-y: auto;
      max-height: 150px;
    }
    .fixTableHead thead th {
      position: sticky;
      top: 0;
    }
    .fixTableHead table {
      border-collapse: collapse;        
      width: 100%;
      
    }
   .fixTableHead th,
    td {
      border: 2px solid #529432;
      white-space: nowrap;
    }
    .fixTableHead th {
      background: #51647c;
        vertical-align: middle;
    }
    
    .fixTableHead2 {
      overflow-y: auto;
      max-height: 350px;
    }
    .fixTableHead2 thead th {
      position: sticky;
      top: 0;
    }
    .fixTableHead2 table {
      border-collapse: collapse;        
      width: 100%;
      
    }
   .fixTableHead2 th,
    td {
      padding: 5px 5px;
      border: 2px solid #529432;
      white-space: nowrap;
    }
    .fixTableHead2 th {
      background: #51647c;
      padding: 5px 5px;
      text-align: left;
    vertical-align: middle;
    }
    
.daily_report th { padding: 0px 5px !important; border:1px solid !important;font-size:11px; line-height:0.7!important;vertical-align: middle !important;}
.daily_report td { padding: 0px 5px !important; border:1px solid !important;font-size:11px; line-height:0.7!important;vertical-align: middle !important;}


 
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body" style="padding-top:8px;">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                    <?php
                        $selected_company = $this->session->userdata('root_company');
            			$fy = $this->session->userdata('finacial_year');
                        if($selected_company == 1){
                            $new_DiscNumber = get_option('next_spldisc_number_for_cspl');
                        }elseif($selected_company == 2){
                            $new_DiscNumber = get_option('next_spldisc_number_for_cff');
                        }elseif($selected_company == 3){
                            $new_DiscNumber = get_option('next_spldisc_number_for_cbu');
                        }elseif($selected_company == 4){
                            $new_DiscNumber = get_option('next_spldisc_number_for_cbupl');
                        }
                        $format = get_option('invoice_number_format');
                        $prefix = "DIS".$fy;
                        $_new_DiscNumber = str_pad($new_DiscNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                    ?>
                    <div class="col-md-12">
                        
                        <div class="searchh22" style="display:none;">Please wait Create new SplDisc...</div>
                        <div class="searchh33" style="display:none;">Please wait update SplDisc...</div>
                    </div>
                    
                    <div class="col-md-2 ">
                        <div class="row">
                            <div class="col-md-4  No-Padding">
                                <label for="number">DiscountID</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <div class="form-group">
                                    <input type="text" name="DiscID" id="DiscID" class="form-control DiscID" value="<?php echo $prefix.$_new_DiscNumber; ?>">
                                    <input type="hidden" name="DiscIDHidden" id="DiscIDHidden" value="<?php echo $prefix.$_new_DiscNumber; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 No-Padding">
                                <label for="number">FromDate</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <?php 
                                $FromDate = date('01/m/Y');
                                echo render_date_input('FromDate','',$FromDate); ?>
                            </div>
                            <input type="hidden" name="FdateHidden" id="FdateHidden" value="<?php echo $FromDate; ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 No-Padding">
                                <label for="number">ToDate</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <?php 
                                $date = new DateTime('now');
                                $date->modify('last day of this month');
                                $ToDate = $date->format('d/m/Y');
                                //$ToDate = date('d/m/Y');
                                echo render_date_input('ToDate','',$ToDate); ?>
                            </div>
                            <input type="hidden" name="TdateHidden" id="TdateHidden" value="<?php echo $ToDate; ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4  No-Padding">
                                <label for="number">Discount%</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <input type="text" name="Discper" id="Discper" class="form-control Discper" value="">
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-2 ">
                        <div class="row">
                            <div class="col-md-4 ">
                                <label for="number">TranDate</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <?php 
                                $TransDate = date('d/m/Y');
                                echo render_date_input('TransDate','',$TransDate); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 ">
                                <label for="number">State</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <?php echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), '','',array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 ">
                                <label for="number">LocType</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <div class="form-group">
                                    <select name="loc_type" id="loc_type" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
                                        <option value="1">Local</option>
                                        <option value="2">OutStation</option>
                                        <option value="3">NotDefined</option>
                                    </select>
                                </div>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 ">
                                <label for="number">OrderBy</label>
                            </div>
                            <div class="col-md-8 No-Padding">
                                <div class="form-group">
                                    <select name="order_by" id="order_by" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
                                        <option value="1">Station Name</option>
                                        <option value="2">Account Name</option>
                                    </select>
                                </div>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    <div class="col-md-5">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                <div class='fixTableHead '>
                                    <table id="itemdivision" class="table-striped table-bordered daily_report" width="100%">
                                    <thead>
                                        <tr>
                                            <th style="padding:5px;"><input id="All" name="All" type="checkbox" value="true" onclick="toggle(this);"><input name="All" type="hidden" value="false"> &nbsp;All
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="itemgroup_body" style="display:grid;grid-template-columns: 4fr 4fr 4fr;">
                                    <!--<?php
                                        foreach($groups as $Key => $value)
                                        { ?>
                                            <tr>
                                            <td style="border:none !important;">
                                            <input id="<?php echo $value['id'];?>" name="chk" class="chk" type="checkbox" value="<?php echo $value['id'];?>">
                                            </td>
                                            <td style="border:none !important;">
                                            <label for="<?php echo $value['name'];?>" style="font-size:11px;"><?php echo $value['name'];?></label>
                                            </td>
                                            </tr>
                                    <?php   
                                        }
                                    ?>-->
                                    </tbody>
                                </table>
                                </div>
                                
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="height: 60px !important;">
                                    <textarea name='narration' id="narration" class='form-control'></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <?php if (has_permission_new('SplDisc', '', 'view')) { ?>
                                <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;padding:8px 15px;">Show</button>
                                <!--<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export</span></a>-->
                                <button class="btn btn-default pull-left mleft5 " id="caexcel" style="font-size:12px;padding:8px 15px; display:none;">Export</button>
                                <button class="btn btn-default pull-left mleft5 " id="caexcel2" style="font-size:12px;padding:8px 15px; display:none;">Export</button>
                            <!--<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>-->
                                <?php } ?>
                                <?php if (has_permission_new('SplDisc', '', 'create') || has_permission_new('SplDisc', '', 'edit')) { ?>
                                <button class="btn btn-default pull-left mleft5 save_data" id="save_data" style="font-size:12px;padding:8px 15px;display:none;">Post</button>
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                            
                </div>
                
                <div class="row">
                <div class="col-md-8">
                    <div class="searchh11" id="searchh11" style="display:none;">Please wait fetching data...</div>
                    <div class="searchh111" id="searchh111" style="display:none;">Please wait Exporting data...</div>
                    <div class="searchPost" id="searchPost" style="display:none;">Please wait processing your request...</div>
                    <div class="fixTableHead2 load_data">
              
                    </div>
                </div>
                </div>
                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
                <!-- Account Head List Model-->
            
                <div class="modal fade Disc_List" id="Disc_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Discount List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Disc_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-Disc_List tableFixHead2" id="Disc_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">DiscountID </th>
                                            <th style="text-align:left;">TransDate</th>
                                            <th style="text-align:left;">Disc. From</th>
                                            <th style="text-align:left;">Disc. To</th>
                                            <th style="text-align:left;">Disc.</th>
                                            <th style="text-align:left;">Disc. Amt</th>
                                            <th style="text-align:left;">State</th>
                                            <th style="text-align:left;">Loc Type</th>
                                            <th style="text-align:left;">Narration</th>
                                            <th style="text-align:left;">UserID</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($DiscountList as $key => $value) {
                                    ?>
                                        <tr class="get_DiscountID" data-id="<?php echo $value["DiscountID"]; ?>">
                                            <td><?php echo $value['DiscountID'];?></td>
                                            <td><?php echo _d(substr($value['Transdate'],0,10));?></td>
                                            <td><?php echo _d(substr($value["TransdateFrom"],0,10));?></td>
                                            <td><?php echo _d(substr($value["TransdateTo"],0,10));?></td>
                                            <td><?php echo $value["DiscPerc"];?></td>
                                            <td><?php echo $value['DiscAmt'];?></td>
                                            <td><?php echo $value['state_name'];?></td>
                                            <?if($value['LocationTypeID'] == "1"){
                                                $locType = 'Local';
                                            }else if($value['LocationTypeID'] == "2"){
                                                $locType = 'OutStation';
                                            }else{
                                                $locType = 'notdefine';
                                            }
                                            ?>
                                            <td><?php echo $locType;?></td>
                                            <td><?php echo $value["Narration"];?></td>
                                            <td><?php echo $value["UserID"];?></td>
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
<?php init_tail(); ?>
<style>


#Disc_List td:hover {
    cursor: pointer;
}
#Disc_List tr:hover {
    background-color: #ccc;
}

    .table-Disc_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Disc_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Disc_List tbody th { position: sticky; left: 0; }
    table  { border-collapse: collapse; width: 100%; }
    th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
    th     { background: #50607b;
    color: #fff !important; }
</style>
<script>
$(document).ready(function(){
    
    var FromDate = $("#FromDate").val();
	var ToDate = $("#ToDate").val();
	
	get_item_group(FromDate,ToDate);
	
	function get_item_group(FromDate,ToDate)
    {
    $.ajax({
      url:"<?php echo admin_url(); ?>SplDisc/GetSaleItemGroup",
      dataType:"JSON",
      method:"POST",
      data:{FromDate:FromDate,ToDate:ToDate},
      beforeSend: function () {
        $('.itemgroup_body').html('<tr><td style="width:100px;"> please wait item group loading...</td></tr>');
      },
      complete: function () {
        
      },
      success:function(data){
          var html = '';
          if(data.length === 0){
            html += '<tr>';
            html += '<td style="border-bottom:none;width:100px;"> Item Group not available...</td></tr>';
            $('.itemgroup_body').html(html);
        }else{
            for(var count = 0; count < data.length; count++)
            {
                
            html += '<tr>';
            html += '<td style="border:none !important;">';
            html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" >';
            html += '</td>';
            html += '<td style="border:none !important;">';
            html += '<label for="'+data[count].name+'" style="font-size:11px;">'+data[count].name+'</label>';
            html += '</td>';
            html += '</tr>';
            }
            toggle(true);
            $('.itemgroup_body').html(html);
        }
        }
    });
    }
    
    $('#FromDate').on('change',function(){
        var FromDate = $("#FromDate").val();
	    var ToDate = $("#ToDate").val();
	    get_item_group(FromDate,ToDate);
    });
 
    $('#ToDate').on('change',function(){
        var FromDate = $("#FromDate").val();
	    var ToDate = $("#ToDate").val();
	    get_item_group(FromDate,ToDate);
    });
	
    $("#DiscID").dblclick(function(){
            $('#Disc_List').modal('show');
            $('#Disc_List').on('shown.bs.modal', function () {
              $('#myInput1').focus();
        })
    });    
    
    $('.search_data').on('click',function(){
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var TransDate = $('#TransDate').val();
        var Discper = $('#Discper').val();
        var states = $('#states').val();
        var loc_type = $('#loc_type').val();
        var order_by = $('#order_by').val();
        
        var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
        
        var ItemGroupArray = new Array();
        var i = 1;
        $.each($("input[name='chk']:checked"), function(){
            var val = $(this).val();
            ItemGroupArray.push(val);
        });
        var ItemGroupSerializedArr = JSON.stringify(ItemGroupArray);
        if(ItemGroupSerializedArr == '[]'){
            alert('please select ItemGroup');
            $('.chk').focus();
        }else{
            if(Discper == ''){
                alert('please enter discount percentage');
            $('#Discper').focus();
            }else{
                $('#narration').val(narration);
                $.ajax({
                    url:"<?php echo admin_url(); ?>SplDisc/ShowResult",
                    dataType:"JSON",
                    method:"POST",
                    data:{FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,Discper:Discper,states:states,loc_type:loc_type,ItemGroupSerializedArr:ItemGroupSerializedArr,order_by:order_by},
                    beforeSend: function () {
                    $('.searchh11').css('display','block');
                    $('.searchh11').css('color','blue');
                    $('.fixTableHead2').css('display','none');
                    },
                    complete: function () {
                    $('.searchh11').css('display','none');
                    $('.fixTableHead2').css('display','block');
                    },
                    success:function(data){
                        $("#caexcel").css('display','none');
                        $("#caexcel2").css('display','block');
                        $("#save_data").css('display','block');
                        $('.load_data').html(data);
                    }
                });
            }
        }
    });
    $('.get_DiscountID').on('click',function(){ 
            DiscountID = $(this).attr("data-id");
            order_by = $('#order_by').val();
            $.ajax({
                url:"<?php echo admin_url(); ?>SplDisc/GetDiscDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{DiscountID:DiscountID,order_by:order_by},
                beforeSend: function () {
                $('.searchh11').css('display','block');
                $('.searchh11').css('color','blue');
                $('.fixTableHead2').css('display','none');
                },
                complete: function () {
                $('.searchh11').css('display','none');
                $('.fixTableHead2').css('display','block');
                },
                success:function(data){
                    $("#caexcel").css('display','block');
                    $("#caexcel2").css('display','none');
                    $("#save_data").css('display','block');
                    $('#DiscID').val(data.DiscountID);
                    $('#Discper').val(data.DiscPerc);
                    $('#narration').val(data.Narration);
                    
                    var date = data.Transdate.substring(0, 10)
                    var date_new = date.split("-").reverse().join("/");
                    $('#TransDate').val(date_new);
                    
                    var date2 = data.TransdateFrom.substring(0, 10)
                    var date_new2 = date2.split("-").reverse().join("/");
                    $('#FromDate').val(date_new2);
                    
                    var date3 = data.TransdateTo.substring(0, 10)
                    var date_new3 = date3.split("-").reverse().join("/");
                    $('#ToDate').val(date_new3);
                    
                    //get_item_group(date_new2,date_new3);
                    $('select[name=states]').val(data.StateID);
                    $('.selectpicker').selectpicker('refresh');
                    
                    $('select[name=loc_type]').val(data.LocationTypeID);
                    $('.selectpicker').selectpicker('refresh');
                    
                    html = '';
                        if(data.GetSaleItemGroup.length === 0){
                            html += '<tr>';
                            html += '<td style="border-bottom:none;width:100px;"> Item Group not available...</td></tr>';
                            $('.itemgroup_body').html(html);
                        }else{
                            for(var count = 0; count < data.GetSaleItemGroup.length; count++)
                            {
                                
                            html += '<tr>';
                            html += '<td style="border:none !important;">';
                            html += '<input id="'+data.GetSaleItemGroup[count].id+'" name="chk" class="chk" type="checkbox" value="'+data.GetSaleItemGroup[count].id+'" >';
                            html += '</td>';
                            html += '<td style="border:none !important;">';
                            html += '<label for="'+data.GetSaleItemGroup[count].name+'" style="font-size:11px;">'+data.GetSaleItemGroup[count].name+'</label>';
                            html += '</td>';
                            html += '</tr>';
                            }
                            toggle(true);
                            $('.itemgroup_body').html(html);
                        }
                    
                    $.each(data.DiscItem, function (column, value) {
                        var ID = value['ItemGroupID'];
                        $('#'+ID+'').prop('checked', true);
                    })
                    $('.load_data').html(data.DiscLedger);
                }
            });
            $('#Disc_List').modal('hide');
        });
    
    // Empty and open create mode
        $("#DiscID").focus(function(){
            $("#caexcel").css('display','none');
            $("#caexcel2").css('display','none');
            $("#save_data").css('display','none');
            $('#Discper').val('');
            $('#narration').val('');
            var DiscIDHidden = $('#DiscIDHidden').val();
            $('#DiscID').val(DiscIDHidden);
            
            var FrDate = $('#FdateHidden').val();
            $('#FromDate').val(FrDate);
            
            var ToDate = $('#TdateHidden').val();
            $('#ToDate').val(ToDate);
            
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy;
            $('#TransDate').val(today);
            
            $('select[name=states]').val('');
            $('.selectpicker').selectpicker('refresh');
            
            $('select[name=loc_type]').val('1');
            $('.selectpicker').selectpicker('refresh');
            
            $('.fixTableHead2').html('');
            $('.chk').prop('checked', false);
            get_item_group(FrDate,ToDate);
            
        });
        
        // On Blur ItemID Get All Date
        $('#DiscID').blur(function(){ 
            DiscountID = $(this).val();
            order_by = $('#order_by').val();
            $('#states').focus();
            if(DiscountID == ''){
                
            }else{
                $.ajax({
                url:"<?php echo admin_url(); ?>SplDisc/GetDiscDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{DiscountID:DiscountID,order_by:order_by},
                beforeSend: function () {
                $('.searchh11').css('display','block');
                $('.searchh11').css('color','blue');
                $('.fixTableHead2').css('display','none');
                },
                complete: function () {
                $('.searchh11').css('display','none');
                $('.fixTableHead2').css('display','block');
                },
                success:function(data){
                    init_selectpicker();
                    if(data == null){
                        //alert("DiscountID not found...");
                        $("#save_data").css('display','none');
                        $('#Discper').val('');
                        $('#narration').val('');
                        var DiscIDHidden = $('#DiscIDHidden').val();
                        $('#DiscID').val(DiscIDHidden);
                        
                        var FrDate = $('#FdateHidden').val();
                        $('#FromDate').val(FrDate);
                        
                        var ToDate = $('#TdateHidden').val();
                        $('#ToDate').val(ToDate);
                        
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                        $('#TransDate').val(today);
                        
                        $('select[name=states]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=loc_type]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=order_by]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('.chk').prop('checked', false);
                    }else{
                        $("#caexcel").css('display','block');
                        $("#caexcel2").css('display','none');
                        $("#save_data").css('display','block');
                        $('#DiscID').val(data.DiscountID);
                        $('#Discper').val(data.DiscPerc);
                        $('#narration').val(data.Narration);
                        
                        var date = data.Transdate.substring(0, 10)
                        var date_new = date.split("-").reverse().join("/");
                        $('#TransDate').val(date_new);
                        
                        var date2 = data.TransdateFrom.substring(0, 10)
                        var date_new2 = date2.split("-").reverse().join("/");
                        $('#FromDate').val(date_new2);
                        
                        var date3 = data.TransdateTo.substring(0, 10)
                        var date_new3 = date3.split("-").reverse().join("/");
                        $('#ToDate').val(date_new3);
                        //get_item_group(date_new2,date_new3);
                        $('select[name=states]').val(data.StateID);
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=loc_type]').val(data.LocationTypeID);
                        $('.selectpicker').selectpicker('refresh');
                        html = '';
                        if(data.GetSaleItemGroup.length === 0){
                            html += '<tr>';
                            html += '<td style="border-bottom:none;width:100px;"> Item Group not available...</td></tr>';
                            $('.itemgroup_body').html(html);
                        }else{
                            for(var count = 0; count < data.GetSaleItemGroup.length; count++)
                            {
                                
                            html += '<tr>';
                            html += '<td style="border:none !important;">';
                            html += '<input id="'+data.GetSaleItemGroup[count].id+'" name="chk" class="chk" type="checkbox" value="'+data.GetSaleItemGroup[count].id+'" >';
                            html += '</td>';
                            html += '<td style="border:none !important;">';
                            html += '<label for="'+data.GetSaleItemGroup[count].name+'" style="font-size:11px;">'+data.GetSaleItemGroup[count].name+'</label>';
                            html += '</td>';
                            html += '</tr>';
                            }
                            toggle(true);
                            $('.itemgroup_body').html(html);
                        }
                        
                        $.each(data.DiscItem, function (column, value) {
                            var ID = value['ItemGroupID'];
                            $('#'+ID+'').prop('checked', true);
                        })
                        $('.load_data').html(data.DiscLedger);
                    } 
                }
            });
            }
        });
    // Add or Update Data 
    $('#save_data').on('click',function(){
        var DiscID = $('#DiscID').val();
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var TransDate = $('#TransDate').val();
        var Discper = $('#Discper').val();
        var states = $('#states').val();
        var loc_type = $('#loc_type').val();
        var order_by = $('#order_by').val();
        
        //var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
        var narration = $('#narration').val();
        
        var ItemGroupArray = new Array();
        var i = 1;
        $.each($("input[name='chk']:checked"), function(){
            var val = $(this).val();
            ItemGroupArray.push(val);
        });
        var ItemGroupSerializedArr = JSON.stringify(ItemGroupArray);
        if(ItemGroupSerializedArr == '[]'){
            alert('please select ItemGroup');
            $('.chk').focus();
        }else{
            if(Discper == ''){
                alert('please enter discount percentage');
            $('#Discper').focus();
            }else{
                $('#narration').val(narration);
                $.ajax({
                    url:"<?php echo admin_url(); ?>SplDisc/SaveResult",
                    dataType:"JSON",
                    method:"POST",
                    data:{DiscID:DiscID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,Discper:Discper,states:states,loc_type:loc_type,ItemGroupSerializedArr:ItemGroupSerializedArr,order_by:order_by,narration:narration},
                    beforeSend: function () {
                    $('.searchPost').css('display','block');
                    $('.searchPost').css('color','blue');
                    },
                    complete: function () {
                    $('.searchPost').css('display','none');
                    },
                    success:function(data){
                        $('#Discper').val('');
                        $('#narration').val('');
                        $("#save_data").css('display','none');
                        if(data == true){
                            alert('Discount Updated Successfully...');
                            var DiscIDHidden = $('#DiscIDHidden').val();
                            $('#DiscID').val(DiscIDHidden);
                        }else{
                            alert('Discount Created Successfully...');
                            $('#DiscIDHidden').val(data);
                            $('#DiscID').val(data);
                        }
                        var FrDate = $('#FdateHidden').val();
                        $('#FromDate').val(FrDate);
                        var ToDate = $('#TdateHidden').val();
                        $('#ToDate').val(ToDate);
                        
                        var today = new Date();
                        var dd = String(today.getDate()).padStart(2, '0');
                        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        var yyyy = today.getFullYear();
                        today = dd + '/' + mm + '/' + yyyy;
                        $('#TransDate').val(today);
                        
                        $('select[name=states]').val('');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=loc_type]').val('1');
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('.fixTableHead2').html('');
                        $('.chk').prop('checked', false);
                        get_item_group(FrDate,ToDate);
                    }
                });
            }
        }
    })
    
    $("#caexcel").click(function(){
        DiscountID = $("#DiscID").val();
        order_by = $('#order_by').val();
	    $.ajax({
          url:"<?php echo admin_url(); ?>SplDisc/ExportGetDiscDetailByID",
          method:"POST",
          data:{DiscountID:DiscountID,order_by:order_by},
          beforeSend: function () {
            $('.searchh111').css('display','block');
            $('.searchh111').css('color','blue');
         },
          complete: function () {
            $('.searchh111').css('display','none');
         },
        success:function(data){
            response = JSON.parse(data);
            window.location.href = response.site_url+response.filename;
        }
        });
    });
    
    
    $("#caexcel2").click(function(){
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var TransDate = $('#TransDate').val();
        var Discper = $('#Discper').val();
        var states = $('#states').val();
        var loc_type = $('#loc_type').val();
        var order_by = $('#order_by').val();
        
        var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
        
        var ItemGroupArray = new Array();
        var i = 1;
        $.each($("input[name='chk']:checked"), function(){
            var val = $(this).val();
            ItemGroupArray.push(val);
        });
        var ItemGroupSerializedArr = JSON.stringify(ItemGroupArray);
        if(ItemGroupSerializedArr == '[]'){
            alert('please select ItemGroup');
            $('.chk').focus();
        }else{
            if(Discper == ''){
                alert('please enter discount percentage');
            $('#Discper').focus();
            }else{
                $('#narration').val(narration);
                $.ajax({
                    url:"<?php echo admin_url(); ?>SplDisc/ExportResult",
                    method:"POST",
                    data:{FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,Discper:Discper,states:states,loc_type:loc_type,ItemGroupSerializedArr:ItemGroupSerializedArr,order_by:order_by},
                    beforeSend: function () {
                        $('.searchh111').css('display','block');
                        $('.searchh111').css('color','blue');
                    },
                      complete: function () {
                        $('.searchh111').css('display','none');
                    },
                    success:function(data){
                        response = JSON.parse(data);
                        window.location.href = response.site_url+response.filename;
                    }
                });
            }
        }
        
        
    });
})
</script>
<script type="text/javascript">
   $('#Discper').on('keypress',function (event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2 )) {
        event.preventDefault();
    }
});
</script>
<script>
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>
</body>
</html>