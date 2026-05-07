<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
 
    .fixTableHead2 {
      overflow-y: auto;
      max-height: 450px;
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
    
    #Scheme_List td:hover {
    cursor: pointer;
}
#Scheme_List tr:hover {
    background-color: #ccc;
}

#Item_List td:hover {
    cursor: pointer;
}
#Item_List tr:hover {
    background-color: #ccc;
}

    .table-Scheme_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Scheme_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Scheme_List tbody th { position: sticky; left: 0; }
     table  { border-collapse: collapse; width: 100%; }
     th { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
     .data_table>tbody>tr>td { padding: 0px 0px !important; white-space: nowrap; border:0.1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
     .table-Item_List>tbody>tr>td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
     .table-Scheme_List>tbody>tr>td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
     .table-Show_List>tbody>tr>td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
     th     { background: #50607b;
    color: #fff !important; }
    
    .table-Item_List          { overflow: auto;max-height: 65vh;width:100%;position:relative;top: 0px; }
    .table-Item_List thead th { position: sticky; top: 0; z-index: 1; }
    .table-Item_List tbody th { position: sticky; left: 0; }
    
    .Addbtn:hover {background-color: #3e8e41}
    .Addbtn:visited {background-color: #3e8e41}
    .Addbtn:active {background-color: #3e8e41}
 
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
                            $new_DiscNumber = get_option('next_scheme_number_for_cspl');
                        }elseif($selected_company == 2){
                            $new_DiscNumber = get_option('next_scheme_number_for_cff');
                        }elseif($selected_company == 3){
                            $new_DiscNumber = get_option('next_scheme_number_for_cbu');
                        }
                        $format = get_option('invoice_number_format');
                        $prefix = "SCH".$fy;
                        $_new_DiscNumber = str_pad($new_DiscNumber, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                    ?>
                    <div class="col-md-12">
                        
                        <div class="searchh22" style="display:none;">Please wait Create new SplDisc...</div>
                        <div class="searchh33" style="display:none;">Please wait update SplDisc...</div>
                    </div>
                    
                    <div class="col-md-3 ">
                        <div class="row">
                            <div class="col-md-3 ">
                                <label for="number">SchemeID</label>
                            </div>
                            <div class="col-md-9 ">
                                <div class="form-group">
                                    <input type="hidden" name="SchemeApprove" id="SchemeApprove" class="form-control" value="">
                                    <input type="hidden" name="ApproveIMG" id="ApproveIMG" class="form-control SchemeID" value="">
                                    <input type="text" name="SchemeID" id="SchemeID" class="form-control SchemeID" value="<?php echo $prefix.$_new_DiscNumber; ?>">
                                    <input type="hidden" name="SchemeIDHidden" id="SchemeIDHidden" value="<?php echo $prefix.$_new_DiscNumber; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 ">
                                <label for="number">TransDate</label>
                            </div>
                            <div class="col-md-9 ">
                                <?php 
                                $TransDate = date('d/m/Y');
                                $attr = array(
                                    "disabled"=>"disabled"
                                    );
                                echo render_date_input('TransDate','',$TransDate,$attr); ?>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3 ">
                                <label for="number">State</label>
                            </div>
                            <div class="col-md-9">
                                <?php echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), '','UP',array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                            </div>
                            
                        </div>
                    </div>
                    <div class="col-md-3 ">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="number">FromDate</label>
                            </div>
                            <div class="col-md-9">
                                <?php 
                                $FromDate = date('01/m/Y');
                                echo render_date_input('FromDate','',$TransDate); ?>
                            </div>
                            <input type="hidden" name="FdateHidden" id="FdateHidden" value="<?php echo $TransDate; ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <label for="number">ToDate</label>
                            </div>
                            <div class="col-md-9">
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
                            <div class="col-md-3 ">
                                <label for="number">Dist.Type</label>
                            </div>
                            <div class="col-md-9">
                                <?php
                                    $selected_company = $this->session->userdata('root_company');
                                    if($selected_company == '1'){
                                        $distType = '1';
                                    }else if($selected_company == '3'){
                                        $distType = '21';
                                    }
                                    
                                ?>
                                <input type="hidden" name="client_typeHIdden" id="client_typeHIdden" value="<?php echo $distType; ?>">
                            <?php echo render_select('client_type',$groups,array('id','name'),'',$distType); ?>
                        </div>
                        </div>
                        
                    </div>
                    
                    <div class="col-md-4">
                        <div class="row">
                            
                            <div class="col-md-12">
                                
                                <?php if (has_permission_new('SchemeMaster', '', 'create')) { ?>
                                    <button class="btn btn-info pull-left mleft5 save_data" id="save_data" style="font-size:12px;padding:8px 15px;">Save</button>
                                <?php }else{ ?>
                                    <button class="btn btn-info pull-left mleft5 save_data" disabled id="save_data" style="font-size:12px;padding:8px 15px;">Save</button>
                                <?php } ?>
                                <?php if (has_permission_new('SchemeMaster', '', 'edit')) { ?>
                                    <button class="btn btn-info pull-left mleft5 update_data" id="update_data" style="font-size:12px;padding:8px 15px;">Update</button>
                                <?php }else{ ?>
                                    <button class="btn btn-info pull-left mleft5 update_data" disabled id="update_data1" style="font-size:12px;padding:8px 15px;">Update</button>
                                    
                                <?php } ?>
                                <?php if (has_permission_new('SchemeMaster', '', 'create') || has_permission_new('SchemeMaster', '', 'edit')) { ?>
                                    <button class="btn btn-default pull-left mleft5 show_data" id="show_data" style="font-size:12px;padding:8px 15px;">Show</button>
                                    <button class="btn btn-default pull-left mleft5 Exportshow_data" id="Exportshow_data" style="font-size:12px;padding:8px 15px;">Export</button>
                                <?php } ?>
                                
                                <?php
                                    $staff_user_id = $this->session->userdata('staff_user_id');
                                    if($staff_user_id == "3" || $staff_user_id == "4" || $staff_user_id == "1"){ ?>
                                        <button class="btn btn-info pull-left mleft5 Approve_data" id="Approve_data" style="font-size:12px;padding:8px 15px;">Approve</button>
                                <?php }
                                ?>
                                
                            </div>
                            <?php if (has_permission_new('SchemeMaster', '', 'edit')) { ?>
                                <form method="post" id="upload_form" align="center" enctype="multipart/form-data"> 
                                <div class="col-md-6">
                                    <br>
                                        <input type="hidden" name="EditSchemeID" id="EditSchemeID">
                                        <input type="file" name="image_file" id="image_file" />
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <input type="submit" name="upload" id="upload" value="Upload" class="btn btn-info" />  
                                </div>
                            </form>
                            <?php } ?>
                            <div class="col-md-12">
                                <br>
                                <div class="form-group" style="height: 60px !important;">
                                    <textarea name='narration' id="narration" class='form-control'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <div id="uploaded_image">  
                        </div>  
                    </div>
                </div>
                
                
                <div class="row">
                    <div class="col-md-6">
                        <!--<span style="color:red">Note : Discount amount show excluding gst</span>-->
                        <div class="searchh11" id="searchh11" style="display:none;">Please wait fetching data...</div>
                        <div class="savedata" id="savedata" style="display:none;">Please wait data saving...</div>
                        <div class="updatedata" id="updatedata" style="display:none;">Please wait data updating...</div>
                        <div class="showdata" id="showdata" style="display:none;">Please wait data loading...</div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="col-md-7">
                        <input type="hidden" name="count" id="count" value="100">
                        <table class="table table-striped table-bordered data_table" id="data_table" width="100%">
                        <thead>
                            <tr> 
								<th style="width:10%">ItemID</th>
                                <th style="width:43%">ItemName</th>
                                <th style="width:5%">Pack</th>
                                <th style="width:5%">Basic Rate</th>
								<th style="width:8%">SlabCases</th>
								<th style="width:8%">SlabAmt</th>
								<th style="width:8%">Disc%</th>
								<th style="width:8%">UnitDisc</th>
								<th style="width:10%">A</th>
								<!--<th>Action</th>-->
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <?php
                                for($i = 1;$i<=100;$i++){
                                ?>
                                    <tr id="R<?php echo $i; ?>">
                                        
                                        <td style="width:10%"><input type="text" name="ItemID<?php echo $i; ?>" id="ItemID<?php echo $i; ?>" value="" style="height: 30px;width: 100%;" onchange="ItemAddEdit(<?php echo $i; ?>)" ondblclick="OpenPopUp(<?php echo $i; ?>)"></td>
                                        <td style="width:43%"><input type="text" name="ItemNameHidden<?php echo $i; ?>" id="ItemNameHidden<?php echo $i; ?>" value="" readonly style="height: 30px;width: 100%;"></td>
                                        <td style="width:5%"><input type="text" name="packQtyHidden<?php echo $i; ?>" id="packQtyHidden<?php echo $i; ?>" value="" readonly style="height: 30px;width: 100%;"></td>
                                        <td style="width:5%"></span><input type="text" name="rateHidden<?php echo $i; ?>" id="rateHidden<?php echo $i; ?>" value="" readonly style="height: 30px;width: 100%;"></td>
                                        <td style="width:8%"><input type="text" name="SlabCases<?php echo $i; ?>" id="SlabCases<?php echo $i; ?>" class="SlabCases" value="" style="height: 30px;width: 100%;" onkeypress=" return isNumber(event)" ></td>
                                        <td style="width:8%"><input type="text" name="SlabAmt<?php echo $i; ?>" id="SlabAmt<?php echo $i; ?>" class="SlabAmt" value="" style="height: 30px;width: 100%;" onchange="SaleAmtAddEdit(<?php echo $i; ?>)"></td>
                                        <td style="width:8%"><input type="text" name="Disc<?php echo $i; ?>" id="Disc<?php echo $i; ?>" class="Disc" value="" style="height: 30px;width: 100%;" onchange="DiscAddEdit(<?php echo $i; ?>)"></td>
                                        <td style="width:8%"><input type="text" name="UnitDisc<?php echo $i; ?>" id="UnitDisc<?php echo $i; ?>" value="" readonly style="height: 30px;width: 100%;"></td>
                                        <td style="width:5%"><input type="hidden" name="oldstatus<?php echo $i; ?>" id="oldstatus<?php echo $i; ?>" value=""><input type="text" name="status<?php echo $i; ?>" id="status<?php echo $i; ?>" value="" style="height: 30px;width: 100%; text-transform:uppercase" onchange="AllowYN(<?php echo $i; ?>)"></td>
                                        <!--<td><button type="button" name="addBtn" id="Addbtn" class="btn btn-xs btn-succes Addbtn" value="Add"><i class="fa fa-plus-circle " style="font-size:20px;"></i></button></td>-->
                                    </tr>
                                <?php
                                }
                            ?>
                            
                        </tbody>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <div class="fixTableHead2 load_data">
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
        
        <div class="clearfix"></div>
        
        <!-- Item List Model -->
            
                <div class="modal fade Item_List" id="Item_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Items List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Item_List tableFixHead2">
                                <input type="hidden" name="RowID" id="RowID" value="">
                                <table class="tree table table-striped table-bordered table-Item_List tableFixHead2" id="Item_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th style="text-align:left;">ItemID </th>
                                            <th style="text-align:left;">ItemName</th>
                                            <th style="text-align:left;">ItemGroup</th>
                                            <th style="text-align:left;">ItemDiv</th>
                                            <th style="text-align:left;">HSN</th>
                                            <th style="text-align:left;">GST%</th>
                                            <th style="text-align:left;">BowlQty</th>
                                            <th style="text-align:left;">CaseQty</th>
                                            <th style="text-align:left;">CrateQty</th>
                                            <th style="text-align:left;">Weight</th>
                                            <th style="text-align:left;">MinQty</th>
                                            <th style="text-align:left;">MinDay</th>
                                            <th style="text-align:left;">StkMon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($ItemList as $key => $value) {
                                    ?>
                                        <tr class="GetItemID" data-id="<?php echo $value["item_code"]; ?>">
                                            <td><?php echo $value['item_code'];?></td>
                                            <td><?php echo $value['description'];?></td>
                                            <td><?php echo $value["subgroup_id"];?></td>
                                            <td><?php echo $value["subgroup_id"];?></td>
                                            <td><?php echo $value["hsn_code"];?></td>
                                            <td><?php echo $value['tax'];?></td>
                                            <td><?php echo $value['bowl_qty'];?></td>
                                            <td><?php echo $value["case_qty"];?></td>
                                            <td><?php echo $value["crate_qty"];?></td>
                                            <td><?php echo $value["case_weight"];?></td>
                                            <td><?php echo $value["min_qty"];?></td>
                                            <td><?php echo $value["min_day"];?></td>
                                            <td><?php echo $value["monitorstock"];?></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>   
                            </div>
                        </div>
                        <div class="modal-footer" style="padding:0px;">
                            <input type="text" id="ItemSearch" onkeyup="ItemSearch()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
                        </div>
                        </div>
                    <!-- /.modal-content -->
                    </div>
                <!-- /.modal-dialog -->
                </div>
            <!-- /.modal -->
                <!-- Account Head List Model-->
            
                <div class="modal fade Scheme_List" id="Scheme_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                        <div class="modal-header" style="padding:5px 10px;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Scheme List</h4>
                        </div>
                        <div class="modal-body" style="padding:0px 5px !important">
                            
                            <div class="table-Scheme_List tableFixHead2">
                                <table class="tree table table-striped table-bordered table-Scheme_List tableFixHead2" id="Scheme_List" width="100%">
                                    <thead>
                                        <tr style="display:none;">
                                            <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Item Master</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                        </tr>
                                        <tr>
                                            <th id="sl" style="text-align:left;">SchemeID </th>
                                            <th style="text-align:left;">EntryDate</th>
                                            <th style="text-align:left;">StartDate</th>
                                            <th style="text-align:left;">EndDate</th>
                                            <th style="text-align:left;">Dist.Type</th>
                                            <th style="text-align:left;">State</th>
                                            <th style="text-align:left;">Remark</th>
                                            <th style="text-align:left;">Image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    foreach ($SchemeList as $key => $value) {
                                    ?>
                                        <tr >
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo $value['SchemeID'];?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo _d(substr($value['TransDate'],0,10));?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo _d(substr($value["StartDate"],0,10));?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo _d(substr($value["EndDate"],0,10));?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo $value["name"];?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo $value['state_name'];?></td>
                                            <td class="GetSchemeID" data-id="<?php echo $value["SchemeID"]; ?>"><?php echo $value['narration'];?></td>
                                    <?php
                                            $link = site_url().'uploads/staff_profile_images/'.$value['SchemeID'].'/small_'.$value['file_name'];
                                            $link2 = site_url().'uploads/staff_profile_images/'.$value['SchemeID'].'/thumb_'.$value['file_name'];
                                    ?>
                                            <td><a target='_blank' href='<?php echo $link2; ?>' ><i class="fa fa-view"></i>View Image<!--<img style = "width:110px;height:70px;" src='<?php echo $link; ?>' />--></a></td>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>   
                            </div>
                        </div>
                        <div class="modal-footer" style="padding:0px;">
                            <input type="text" id="SchemeSearchInput" onkeyup="SchemeSearch()" placeholder="Search.." title="Type in a name" style="float: left;width: 100%;">
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

<script>
    $(document).ready(function(){
        $('#ToDate').on('change',function(){
            var FromDate = $("#FromDate").val();
            var ToDate = $("#ToDate").val();
            var ToDateHidden = $("#TdateHidden").val();
            var date1 = FromDate.split("/").reverse().join("-");
            var date2 = ToDate.split("/").reverse().join("-");
            let date11 = new Date(date1).getTime();
            let date22 = new Date(date2).getTime();
            if(date22 < date11){
                alert('ToDate must be greater than or equal to FromDate');
                $("#ToDate").val(ToDateHidden);
            }
        })
        
        $('#FromDate').on('change',function(){
            var FromDate = $("#FromDate").val();
            var ToDate = $("#ToDate").val();
            var FdateHidden = $("#FdateHidden").val();
            var date1 = FromDate.split("/").reverse().join("-");
            var date2 = ToDate.split("/").reverse().join("-");
            let date11 = new Date(date1).getTime();
            let date22 = new Date(date2).getTime();
            
            var TransDate = $('#TransDate').val();
            var date3 = TransDate.split("/").reverse().join("-");
            let date33 = new Date(date3).getTime();
            
            if(date11 > date22){
                alert('FromDate must be less than or equal to ToDate');
                $("#FromDate").val(FdateHidden);
            }
            if(date11 < date33){
                alert('FromDate must be greater than or equal to current Date');
                $("#FromDate").val(FdateHidden);
            }
        })
        
        $('#submit').submit(function(e){
        e.preventDefault(); 
            $.ajax({
                url:"<?php echo admin_url(); ?>SchemeMaster/do_upload",
                type:"POST",
                data:new FormData(this),
                processData:false,
                contentType:false,
                cache:false,
                async:false,
                success: function(data){
                    alert(data);
                    //alert("Upload Image Successful.");
                }
            });
        });
        
        $('#upload_form').on('submit', function(e){  
           e.preventDefault();  
           if($('#image_file').val() == '')  
           {  
                alert("Please Select the File");  
           }  
           else  
           {  
                $.ajax({  
                     url:"<?php echo admin_url(); ?>SchemeMaster/ajax_upload",
                     method:"POST",  
                     data:new FormData(this),  
                     contentType: false,  
                     cache: false,  
                     processData:false,  
                     success:function(data)  
                     {  
                         if(data ==""){
                             
                         }else{
                            alert("File uploaded successfully."); 
                            $('#uploaded_image').html(data); 
                            $('#ApproveIMG').val("Y");
                         }
                        
                           
                     }  
                });  
           }  
      }); 
    });
</script>
<script>
    function isNumber(evt)
	{
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true; 
	}
	
    $('#SlabAmt').on('keypress',function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
            event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3 )) {
            event.preventDefault();
        }
    });
    $('#Disc').on('keypress',function (event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 45 || event.which > 57)) {
            event.preventDefault();
        }
        var input = $(this).val();
        if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 3 )) {
            event.preventDefault();
        }
    });
    
    $("#Addbtn").click(function(){
            add_row();
    });
        
    function add_row()
    {  
        //alert('hello');
        var ItemID = $('#ItemID').val();
        var ItemNameHidden = $('#ItemNameHidden').val();
        var packQtyHidden = $('#packQtyHidden').val();
        var rateHidden = $('#rateHidden').val();
        var SlabCases = $('#SlabCases').val();
        var SlabAmt = $('#SlabAmt').val();
        var Disc = $('#Disc').val();
        var UnitDisc = $('#UnitDisc').val();
        var status = $('#status').val().toUpperCase();
        
        var table = document.getElementById("data_table");
        var table_len =(table.rows.length)-1;
        var html = '';
        html += "<tr id='row"+table_len+"'>";
        html += "<td id='ItemID"+table_len+"'>"+ItemID+" <input type='hidden' name='ItemIDHidden"+table_len+"' id='ItemIDHidden"+table_len+"' value='"+ItemID+"'></td>";
        html += "<td id='ItemName"+table_len+"'>"+ItemNameHidden+" <input type='hidden' name='ItemNameHidden"+table_len+"' id='ItemNameHidden"+table_len+"' value='"+ItemNameHidden+"'></td>";
        html += "<td id='packQty"+table_len+"'>"+packQtyHidden+" <input type='hidden' name='packQtyHidden"+table_len+"' id='packQtyHidden"+table_len+"' value='"+packQtyHidden+"'></td>";
        html += "<td id='rate"+table_len+"'>"+rateHidden+" <input type='hidden' name='rateHidden"+table_len+"' id='rateHidden"+table_len+"' value='"+rateHidden+"'></td>";
        html += "<td id='SlabCases"+table_len+"'>"+SlabCases+" <input type='hidden' name='SlabCasesHidden"+table_len+"' id='SlabCasesHidden"+table_len+"' value='"+SlabCases+"'></td>";
        html += "<td id='SlabAmt"+table_len+"'>"+SlabAmt+" <input type='hidden' name='SlabAmtHidden"+table_len+"' id='SlabAmtHidden"+table_len+"' value='"+SlabAmt+"'></td>";
        html += "<td id='Disc"+table_len+"'>"+Disc+" <input type='hidden' name='DiscHidden"+table_len+"' id='DiscHidden"+table_len+"' value='"+Disc+"'></td>";
        html += "<td id='UnitDisc"+table_len+"'>"+UnitDisc+" <input type='hidden' name='UnitDiscHidden"+table_len+"' id='UnitDiscHidden"+table_len+"' value='"+UnitDisc+"'></td>";
        html += "<td id='status"+table_len+"'>"+status+" <input type='hidden' name='statusHidden"+table_len+"' id='statusHidden"+table_len+"' value='"+status+"'></td>";
        html += '<td><button type="button" name="edit" id="remove" class="btn btn-xs btn-danger remove" value="remove"><i class="fa fa-trash " style="font-size:16px;"></i></button><input type="hidden" name="rownum" id="rownum" value="'+table_len+'"></td>';
        html += '</tr>';
        
        var row = table.insertRow(table_len).outerHTML=html;
        var Count = $('#count').val();
        var NewCount = parseInt(Count) + 1;
        $("#count").val(NewCount);
        $('#ItemID').val('');
        $('#ItemNameHidden').val('');
        $('#packQtyHidden').val('');
        $('#rateHidden').val('');
        $('#SlabCases').val('');
        $('#SlabAmt').val('');
        $('#Disc').val('');
        $('#UnitDisc').val('');
        $('#status').val('');
        
        $('#ItemName').html('');
        $('#packQty').html('');
        $('#rate').html('');
        
        $('#ItemID').focus();
    }
    
