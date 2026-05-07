<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
.table-invoice-items tbody{
  display: block;
  max-height: 350px;
  overflow-y: scroll;
}
.table-invoice-items thead, .table-invoice-items tbody tr{
  display: table;
  table-layout: fixed;
  width: 100%;
}
.table-invoice-items thead{
  width: calc(100% - 1.1em);
}
.table-invoice-items thead{
  position: relative;
}
.table-invoice-items thead th:last-child:after{
  content: ' ';
  position: absolute;
  background-color: #337ab7;
  width: 1.3em;
  height: 38px;
  right: -1.3em;
  top: 0;
  border-bottom: 2px solid #ddd;
}
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php if(has_permission_new('items','','delete')){ ?>
            <!-- <a href="#" data-toggle="modal" data-table=".table-invoice-items" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>-->
             <div class="modal fade bulk_actions" id="items_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
               </div>
               <div class="modal-body">
                 <?php if(has_permission_new('leads','','delete')){ ?>
                   <div class="checkbox checkbox-danger">
                    <input type="checkbox" name="mass_delete" id="mass_delete">
                    <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                  </div>
                  <!-- <hr class="mass_delete_separator" /> -->
                <?php } ?>
              </div>
              <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
               <a href="#" class="btn btn-info" onclick="items_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
             </div>
           </div>
           <!-- /.modal-content -->
         </div>
         <!-- /.modal-dialog -->
       </div>
       <!-- /.modal -->
     <?php } ?>
     <?php hooks()->do_action('before_items_page_content'); ?>
     
       <div class="_buttons">
         <?php if(has_permission_new('items','','create')){ ?>
            <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#sales_item_modal"><?php echo _l('new_invoice_item'); ?></a>
        <?php } ?>
        <?php if(has_permission_new('itemsdivision','','view')){ ?>
            <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#groups"><?php echo _l('item_groups'); ?></a>
        <?php } ?>
        <?php if(has_permission_new('itemsmaingrp','','view')){ ?>
            <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#maingroups"><?php echo _l('item_main_groups'); ?></a>
        <?php } ?>
        <?php if(has_permission_new('itemssubgrp','','view')){ ?>
        <a href="#" class="btn btn-info pull-left mleft5" data-toggle="modal" data-target="#subgroups"><?php echo _l('item_sub_groups'); ?></a>
        <?php } ?>
        <!--<a href="<?php echo admin_url('invoice_items/import'); ?>" class="btn btn-info pull-left mleft5"><?php echo _l('import_items'); ?></a>-->
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    
    <?php
    $table_data = [];

    /*if(has_permission_new('items','','delete')) {
      $table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
    }*/

    $table_data = array_merge($table_data, array(
        'Item Code',
      _l('invoice_items_list_description'),
      /*_l('invoice_item_long_description'),*/
     /* _l('invoice_items_list_rate'),
      _l('tax_1'),
      _l('tax_2'),*/
      /*_l('unit')*/'MeasuredIn',
      _l('item_group_name'),
      'Group Name'
      ));

    /*$cf = get_custom_fields('items',array('show_on_table'=>1));
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }*/
    /*$table_data = array_merge($table_data, array(
        
      'Action'
      ));*/
    render_datatable($table_data,'invoice-items'); ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php $this->load->view('admin/invoice_items/item'); ?>

<!-- Item Division Model -->

