<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
.table-daily_report tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
           
     <?php hooks()->do_action('before_items_page_content'); ?>
     
      
      <?php if(has_permission_new('ratemaster','','view')){ ?>
      <div class="_buttons">
         <div class="col-md-3">
            <?php 
            echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
            ?>
         </div>
         <div class="col-md-3">
            <?php 
             echo render_select('distributor_id',$groups,array('id','name'),'customer_groups',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                ?>
         </div> 
      </div>
      
      <div class="col-md-6">
      
      <br>
       <div class="_buttons">
           <?php if(has_permission_new('ratemaster','','view')){ ?>
           <button class="btn btn-info pull-left mleft5 search_data" id="search_data">show</button>
           <?php } ?>
        <?php if(has_permission_new('ratemaster','','create')){ ?>
        <a href="<?php echo admin_url('rate_master/import'); ?>" class="btn btn-info pull-left mleft5"><?php echo _l('import_rate_master'); ?></a>
      <?php } ?>
      </div>
      
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    <?php } ?>
    <div class="col-md-6">
      
      <div class="custom_button">
            &nbsp;<a class="btn btn-default buttons-excel buttons-html5"    tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
            <a class="btn btn-default" href="javascript:void(0);"    onclick="printPage();">Print</a>
            </div>
      </div>
    <div class="col-md-6">
    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
    </div>
    <div class="table-daily_report tableFixHead2">
             
              <table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
                  
                <thead>
                 
                    <tr style="display:none;">
                      <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                  </tr>
                  <tr>
                    <th style="text-align:left;" id="sl">Item Code <span class="up_starting">  &#8595;</span><span class="up" style="display:none;"> &#8593;</span><span class="down" style="display:none;"> &#8595;</span></th>
                    <th style="text-align:left;">Item Name</th>
                    <th style="text-align:left;">Unit</th>
                    <th style="text-align:left;">Basic Rate</th>
                    <th style="text-align:left;">GST</th>
                    <th style="text-align:left;">Sale Rate</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>   
            </div>
            <span id="searchh2" style="display:none;">Loading.....</span>
             <span id="searchh3" style="display:none;">Loading.....</span>
    <?php
   /* $table_data = [];

    if(has_permission_new('ratemaster','','delete')) {
      //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
    }

    $table_data = array_merge($table_data, array(
      _l('invoice_items_list_code'),
      _l('invoice_items_list_description'),
      _l('unit'),
      
      'Basic Rate',
      'GST',
      'Sale Rate',
      ));

    
    render_datatable($table_data,'invoice-items'); */
    ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php $this->load->view('admin/rate_master/item'); ?>

<!-- Item Division Model -->

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('items','','create')){ ?>
          <div class="input-group">
            <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('new_item_group'); ?></button>
            </span>
          </div>
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('item_group_name'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission_new('items','','edit')){ ?>
                      <a href="#" class="edit-item-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Division -->

<!-- Item Main Group -->
<div class="modal fade" id="maingroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_main_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('items','','create')){ ?>
        <div class="row">
        <div class="col-md-4">
            
                <input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">
            
        </div>
        <div class="col-md-4">
            
            <input type="text" name="item_main_group_name" id="item_main_group_name" class="form-control" placeholder="<?php echo _l('item_main_group_name'); ?>">
            
          
        </div>
        
        <div class="col-md-4">
            <span class="btn" style="top: -7px;position: relative;">
              <button class="btn btn-info p7" type="button" id="new-item-main-group-insert"><?php echo _l('new_item_main_group'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('item_main_group_id'); ?></th>
                <th><?php echo _l('item_main_group'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_main_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['item_main_group_id']; ?>"><?php echo $group['item_main_group_id']; ?></td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="main_group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-main-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission_new('items','','edit')){ ?>
                      <a href="#" class="edit-item-main-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Main Group-->

<!-- Item Sub Group -->

<div class="modal fade" id="subgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_sub_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('items','','create')){ ?>
        <div class="row">
        <div class="col-md-4">
            
                <!--<input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">-->
            <?php 
            //print_r($items_main_groups);
            $s_attrs = array('data-none-selected-text'=>'Select Main Group');
                     $selected = '';
            echo render_select('item_main_group_id1',$items_main_groups,array('item_main_group_id','name'),'',$selected,$s_attrs); ?>
            <!--<select id="item_main_group_id1" name="item_main_group_id1" class="form-control">
                <?php 
                foreach ($items_main_groups as $key => $value) {
                        # code...
                        ?>
                    <option value="<?php echo $value['item_main_group_id'];?>"><?php echo $value["name"];?></option>
                        <?php
                    }
                ?>
                
            </select>-->
            
        </div>
        <div class="col-md-4">
            
            <input type="text" name="item_main_group_name" id="item_sub_group_name" class="form-control" placeholder="<?php echo _l('item_sub_group_name'); ?>">
            
          
        </div>
        
        <div class="col-md-4">
            <span class="btn" style="top: -7px;position: relative;">
              <button class="btn btn-info p7" type="button" id="new-item-sub-group-insert"><?php echo _l('new_item_sub_group'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="1" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('item_main_group_name'); ?></th>
                <th><?php echo _l('item_sub_group'); ?></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_sub_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['main_group_id']; ?>"><?php echo $group['main_group_id']; ?></td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="group_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="main_group_edit hide">
                     <div class="input-group">
                      <input type="text" class="form-control">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-main-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  <div class="row-options">
                    <?php if(has_permission_new('items','','edit')){ ?>
                      <a href="#" class="edit-item-main-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('items','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
  </div>
</div>
</div>
</div>

<!-- End Item Sub Group-->

<?php init_tail(); ?>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
 <script>
     function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table-daily_report");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
 </script>
<script>
    $(document).ready(function(){
 
  function load_data(state_id,distributor_id)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>rate_master/load_data",
      dataType:"json",
      method:"POST",
      data:{state_id:state_id, distributor_id:distributor_id},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table-daily_report tbody').css('display','none');
        
     },
      complete: function () {
                            
        $('.table-daily_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        //  data1 = JSON.parse(data);
           var msg = "Rate Master State: "+data.state.state_name +" Distributor " +data.distributor.name;
	    $(".report_for").text(msg);
        //   condole.log(data1.html);return false; 
           $('#table-daily_report tbody').append(data.html);
        // $('tbody').html(data);
      }
    });
  }
 $('#search_data').on('click',function(){
        var states = $("#states").val();
	    var distributor_id = $("#distributor_id").val();
	  
	//alert(customer_type);
	if (states.trim()!='' && distributor_id.trim()!=''){
	   
	   
        load_data(states,distributor_id);
      }else{
              alert("Please select State and Customer Type");
            }
        
 });

  
  
});
</script>
 <script>
 
$("#caexcel").click(function(){
    
	    var state_id = $("#states").val();
	    var distributor_id = $("#distributor_id").val();
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>rate_master/export_rate_master",
            method:"POST",
            data:{state_id:state_id, distributor_id:distributor_id},
            beforeSend: function () {
                $('#searchh3').css('display','block');
            },
            complete: function () {
                $('#searchh3').css('display','none');
            },
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
            }
        });
});