</script>
<script>

    function OpenPopUp(rowid){
        $('#Item_List').modal('show');
        $('#Item_List').on('shown.bs.modal', function () {
            $('#RowID').val(rowid);
            $('#ItemSearch').focus();
        })
    }
    function DiscAddEdit(rowid){
        var Disc = $('#Disc'+rowid).val();
        if(isNaN(Disc) || Disc == ""){
               
        }else{
            var Rate = $('#rateHidden'+rowid).val();
            var DiscUnit = (parseFloat(Rate) / 100 ) * parseFloat(Disc);
            $('#UnitDisc'+rowid).val(DiscUnit.toFixed(4));
            
            var SchemeApprove = $("#SchemeApprove").val();
            if(SchemeApprove == ""){
                $('#status'+rowid).val('Y');
                $('#oldstatus'+rowid).val('Y');
            }
            $("#SlabAmt"+rowid).val("");
        }
    }
    function SaleAmtAddEdit(rowid){
        var SlabAmt = $("#SlabAmt"+rowid).val();
        if(isNaN(SlabAmt) || SlabAmt == ""){
        }else{
            var slabCases = $('#SlabCases'+rowid).val();
            var Pack = $('#packQtyHidden'+rowid).val();
            var Rate = $('#rateHidden'+rowid).val();
            var DiscUnit = parseFloat(SlabAmt) / parseFloat(Pack);
            $('#UnitDisc'+rowid).val(DiscUnit.toFixed(4));
            $("#Disc"+rowid).val("");
            var SchemeApprove = $("#SchemeApprove").val();
            if(SchemeApprove == ""){
                $('#status'+rowid).val('Y');
                $('#oldstatus'+rowid).val('Y');
            }
        }
    }
    
    function AllowYN(rowid){
        
        var Char = $("#status"+rowid).val().toUpperCase();
        var ApproveIMG = $("#ApproveIMG").val();
        if(ApproveIMG == "Y"){
            oldstatus = $("#oldstatus"+rowid).val();
            if(oldstatus == "N"){
                alert("This Item is permently lock, Can't active again...");
                $("#status"+rowid).val("N")
            }
        }
        if((Char !== "N") && Char !== "Y"){
            if(ApproveIMG == ""){
                $('#oldstatus'+rowid).val('Y');
            }
            $('#status'+rowid).val('Y');
            alert('Only allow Y or N ');
        }
        
    }

    function ItemAddEdit(rowid){
        ItemID = $('#ItemID'+rowid).val();
            State = $('#states').val();
            DistType = $('#client_type').val();
            FromDate = $('#FromDate').val();
            ToDate = $('#ToDate').val();
            var ApproveIMG = $("#ApproveIMG").val();
            var SchemeApprove = $("#SchemeApprove").val();
            if(ItemID == "" ){
                $('#ItemID'+rowid).val('');
                $('#ItemNameHidden'+rowid).val('');
                $('#packQtyHidden'+rowid).val('');
                $('#rateHidden'+rowid).val('');
                $('#SlabCases'+rowid).val('');
                $('#SlabAmt'+rowid).val('');
                $('#Disc'+rowid).val('');
                $('#UnitDisc'+rowid).val('');
                $('#status'+rowid).val('');
                $('#oldstatus'+rowid).val('');
            }else{
                if(State == ""){
                    alert('please select state');
                }else{
                    if(DistType == ""){
                        alert('please select distributor type');
                    }else{
                        if(SchemeApprove == ""){
                            $.ajax({
                                url:"<?php echo admin_url(); ?>SchemeMaster/GetItemDetailByItemID",
                                dataType:"JSON",
                                method:"POST",
                                data:{ItemID:ItemID,State:State,DistType:DistType,FromDate:FromDate,ToDate:ToDate},
                                beforeSend: function () {
                                
                                },
                                complete: function () {
                                
                                },
                                success:function(data){
                                    if(data == null){
                                        alert('Item not found');
                                        $('#ItemID'+rowid).val('');
                                        $('#ItemNameHidden'+rowid).val('');
                                        $('#packQtyHidden'+rowid).val('');
                                        $('#rateHidden'+rowid).val('');
                                        $('#SlabCases'+rowid).val('');
                                        $('#SlabAmt'+rowid).val('');
                                        $('#Disc'+rowid).val('');
                                        $('#UnitDisc'+rowid).val('');
                                        $('#status'+rowid).val('');
                                        $('#oldstatus'+rowid).val('');
                                    }else{
                                        if(data == false){
                                            alert('This ItemID is already used in other scheme');
                                        }else{
                                            if(data.assigned_rate == "" || data.assigned_rate == null){
                                                alert('rate not assigned');
                                                $('#ItemID').focus();
                                            }else{
                                                //var SaleRate = (parseFloat(data.assigned_rate)) + ((parseFloat(data.assigned_rate) * data.taxrate) / 100);
                                                $('#ItemID'+rowid).val(data.item_code);
                                                $('#ItemNameHidden'+rowid).val(data.description);
                                                $('#packQtyHidden'+rowid).val(data.case_qty);
                                                $('#rateHidden'+rowid).val(data.assigned_rate);
                                                $('#SlabCases'+rowid).focus();
                                            }
                                        }
                                    }
                                }
                            });
                        }else{
                            alert('Scheme Approved, Not allowed to add new Item..');
                            $('#ItemID'+rowid).val('');
                        }
                    }
                }
            }  
    }