<div class="modal fade" id="groups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('itemsdivision','','create')){ ?>
          <div class="input-group">
            <input type="text" name="item_group_name" id="item_group_name" class="form-control" placeholder="<?php echo _l('item_group_name'); ?>">
            <span class="input-group-btn">
              <button class="btn btn-info p7" type="button" id="new-item-group-insert"><?php echo _l('save'); ?></button>
            </span>
          </div>
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('sr_no'); ?></th>
                <th><?php echo _l('item_group_name'); ?></th>
                <th>Action</th>
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
                  <!--<div class="row-options">
                    
                  </div>-->
                </td>
                <td>
                    <?php if(has_permission_new('itemsdivision','','edit')){ ?>
                      <a href="#" class="edit-item-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('itemsdivision','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
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
<div class="modal fade" id="maingroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_main_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('itemsmaingrp','','create')){ ?>
        <div class="row">
        <!--<div class="col-md-4">
            
                <input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">
            
        </div>-->
        <div class="col-md-4">
            
            <input type="text" name="item_main_group_name" id="item_main_group_name" class="form-control" placeholder="<?php echo _l('item_main_group_name'); ?>">
            
          
        </div>
        
        <div class="col-md-4">
            <span class="btn" style="top: -7px;position: relative;">
              <button class="btn btn-info p7" type="button" id="new-item-main-group-insert"><?php echo _l('save'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <!--<th><?php echo _l('item_main_group_id'); ?></th>-->
                <th><?php echo _l('item_main_group'); ?></th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_main_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <!--<td data-order="<?php echo $group['item_main_group_id']; ?>"><?php echo $group['item_main_group_id']; ?></td>-->
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
                  <!--<div class="row-options">
                    
                  </div>-->
                </td>
                <td>
                    <?php if(has_permission_new('itemsmaingrp','','edit')){ ?>
                      <a href="#" class="edit-item-main-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('itemsmaingrp','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_main_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?>
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

<div class="modal fade" id="subgroups" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          <?php echo _l('item_sub_groups'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <?php if(has_permission_new('itemssubgrp','','create')){ ?>
        <div class="row">
        <div class="col-md-4">
            
                <!--<input type="text" name="item_main_group_id" id="item_main_group_id" class="form-control" placeholder="<?php echo _l('item_main_group_id'); ?>">-->
            <?php 
            //print_r($items_main_groups);
            $s_attrs = array('data-none-selected-text'=>'Select Main Group');
                     $selected = '';
            echo render_select('item_main_group_id1',$items_main_groups,array('id','name'),'',$selected,$s_attrs); ?>
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
              <button class="btn btn-info p7" type="button" id="new-item-sub-group-insert"><?php echo _l('save'); ?></button>
            </span>
        </div>
        </div>
          
          <hr />
        <?php } ?>
        <div class="row">
         <div class="container-fluid">
          <table class="table dt-table table-items-groups" data-order-col="0" data-order-type="asc">
            <thead>
              <tr>
                <th><?php echo _l('id'); ?></th>
                <th><?php echo _l('item_main_group_name'); ?></th>
                <th><?php echo _l('item_sub_group'); ?></th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($items_sub_groups as $group){ ?>
                <tr class="row-has-options" data-group-row-id="<?php echo $group['id']; ?>">
                  <td data-order="<?php echo $group['id']; ?>"><?php echo $group['id']; ?></td>
                  <td data-order="<?php echo $group['main_group_id']; ?>"><?php $ss = get_main_group_name($group['main_group_id']);
                  echo $ss->name; ?>
                  <?php $getall_main_group = get_main_group(); 
                  //print_r($getall_main_group);
                  ?>
                  
                  </td>
                  <td data-order="<?php echo $group['name']; ?>">
                    <span class="subgroup_name_plain_text"><?php echo $group['name']; ?></span>
                    <div class="sub_group_edit hide">
                        <?php
                  
                  
                  $s_attrs = array('data-none-selected-text'=>'Select Main Group');
                  
                     $selected = '';
                    echo render_select('item_main_group_id_edit',$getall_main_group,array('id','name'),'',$group['main_group_id'],$s_attrs); ?>
                  
                     <div class="input-group">
                      <input type="text" class="form-control" id="subgroup_name">
                      <span class="input-group-btn">
                        <button class="btn btn-info p8 update-item-sub-group" type="button"><?php echo _l('submit'); ?></button>
                      </span>
                    </div>
                  </div>
                  
                </td>
                <td><?php if(has_permission_new('itemssubgrp','','edit')){ ?>
                      <a href="#" class="edit-item-sub-group">
                        <?php echo _l('edit'); ?>
                      </a>
                    <?php } ?>
                    <?php if(has_permission_new('itemssubgrp','','delete')){ ?>
                      | <a href="<?php echo admin_url('invoice_items/delete_sub_group/'.$group['id']); ?>" class="delete-item-group _delete text-danger">
                        <?php echo _l('delete'); ?>
                      </a>
                    <?php } ?></td>
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
<script>
  $(function(){

    var notSortableAndSearchableItemColumns = [];
    <?php if(has_permission_new('items','','delete')){ ?>
      notSortableAndSearchableItemColumns.push(0);
    <?php } ?>

    initDataTable('.table-invoice-items', admin_url+'invoice_items/table', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[1,'asc']);

    if(get_url_param('groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#groups').modal('show');
       },1000);
     }
     
    
    // Item Division Add 
     $('#new-item-group-insert').on('click',function(){
      var group_name = $('#item_group_name').val();
      if(group_name != ''){
        $.post(admin_url+'invoice_items/add_group',{name:group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }else{
          alert("please enter division name..");
      }
    });
    
    if(get_url_param('main_groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#maingroups').modal('show');
       },1000);
     }
     
     if(get_url_param('sub_groups_modal')){
       // Set time out user to see the message
       setTimeout(function(){
         $('#subgroups').modal('show');
       },1000);
     }
    
    
    // Item Main Group add
    $('#new-item-main-group-insert').on('click',function(){
      var main_group_name = $('#item_main_group_name').val();
      //var main_group_id = $('#item_main_group_id').val();
      if(main_group_name != ''){
        $.post(admin_url+'invoice_items/add_main_group',{name:main_group_name}).done(function(){
         window.location.href = admin_url+'invoice_items?main_groups_modal=true';
       });
      }
    });
    
   
    // Item Sub Group add
    $('#new-item-sub-group-insert').on('click',function(){
      var group_name = $('#item_sub_group_name').val();
      var main_group_id = $('#item_main_group_id1').val();
      //var main_group_id = $( "#item_main_group_id1:selected" ).val();
      
      
      if(group_name != '' && main_group_id != ''){
        $.post(admin_url+'invoice_items/add_sub_group',{name:group_name, id:main_group_id}).done(function(){
         window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
       });
      }else{
          alert("please select main group and enter group name..");
      }
    });
    
     $('body').on('click','.update-item-sub-group',function(){
      var tr = $(this).parents('tr');
      var subgroup_id = tr.attr('data-group-row-id');
      name = tr.find('#subgroup_name').val();
      main_group_id = tr.find('select#item_main_group_id_edit option:selected').val();
      //alert(main_group_id);
      alert(name);
      if(name != ''){
        $.post(admin_url+'invoice_items/update_sub_group/'+subgroup_id,{name:name,main_group_id:main_group_id}).done(function(){
         //window.location.href = admin_url+'invoice_items';
         window.location.href = admin_url+'invoice_items?sub_groups_modal=true';
       });
      }else{
          alert("please enter group name");
      }
    });
    

     $('body').on('click','.edit-item-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.group_edit').toggleClass('hide');
      tr.find('.group_edit input').val(tr.find('.group_name_plain_text').text());
    });

     $('body').on('click','.update-item-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_group/'+group_id,{name:name}).done(function(){
         window.location.href = admin_url+'invoice_items?groups_modal=true';
       });
      }else{
          alert("please enter division name..");
      }
    });
    
     $('body').on('click','.edit-item-main-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.group_name_plain_text').toggleClass('hide');
      tr.find('.main_group_edit').toggleClass('hide');
      tr.find('.main_group_edit input').val(tr.find('.group_name_plain_text').text());
    });
    
    $('body').on('click','.edit-item-sub-group',function(e){
      e.preventDefault();
      var tr = $(this).parents('tr'),
      group_id = tr.attr('data-group-row-id');
      tr.find('.subgroup_name_plain_text').toggleClass('hide');
      tr.find('.sub_group_edit').toggleClass('hide');
      tr.find('.sub_group_edit input').val(tr.find('.subgroup_name_plain_text').text());
    });

     $('body').on('click','.update-item-main-group',function(){
      var tr = $(this).parents('tr');
      var group_id = tr.attr('data-group-row-id');
      name = tr.find('.main_group_edit input').val();
      if(name != ''){
        $.post(admin_url+'invoice_items/update_main_group/'+group_id,{name:name}).done(function(){
         //window.location.href = admin_url+'invoice_items';
         window.location.href = admin_url+'invoice_items?main_groups_modal=true';
       });
      }else{
          alert("please enter main group name");
      }
    });
    
   });
  function items_bulk_action(event) {
    if (confirm_delete()) {
      var mass_delete = $('#mass_delete').prop('checked');
      var ids = [];
      var data = {};

      if(mass_delete == true) {
        data.mass_delete = true;
      }

      var rows = $('.table-invoice-items').find('tbody tr');
      $.each(rows, function() {
        var checkbox = $($(this).find('td').eq(0)).find('input');
        if (checkbox.prop('checked') === true) {
          ids.push(checkbox.val());
        }
      });
      data.ids = ids;
      $(event).addClass('disabled');
      setTimeout(function() {
        $.post(admin_url + 'invoice_items/bulk_action', data).done(function() {
          window.location.reload();
        }).fail(function(data) {
          alert_float('danger', data.responseText);
        });
      }, 200);
    }
  }
 </script>
</body>
</html>