function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
</script>
<script type="text/javascript">
 function printPage(){
    var html_filter_name =    $('.report_for').html();
         var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">Rate Master </td>';
         heading_data += '</tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
         heading_data += '</tr>';
         
         heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
      function sortTable(f,n){
	var rows = $('#table-daily_report tbody  tr').get();

	rows.sort(function(a, b) {

		var A = getVal(a);
		var B = getVal(b);

		if(A < B) {
			return -1*f;
		}
		if(A > B) {
			return 1*f;
		}
		return 0;
	});

	function getVal(elm){
		var v = $(elm).children('td').eq(n).text().toUpperCase();
		if($.isNumeric(v)){
			v = parseInt(v,10);
		}
		return v;
	}

	$.each(rows, function(index, row) {
		$('#table-daily_report').children('tbody').append(row);
	});
}
var f_sl = 1;
var f_nm = 1;
$("#sl").click(function(){
      if ( $('.up').css('display') == 'none')
    {
         $(".up_starting").hide()
      $(".up").show()
      $(".down").hide()
    }else{
         $(".up_starting").hide()
        $(".up").hide()
      $(".down").show()
    }
    f_sl *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_sl,n);
});
$("#nm").click(function(){
    f_nm *= -1;
    var n = $(this).prevAll().length;
    sortTable(f_nm,n);
});
 </script>
</body>
</html>