</script>
<script>
    $(document).ready(function(){
        var rowIdx = 1;
        $('#update_data').hide();
        $('#update_data1').hide();
        $('#upload_form').hide();
        $('#uploaded_image').html('');
        $('#ApproveIMG').val('');
        $('#Approve_data').hide();
        $('#save_data').show();
        
        $("#SchemeID").dblclick(function(){
            $('#Scheme_List').modal('show');
            $('#Scheme_List').on('shown.bs.modal', function () {
                $('#SchemeSearchInput').focus();
            })
        });
        
        $('.GetItemID').on('click',function(){ 
            ItemID = $(this).attr("data-id");
            State = $('#states').val();
            DistType = $('#client_type').val();
            FromDate = $('#FromDate').val();
            ToDate = $('#ToDate').val();
            if(State == ""){
                alert('please select state');
                $('#Item_List').modal('hide');
            }else{
                if(DistType == ""){
                    alert('please select Distributor type');
                    $('#Item_List').modal('hide');
                }else{
                    $.ajax({
                        url:"<?php echo admin_url(); ?>SchemeMaster/GetItemDetailByItemID",
                        dataType:"JSON",
                        method:"POST",
                        data:{ItemID:ItemID,State:State,DistType:DistType,FromDate:FromDate,ToDate:ToDate},
                        beforeSend: function () {
                        
                        },
                        complete: function () {
                        
                        },
                        success:function(data){
                            if(data.SaleRate == "" || data.SaleRate == null){
                                alert('rate not assigned');
                                $('#ItemID').focus();
                            }else{
                                if(data == false){
                                    alert('This ItemID is already used in other scheme');
                                }else{
                                    if(data.assigned_rate == "" || data.assigned_rate == null){
                                        alert('rate not assigned');
                                        $('#ItemID').focus();
                                    }else{
                                        var RowID = $("#RowID").val();
                                        var SchemeApprove = $("#SchemeApprove").val();
                                        if(SchemeApprove == ""){
                                            //var SaleRate = (parseFloat(data.assigned_rate)) + ((parseFloat(data.assigned_rate) * data.taxrate) / 100);
                                            $('#ItemID'+RowID).val(data.item_code);
                                            $('#ItemNameHidden'+RowID).val(data.description);
                                            $('#packQtyHidden'+RowID).val(data.case_qty);
                                            $('#rateHidden'+RowID).val(data.assigned_rate);
                                                $('#SlabCases'+RowID).focus();
                                        }else{
                                            alert('Scheme Approved, Not allowed to add new Item..');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }
            $('#Item_List').modal('hide');
        });
        
        // Add Data 
        $('#save_data').on('click',function(){
            var SchemeID = $('#SchemeID').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var TransDate = $('#TransDate').val();
            var states = $('#states').val();
            var client_type = $('#client_type').val();
            var narration = $('#narration').val();;
            var ItemCount = $("#count").val();
            var ItemArray = new Array();
            for (i=1;i<=ItemCount;i++) {
                var id= 'ItemID'+i;
                var ItemID = document.getElementById(id).value;
                
                var id2= 'ItemNameHidden'+i;
                var ItemName = document.getElementById(id2).value;
                
                var id3= 'packQtyHidden'+i;
                var packQty = document.getElementById(id3).value;
                
                var id4= 'rateHidden'+i;
                var rate = document.getElementById(id4).value;
                
                var id5= 'SlabCases'+i;
                var SlabCases = document.getElementById(id5).value;
                
                var id6= 'SlabAmt'+i;
                var SlabAmt = document.getElementById(id6).value;
                
                var id7= 'Disc'+i;
                var Disc = document.getElementById(id7).value;
                
                var id8= 'UnitDisc'+i;
                var UnitDisc = document.getElementById(id8).value;
                
                var id9= 'status'+i;
                var status = document.getElementById(id9).value;
                
                var ii = i - 1;
                    ItemArray[ii]=new Array();
                    ItemArray[ii][0]=ItemID;
                    ItemArray[ii][1]=ItemName;
                    ItemArray[ii][2]=packQty;
                    ItemArray[ii][3]=rate;
                    ItemArray[ii][4]=SlabCases;
                    ItemArray[ii][5]=SlabAmt;
                    ItemArray[ii][6]=Disc;
                    ItemArray[ii][7]=UnitDisc;
                    ItemArray[ii][8]=status;
            }
            var ItemSerializedArr = JSON.stringify(ItemArray);
        var date1 = FromDate.split("/").reverse().join("-");
        var date2 = TransDate.split("/").reverse().join("-");
        let date11 = new Date(date1).getTime();
        let date22 = new Date(date2).getTime();
       
        if(ItemSerializedArr == '[]'){
            alert('please select atleast one item');
            $('#ItemID').focus();
        }else{
            if(states == '' && client_type == ''){
                alert('please enter state and distributor type');
                $('#states').focus();
            }else{
                if(narration == "" || narration == " "){
                    alert('please enter remark of scheme');
                    $('#narration').focus();
                }else{
                    if(date11 >= date22){
                        $.ajax({
                            url:"<?php echo admin_url(); ?>SchemeMaster/SaveScheme",
                            dataType:"JSON",
                            method:"POST",
                            data:{SchemeID:SchemeID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,states:states,client_type:client_type,ItemSerializedArr:ItemSerializedArr,narration:narration},
                            beforeSend: function () {
                                $('.savedata').css('display','block');
                                $('.savedata').css('color','blue');
                            },
                            complete: function () {
                                $('.savedata').css('display','none');
                            },
                            success:function(data){
                                if(data == false){
                                    alert('Something went wrong');
                                    $('.load_data').html('');
                                }else{
                                    var TotalRow = $("#count").val();
                                    var crRow = parseInt(TotalRow);
                                    for (var A = 1; A <= crRow; A++) {
                                        $('#ItemID'+A).val('');
                                        $('#ItemNameHidden'+A).val('');
                                        $('#packQtyHidden'+A).val('');
                                        $('#rateHidden'+A).val('');
                                        $('#SlabCases'+A).val('');
                                        $('#SlabAmt'+A).val('');
                                        $('#Disc'+A).val('');
                                        $('#UnitDisc'+A).val('');
                                        $('#status'+A).val('');
                                        $('#oldstatus'+A).val('');
                                    }
                                    $('#SchemeID').val(data);
                                    $('#SchemeIDHidden').val(data);
                                    $('.load_data').html('');
                                    $('#narration').val('');
                                    alert('Record created successfully...');
                                }
                            }
                        });
                    }else{
                        
                        alert('Please select From date is greter than or equal to current date..');
                        $('#FromDate').focus();
                    }  
                }
            }
        }
    })
    
    // Update Data 
        $('#update_data').on('click',function(){
            var SchemeID = $('#SchemeID').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var TransDate = $('#TransDate').val();
            var states = $('#states').val();
            var client_type = $('#client_type').val();
            var narration = $('#narration').val();
            var ItemCount = $("#count").val();
            var ItemArray = new Array();
            for (i=1;i<=ItemCount;i++) {
                var id= 'ItemID'+i;
                var ItemID = document.getElementById(id).value;
                if(ItemID == ""){
                    
                }else{
                    var id2= 'ItemNameHidden'+i;
                    var ItemName = document.getElementById(id2).value;
                    
                    var id3= 'packQtyHidden'+i;
                    var packQty = document.getElementById(id3).value;
                    
                    var id4= 'rateHidden'+i;
                    var rate = document.getElementById(id4).value;
                    
                    var id5= 'SlabCases'+i;
                    var SlabCases = document.getElementById(id5).value;
                    
                    var id6= 'SlabAmt'+i;
                    var SlabAmt = document.getElementById(id6).value;
                    
                    var id7= 'Disc'+i;
                    var Disc = document.getElementById(id7).value;
                    
                    var id8= 'UnitDisc'+i;
                    var UnitDisc = document.getElementById(id8).value;
                    
                    var id9= 'status'+i;
                    var status = document.getElementById(id9).value;
                    
                    var ii = i - 1;
                        ItemArray[ii]=new Array();
                        ItemArray[ii][0]=ItemID;
                        ItemArray[ii][1]=ItemName;
                        ItemArray[ii][2]=packQty;
                        ItemArray[ii][3]=rate;
                        ItemArray[ii][4]=SlabCases;
                        ItemArray[ii][5]=SlabAmt;
                        ItemArray[ii][6]=Disc;
                        ItemArray[ii][7]=UnitDisc;
                        ItemArray[ii][8]=status;
                }
            }
            var ItemSerializedArr = JSON.stringify(ItemArray);
        var date1 = FromDate.split("/").reverse().join("-");
        var date2 = TransDate.split("/").reverse().join("-");
        let date11 = new Date(date1).getTime();
        let date22 = new Date(date2).getTime();
        if(ItemSerializedArr == '[]'){
            alert('please select atleast one item');
            $('#ItemID').focus();
        }else{
            if(states == '' && client_type == ''){
                alert('please enter state and distributor type');
                $('#states').focus();
            }else{
                if(narration == "" || narration == " "){
                    alert('please enter remark');
                    $('#narration').focus();
                }else{
                    if(date11 >= date22){
                        $.ajax({
                            url:"<?php echo admin_url(); ?>SchemeMaster/UpdateScheme",
                            dataType:"JSON",
                            method:"POST",
                            data:{SchemeID:SchemeID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,states:states,client_type:client_type,ItemSerializedArr:ItemSerializedArr,narration:narration},
                            beforeSend: function () {
                                $('.updatedata').css('display','block');
                                $('.updatedata').css('color','blue');
                            },
                            complete: function () {
                                $('.updatedata').css('display','none');
                            },
                            success:function(data){
                                if(data == false){
                                    alert('Something went wrong');
                                    $('.load_data').html('');
                                }else{
                                    var SchemeIDHidden = $('#SchemeIDHidden').val();
                                    var client_typeHIdden = $('#client_typeHIdden').val();
                                    var FdateHidden = $('#FdateHidden').val();
                                    var TdateHidden = $('#TdateHidden').val();
                                    $('#SchemeID').val(SchemeIDHidden);
                                    $('#FromDate').val(FdateHidden);
                                    $('#ToDate').val(TdateHidden);
                                    $('#narration').val('');
                                    $('select[name=client_type]').val(client_typeHIdden);
                                    $('.selectpicker').selectpicker('refresh');
                                    $('select[name=states]').val('UP');
                                    $('.selectpicker').selectpicker('refresh');
                                    var TotalRow = $("#count").val();
                                    var crRow = parseInt(TotalRow);
                                    for (var A = 1; A <= crRow; A++) {
                                        $('#ItemID'+A).val('');
                                        $('#ItemNameHidden'+A).val('');
                                        $('#packQtyHidden'+A).val('');
                                        $('#rateHidden'+A).val('');
                                        $('#SlabCases'+A).val('');
                                        $('#SlabAmt'+A).val('');
                                        $('#Disc'+A).val('');
                                        $('#UnitDisc'+A).val('');
                                        $('#status'+A).val('');
                                        $('#oldstatus'+A).val('');
                                    }
                                    $('#update_data').hide();
                                    $('#update_data1').hide();
                                    $('#upload_form').hide();
                                    $('#uploaded_image').html('');
                                    $('#ApproveIMG').val('');
                                    $('#Approve_data').hide();
                                    $('#save_data').show();
                                    alert('Record updated successfully...');
                                    $('.load_data').html('');
                                }
                            }
                        });
                    }else{
                        alert('Please select From date is greter than or equal to current date..');
                        $('#FromDate').focus();
                    }
                        
                }
            }
        }
    })
    
    // Approved Data 
        $('#Approve_data').on('click',function(){
            var SchemeID = $('#SchemeID').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var TransDate = $('#TransDate').val();
            var states = $('#states').val();
            var client_type = $('#client_type').val();
            var ApproveI = $('#ApproveIMG').val();
        //var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
            var ItemCount = $("#count").val();
            var ItemArray = new Array();
            for (i=1;i<=ItemCount;i++) {
                var id= 'ItemID'+i;
                var ItemID = document.getElementById(id).value;
                if(ItemID == ""){
                    
                }else{
                    var id2= 'ItemNameHidden'+i;
                    var ItemName = document.getElementById(id2).value;
                    
                    var id3= 'packQtyHidden'+i;
                    var packQty = document.getElementById(id3).value;
                    
                    var id4= 'rateHidden'+i;
                    var rate = document.getElementById(id4).value;
                    
                    var id5= 'SlabCases'+i;
                    var SlabCases = document.getElementById(id5).value;
                    
                    var id6= 'SlabAmt'+i;
                    var SlabAmt = document.getElementById(id6).value;
                    
                    var id7= 'Disc'+i;
                    var Disc = document.getElementById(id7).value;
                    
                    var id8= 'UnitDisc'+i;
                    var UnitDisc = document.getElementById(id8).value;
                    
                    var id9= 'status'+i;
                    var status = document.getElementById(id9).value;
                    
                    var ii = i - 1;
                        ItemArray[ii]=new Array();
                        ItemArray[ii][0]=ItemID;
                        ItemArray[ii][1]=ItemName;
                        ItemArray[ii][2]=packQty;
                        ItemArray[ii][3]=rate;
                        ItemArray[ii][4]=SlabCases;
                        ItemArray[ii][5]=SlabAmt;
                        ItemArray[ii][6]=Disc;
                        ItemArray[ii][7]=UnitDisc;
                        ItemArray[ii][8]=status;
                }
            }
            var ItemSerializedArr = JSON.stringify(ItemArray);
        
        if(ItemSerializedArr == '[]'){
            alert('please select atleast one item');
            $('#ItemID').focus();
        }else{
            if(states == '' && client_type == ''){
                alert('please enter state and distributor type');
                $('#states').focus();
            }else{
                if(ApproveI == "Y"){
                    if (confirm("Are you sure to approve scheme")) {
                      $.ajax({
                        url:"<?php echo admin_url(); ?>SchemeMaster/ApproveScheme",
                        dataType:"JSON",
                        method:"POST",
                        data:{SchemeID:SchemeID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,states:states,client_type:client_type,ItemSerializedArr:ItemSerializedArr},
                        beforeSend: function () {
                            $('.updatedata').css('display','block');
                            $('.updatedata').css('color','blue');
                        },
                        complete: function () {
                            $('.updatedata').css('display','none');
                        },
                        success:function(data){
                            if(data == false){
                                alert('Something went wrong');
                                $('.load_data').html('');
                            }else{
                                var SchemeIDHidden = $('#SchemeIDHidden').val();
                                var client_typeHIdden = $('#client_typeHIdden').val();
                                var FdateHidden = $('#FdateHidden').val();
                                var TdateHidden = $('#TdateHidden').val();
                                $('#SchemeID').val(SchemeIDHidden);
                                $('#FromDate').val(FdateHidden);
                                $('#ToDate').val(TdateHidden);
                                $('select[name=client_type]').val(client_typeHIdden);
                                $('.selectpicker').selectpicker('refresh');
                                $('select[name=states]').val('UP');
                                $('.selectpicker').selectpicker('refresh');
                                $('#narration').val('');
                                var TotalRow = $("#count").val();
                                var crRow = parseInt(TotalRow);
                                for (var A = 1; A <= crRow; A++) {
                                    $('#ItemID'+A).val('');
                                    $('#ItemNameHidden'+A).val('');
                                    $('#packQtyHidden'+A).val('');
                                    $('#rateHidden'+A).val('');
                                    $('#SlabCases'+A).val('');
                                    $('#SlabAmt'+A).val('');
                                    $('#Disc'+A).val('');
                                    $('#UnitDisc'+A).val('');
                                    $('#status'+A).val('');
                                    $('#oldstatus'+A).val('');
                                }
                                $('#update_data').hide();
                                $('#update_data1').hide();
                                $('#upload_form').hide();
                                $('#uploaded_image').html('');
                                $('#ApproveIMG').val('');
                                $('#Approve_data').hide();
                                $('#save_data').show();
                                alert('Approve Scheme successfully...');
                                $('.load_data').html('');
                            }
                        }
                    });
                } else {
                  //alert('Not Ok');
                }
                }else{
                    alert("Please upload certificate");
                }
                    
            }
        }
    })
    
    // Show Data 
        $('#show_data').on('click',function(){
            var SchemeID = $('#SchemeID').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var TransDate = $('#TransDate').val();
            var states = $('#states').val();
            var client_type = $('#client_type').val();
        //var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
            var ItemCount = $("#count").val();
            var ItemArray = new Array();
            for (i=1;i<=ItemCount;i++) {
                var id= 'ItemID'+i;
                var ItemID = document.getElementById(id).value;
                if(ItemID == ""){
                    
                }else{
                    var id2= 'ItemNameHidden'+i;
                    var ItemName = document.getElementById(id2).value;
                    
                    var id3= 'packQtyHidden'+i;
                    var packQty = document.getElementById(id3).value;
                    
                    var id4= 'rateHidden'+i;
                    var rate = document.getElementById(id4).value;
                    
                    var id5= 'SlabCases'+i;
                    var SlabCases = document.getElementById(id5).value;
                    
                    var id6= 'SlabAmt'+i;
                    var SlabAmt = document.getElementById(id6).value;
                    
                    var id7= 'Disc'+i;
                    var Disc = document.getElementById(id7).value;
                    
                    var id8= 'UnitDisc'+i;
                    var UnitDisc = document.getElementById(id8).value;
                    
                    var id9= 'status'+i;
                    var status = document.getElementById(id9).value;
                    
                    var ii = i - 1;
                        ItemArray[ii]=new Array();
                        ItemArray[ii][0]=ItemID;
                        ItemArray[ii][1]=ItemName;
                        ItemArray[ii][2]=packQty;
                        ItemArray[ii][3]=rate;
                        ItemArray[ii][4]=SlabCases;
                        ItemArray[ii][5]=SlabAmt;
                        ItemArray[ii][6]=Disc;
                        ItemArray[ii][7]=UnitDisc;
                        ItemArray[ii][8]=status;
                }
            }
            var ItemSerializedArr = JSON.stringify(ItemArray);
        
        if(ItemSerializedArr == '[]'){
            alert('please select atleast one item');
            $('#ItemID').focus();
        }else{
            if(states == '' && client_type == ''){
                alert('please enter state and distributor type');
                $('#states').focus();
            }else{
                
                $.ajax({
                    url:"<?php echo admin_url(); ?>SchemeMaster/ShowSchemeAmt",
                    dataType:"JSON",
                    method:"POST",
                    data:{SchemeID:SchemeID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,states:states,client_type:client_type,ItemSerializedArr:ItemSerializedArr},
                    beforeSend: function () {
                        $('.showdata').css('display','block');
                        $('.showdata').css('color','blue');
                    },
                    complete: function () {
                        $('.showdata').css('display','none');
                    },
                    success:function(data){
                        if(data == null){
                            $(".load_data").html('No data found');
                        }else{
                            $('.load_data').html(data);
                        }
                    }
                });
            }
        }
    })
    
    // Show Data 
        $('#Exportshow_data').on('click',function(){
            var SchemeID = $('#SchemeID').val();
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var TransDate = $('#TransDate').val();
            var states = $('#states').val();
            var client_type = $('#client_type').val();
        //var narration = "SPECIAL DISCOUNT FROM  "+FromDate+ " TO " +ToDate;
            var ItemCount = $("#count").val();
            var ItemArray = new Array();
            for (i=1;i<=ItemCount;i++) {
                var id= 'ItemID'+i;
                var ItemID = document.getElementById(id).value;
                if(ItemID == ""){
                    
                }else{
                    var id2= 'ItemNameHidden'+i;
                    var ItemName = document.getElementById(id2).value;
                    
                    var id3= 'packQtyHidden'+i;
                    var packQty = document.getElementById(id3).value;
                    
                    var id4= 'rateHidden'+i;
                    var rate = document.getElementById(id4).value;
                    
                    var id5= 'SlabCases'+i;
                    var SlabCases = document.getElementById(id5).value;
                    
                    var id6= 'SlabAmt'+i;
                    var SlabAmt = document.getElementById(id6).value;
                    
                    var id7= 'Disc'+i;
                    var Disc = document.getElementById(id7).value;
                    
                    var id8= 'UnitDisc'+i;
                    var UnitDisc = document.getElementById(id8).value;
                    
                    var id9= 'status'+i;
                    var status = document.getElementById(id9).value;
                    
                    var ii = i - 1;
                        ItemArray[ii]=new Array();
                        ItemArray[ii][0]=ItemID;
                        ItemArray[ii][1]=ItemName;
                        ItemArray[ii][2]=packQty;
                        ItemArray[ii][3]=rate;
                        ItemArray[ii][4]=SlabCases;
                        ItemArray[ii][5]=SlabAmt;
                        ItemArray[ii][6]=Disc;
                        ItemArray[ii][7]=UnitDisc;
                        ItemArray[ii][8]=status;
                }
            }
            var ItemSerializedArr = JSON.stringify(ItemArray);
        
        if(ItemSerializedArr == '[]'){
            alert('please select atleast one item');
            $('#ItemID').focus();
        }else{
            if(states == '' && client_type == ''){
                alert('please enter state and distributor type');
                $('#states').focus();
            }else{
                
                $.ajax({
                    url:"<?php echo admin_url(); ?>SchemeMaster/ExportShowSchemeAmt",
                    
                    method:"POST",
                    data:{SchemeID:SchemeID,FromDate:FromDate,ToDate:ToDate,TransDate:TransDate,states:states,client_type:client_type,ItemSerializedArr:ItemSerializedArr},
                    beforeSend: function () {
                        $('.showdata').css('display','block');
                        $('.showdata').css('color','blue');
                    },
                    complete: function () {
                        $('.showdata').css('display','none');
                    },
                    success:function(data){
                        response = JSON.parse(data);
                        window.location.href = response.site_url+response.filename;
                    }
                });
            }
        }
    })
    
    $('.GetSchemeID').on('click',function(){ 
            SchemeID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>SchemeMaster/GetSchemeDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{SchemeID:SchemeID},
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
                    //$("#caexcel").css('display','block');
                    //$("#caexcel2").css('display','none');
                    $('#SchemeID').val(data.SchemeID);
                    $('#EditSchemeID').val(data.SchemeID);
                    var date = data.TransDate.substring(0, 10)
                    var date_new = date.split("-").reverse().join("/");
                    $('#TransDate').val(date_new);
                    
                    var date2 = data.StartDate.substring(0, 10)
                    var date_new2 = date2.split("-").reverse().join("/");
                    $('#FromDate').val(date_new2);
                    
                    var date3 = data.EndDate.substring(0, 10)
                    var date_new3 = date3.split("-").reverse().join("/");
                    $('#ToDate').val(date_new3);
                    $('#narration').val(data.narration);
                    //get_item_group(date_new2,date_new3);
                    $('select[name=states]').val(data.StateID);
                    $('.selectpicker').selectpicker('refresh');
                    
                    $('select[name=client_type]').val(data.DistributorType);
                    $('.selectpicker').selectpicker('refresh');
                    if(data.file_name == null){
                            $('#uploaded_image').html('');
                            $('#ApproveIMG').val('');
                        }else{
                            var link = '<?php echo site_url(); ?>'+'uploads/staff_profile_images/'+SchemeID+'/small_'+data.file_name;
                            var link2 = '<?php echo site_url(); ?>'+'uploads/staff_profile_images/'+SchemeID+'/thumb_'+data.file_name;
                            $('#uploaded_image').html("<a target='_blank' href='"+link2+"' ><img src='"+link+"' /></a>");
                            $('#ApproveIMG').val("Y");
                        }
                    
                    if(data.Approve == "Y"){
                        $('#SchemeApprove').val("Y");
                        $('#Approve_data').attr('disabled','disabled');
                        $('#FromDate').attr('disabled','disabled');
                        $('#ToDate').attr('disabled','disabled');
                        $('select[name=states]').attr('disabled','disabled');
                        $('select[name=client_type]').attr('disabled','disabled');
                        $('#narration').attr('disabled','disabled');
                        $('#upload_form').hide();
                    }else{
                        $('#SchemeApprove').val("");
                        $('#upload_form').show();
                    }
                    
                    for(var count = 0; count < data.Item.length; count++)
                        {
                            var row = parseInt(count) + 1;
                            $('#ItemID'+row).val(data.Item[count].ItemID); 
                            $('#ItemNameHidden'+row).val(data.Item[count].description); 
                            $('#packQtyHidden'+row).val(data.Item[count].CaseQty); 
                            $('#rateHidden'+row).val(data.Item[count].BasicRate); 
                            $('#SlabCases'+row).val(data.Item[count].SlabQty); 
                            $('#SlabAmt'+row).val(data.Item[count].SlabAmt); 
                            $('#Disc'+row).val(data.Item[count].DiscPerc); 
                            $('#UnitDisc'+row).val(data.Item[count].DiscAmt); 
                            $('#status'+row).val(data.Item[count].ActYN);
                            $('#oldstatus'+row).val(data.Item[count].ActYN);
                            if(data.Approve == "Y"){
                                    $('#ItemID'+row).attr('readonly','readonly');
                                    $('#SlabCases'+row).attr('readonly','readonly');
                                    $('#SlabAmt'+row).attr('readonly','readonly');
                                    $('#Disc'+row).attr('readonly','readonly');
                                }
                        }
                    $('#update_data').show();
                    $('#update_data1').show();
                    $('#Approve_data').show();
                    $('#save_data').hide();
                }
            });
            $('#Scheme_List').modal('hide');
        });
        
        $('#SchemeID').on('blur',function(){ 
            SchemeID = $(this).val();
            $.ajax({
                url:"<?php echo admin_url(); ?>SchemeMaster/GetSchemeDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{SchemeID:SchemeID},
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
                    if(data == null){
                        var SchemeIDHidden = $('#SchemeIDHidden').val();
                        var client_typeHIdden = $('#client_typeHIdden').val();
                        var FdateHidden = $('#FdateHidden').val();
                        var TdateHidden = $('#TdateHidden').val();
                        $('#SchemeID').val(SchemeIDHidden);
                        $('#EditSchemeID').val('');
                        $('#FromDate').val(FdateHidden);
                        $('#ToDate').val(TdateHidden);
                        $('select[name=client_type]').val(client_typeHIdden);
                        $('.selectpicker').selectpicker('refresh');
                        $('select[name=states]').val('UP');
                        $('.selectpicker').selectpicker('refresh');
                        $('#narration').val('');
                        var TotalRow = $("#count").val();
                        var crRow = parseInt(TotalRow);
                        for (var A = 1; A <= crRow; A++) {
                            $('#ItemID'+A).val('');
                            $('#ItemNameHidden'+A).val('');
                            $('#packQtyHidden'+A).val('');
                            $('#rateHidden'+A).val('');
                            $('#SlabCases'+A).val('');
                            $('#SlabAmt'+A).val('');
                            $('#Disc'+A).val('');
                            $('#UnitDisc'+A).val('');
                            $('#status'+A).val('');
                            $('#oldstatus'+A).val('');
                        }
                        $('#update_data').hide();
                        $('#update_data1').hide();
                        $('#upload_form').hide();
                        $('#uploaded_image').html('');
                        $('#ApproveIMG').val('');
                        $('#Approve_data').hide();
                        $('#save_data').show();
                        $('.load_data').html('');
                    }else{
                        $('#SchemeID').val(data.SchemeID);
                        $('#EditSchemeID').val(data.SchemeID);
                        var date = data.TransDate.substring(0, 10)
                        var date_new = date.split("-").reverse().join("/");
                        $('#TransDate').val(date_new);
                        
                        var date2 = data.StartDate.substring(0, 10)
                        var date_new2 = date2.split("-").reverse().join("/");
                        $('#FromDate').val(date_new2);
                        
                        var date3 = data.EndDate.substring(0, 10)
                        var date_new3 = date3.split("-").reverse().join("/");
                        $('#ToDate').val(date_new3);
                        
                        $('select[name=states]').val(data.StateID);
                        $('.selectpicker').selectpicker('refresh');
                        
                        $('select[name=client_type]').val(data.DistributorType);
                        $('.selectpicker').selectpicker('refresh');
                        $('#narration').val(data.narration);
                        if(data.file_name == null){
                            $('#uploaded_image').html('');
                            $('#ApproveIMG').val('');
                        }else{
                            var link = '<?php echo site_url(); ?>'+'uploads/staff_profile_images/'+SchemeID+'/small_'+data.file_name;
                            var link2 = '<?php echo site_url(); ?>'+'uploads/staff_profile_images/'+SchemeID+'/thumb_'+data.file_name;
                            $('#uploaded_image').html("<a target='_blank' href='"+link2+"' ><img src='"+link+"' /></a>");
                            $('#ApproveIMG').val('Y');
                        }
                        if(data.Approve == "Y"){
                            $('#SchemeApprove').val("Y");
                            $('#Approve_data').attr('disabled','disabled');
                            $('#FromDate').attr('disabled','disabled');
                            $('#ToDate').attr('disabled','disabled');
                            $('select[name=states]').attr('disabled','disabled');
                            $('select[name=client_type]').attr('disabled','disabled');
                            $('#narration').attr('disabled','disabled');
                            $('#upload_form').hide();
                        }else{
                            $('#SchemeApprove').val("");
                            $('#upload_form').show();
                        }
                        for(var count = 0; count < data.Item.length; count++)
                            {
                                var row = parseInt(count) + 1;
                                $('#ItemID'+row).val(data.Item[count].ItemID); 
                                $('#ItemNameHidden'+row).val(data.Item[count].description); 
                                $('#packQtyHidden'+row).val(data.Item[count].CaseQty); 
                                $('#rateHidden'+row).val(data.Item[count].BasicRate); 
                                $('#SlabCases'+row).val(data.Item[count].SlabQty); 
                                $('#SlabAmt'+row).val(data.Item[count].SlabAmt); 
                                $('#Disc'+row).val(data.Item[count].DiscPerc); 
                                $('#UnitDisc'+row).val(data.Item[count].DiscAmt); 
                                $('#status'+row).val(data.Item[count].ActYN); 
                                $('#oldstatus'+row).val(data.Item[count].ActYN); 
                                if(data.Approve == "Y"){
                                    $('#ItemID'+row).attr('readonly','readonly');
                                    $('#SlabCases'+row).attr('readonly','readonly');
                                    $('#SlabAmt'+row).attr('readonly','readonly');
                                    $('#Disc'+row).attr('readonly','readonly');
                                }
                            }
                        $('#update_data').show();
                        $('#update_data1').show();
                        $('#Approve_data').show();
                        $('#save_data').hide();
                        $('.load_data').html('');
                    }
                }
            });
            //$('#Scheme_List').modal('hide');
        });
        
        $("#SchemeID").focus(function(){
            var SchemeIDHidden = $('#SchemeIDHidden').val();
            var client_typeHIdden = $('#client_typeHIdden').val();
            var FdateHidden = $('#FdateHidden').val();
            var TdateHidden = $('#TdateHidden').val();
            var date = moment();
            var CurDate = date.format('DD/MM/YYYY');
            $('#SchemeID').val(SchemeIDHidden);
            $('#EditSchemeID').val('');
            $('#FromDate').val(FdateHidden);
            $('#ToDate').val(TdateHidden);
            $('#TransDate').val(CurDate);
            $('select[name=client_type]').val(client_typeHIdden);
            $('.selectpicker').selectpicker('refresh');
            $('select[name=states]').val('UP');
            $('.selectpicker').selectpicker('refresh');
            $('#narration').val('');
            var TotalRow = $("#count").val();
            var crRow = parseInt(TotalRow);
            for (var A = 1; A <= crRow; A++) {
                $('#ItemID'+A).val('');
                $('#ItemNameHidden'+A).val('');
                $('#packQtyHidden'+A).val('');
                $('#rateHidden'+A).val('');
                $('#SlabCases'+A).val('');
                $('#SlabAmt'+A).val('');
                $('#Disc'+A).val('');
                $('#UnitDisc'+A).val('');
                $('#status'+A).val('');
                $('#oldstatus'+A).val('');
                $('#ItemID'+A).removeAttr('readonly','readonly');
                $('#SlabCases'+A).removeAttr('readonly','readonly');
                $('#SlabAmt'+A).removeAttr('readonly','readonly');
                $('#Disc'+A).removeAttr('readonly','readonly');
            }
            $('#Approve_data').removeAttr('disabled');
            $('#FromDate').removeAttr('disabled');
            $('#ToDate').removeAttr('disabled');
            $('select[name=states]').removeAttr('disabled');
            $('select[name=client_type]').removeAttr('disabled');
            $('#narration').removeAttr('disabled');
            $('#upload_form').hide();
            $('#uploaded_image').html('');
            $('#ApproveIMG').val('');
            $('#update_data').hide();
            $('#update_data1').hide();
            $('#Approve_data').hide();
            $('#save_data').show();
            $('.load_data').html('');
        })
});
    
</script>

<script type="text/javascript" language="javascript" >


function SchemeSearch() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("SchemeSearchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("Scheme_List");
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
}}
}
}

function ItemSearch() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("ItemSearch");
  filter = input.value.toUpperCase();
  table = document.getElementById("Item_List");
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
}}
}
}

function ShowSearch() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("ShowSearchInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("Show_List");
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
}}
}
}
</script>
<script>
$(document).ready(function(){
    
    var FromDate = $("#FromDate").val();
	var ToDate = $("#ToDate").val();
	
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
                        $('.load_data').html(data);
                    }
                });
            }
        }
    });
   
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
   $('.Disc,.SlabAmt').on('keypress',function (event) {
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