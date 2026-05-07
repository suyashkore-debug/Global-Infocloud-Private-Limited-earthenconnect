<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-clients tbody{
  display: block;
  max-height: 350px;
  overflow-y: scroll;
}
.table-clients thead, .table-clients tbody tr{
  display: table;
  table-layout: fixed;
  width: 100%;
}
.table-clients thead{
  width: calc(100% - 1.1em);
}
.table-clients thead{
  position: relative;
}
.table-clients thead th:last-child:after{
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
                  
                  
                  <div class="row ">
                    
                    
                    <div class="col-md-3 leads-filter-column">
                    
                    <?php echo render_select('distributor_state',$state,array('id','state_name'),'state'); ?>
                    </div>
                    
                    <div class="col-md-3 leads-filter-column">
                      <?php echo render_select('responsible_admin',$staff_list,array('staffid',array('firstname','lastname')),'responsible'); ?>
                    </div>
                    
                    <!--<div class="col-md-2 leads-filter-column">
                     
                        <div class="form-group">
                            <label class="control-label">Satus</label>
                            <select name="status" id="status" class="form-control selectpicker">
                                <option value=""></option>
                                <option value="1">Active</option>
                                <option value="0">DeActive</option>
                            </select>
                        </div>
                        
                    </div>-->
                               
                </div>
                  
                  <hr class="hr-panel-heading" />
                  <!--<a href="#" data-toggle="modal" data-target="#customers_bulk_action" class="bulk-actions-btn table-btn hide" data-table=".table-clients"><?php echo _l('bulk_actions'); ?></a>-->
                  <div class="modal fade bulk_actions" id="customers_bulk_action" tabindex="-1" role="dialog">
                     <div class="modal-dialog" role="document">
                        <div class="modal-content">
                           <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                              <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
                           </div>
                           <div class="modal-body">
                              <?php if(has_permission('customers','','delete')){ ?>
                              <div class="checkbox checkbox-danger">
                                 <input type="checkbox" name="mass_delete" id="mass_delete">
                                 <label for="mass_delete"><?php echo _l('mass_delete'); ?></label>
                              </div>
                              <hr class="mass_delete_separator" />
                              <?php } ?>
                              <div id="bulk_change">
                                 <?php echo render_select('move_to_groups_customers_bulk[]',$groups,array('id','name'),'customer_groups','', array('multiple'=>true),array(),'','',false); ?>
                                 <p class="text-danger"><?php echo _l('bulk_action_customers_groups_warning'); ?></p>
                              </div>
                           </div>
                           <div class="modal-footer">
                              <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                              <a href="#" class="btn btn-info" onclick="customers_bulk_action(this); return false;"><?php echo _l('confirm'); ?></a>
                           </div>
                        </div>
                        <!-- /.modal-content -->
                     </div>
                     <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                  <!--<div class="checkbox">
                     <input type="checkbox" checked id="exclude_inactive" name="exclude_inactive">
                     <label for="exclude_inactive"><?php echo _l('exclude_inactive'); ?> <?php echo _l('clients'); ?></label>
                  </div>-->
                  
                  <div class="clearfix mtop20"></div>
                  <?php
                  $table_data = array();
                     $_table_data = array(
                      /*'<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="clients"><label></label></div>',*/
                       /*array(
                         'name'=>_l('the_number_sign'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-number')
                        ),*/
                         array(
                         'name'=>"Staff Name",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-number')
                        ),
                         array(
                         'name'=>"Shop Name",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-company')
                        ),
                         array(
                         'name'=>"Date",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sales_person', 'width'=>'45px')
                        ),
                         array(
                         'name'=>"Contact Person",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-active')
                        ),
                        array(
                         'name'=>"Mobile Number",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-groups')
                        ),
                        array(
                         'name'=>_l('customer_state'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-state')
                        ),
                        array(
                         'name'=>"City",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-town')
                        ),
                        array(
                         'name'=>"Area",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sales_person')
                        ),
                        array(
                         'name'=>"Address",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sales_person')
                        ),
                        array(
                         'name'=>"Remark",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sales_person')
                        ),
                        array(
                         'name'=>"Revisit",
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-sales_person')
                        ),
                        
                      );
                     foreach($_table_data as $_t){
                      array_push($table_data,$_t);
                     }

                     $custom_fields = get_custom_fields('customers',array('show_on_table'=>1));
                     foreach($custom_fields as $field){
                      array_push($table_data,$field['name']);
                     }
                     
                     /*array_push($table_data,array(
                         'name'=>_l('date_created'),
                         'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-date-created')
                        ));*/

                     $table_data = hooks()->apply_filters('customers_table_columns', $table_data);

                     render_datatable($table_data,'clients',[],[
                           'data-last-order-identifier' => 'customers',
                           'data-default-order'         => get_table_last_order('customers'),
                     ]);
                     ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php init_tail(); ?>
<script>
   $(function(){
       var CustomersServerParams = {};
       $.each($('._hidden_inputs._filters input'),function(){
          CustomersServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
      });
       
       CustomersServerParams['distributor_state'] = '[name="distributor_state"]';
      
       CustomersServerParams['responsible_admin'] = '[name="responsible_admin"]';
       CustomersServerParams['status'] = '[name="status"]';
       
       var tAPI = initDataTable('.table-clients', admin_url+'enquiry/table', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'desc'))); ?>);
       $('input[name="exclude_inactive"]').on('change',function(){
           tAPI.ajax.reload();
       });
       
       $('select[name="distributor_state"]').on('change',function(){
           tAPI.ajax.reload();
       });
       
       $('select[name="responsible_admin"]').on('change',function(){
           tAPI.ajax.reload();
       });
       $('select[name="status"]').on('change',function(){
           tAPI.ajax.reload();
       });
       
   });
   function customers_bulk_action(event) {
       var r = confirm(app.lang.confirm_action_prompt);
       if (r == false) {
           return false;
       } else {
           var mass_delete = $('#mass_delete').prop('checked');
           var ids = [];
           var data = {};
           if(mass_delete == false || typeof(mass_delete) == 'undefined'){
               data.groups = $('select[name="move_to_groups_customers_bulk[]"]').selectpicker('val');
               if (data.groups.length == 0) {
                   data.groups = 'remove_all';
               }
           } else {
               data.mass_delete = true;
           }
           var rows = $('.table-clients').find('tbody tr');
           $.each(rows, function() {
               var checkbox = $($(this).find('td').eq(0)).find('input');
               if (checkbox.prop('checked') == true) {
                   ids.push(checkbox.val());
               }
           });
           data.ids = ids;
           $(event).addClass('disabled');
           setTimeout(function(){
             $.post(admin_url + 'enquiry/bulk_action', data).done(function() {
              window.location.reload();
          });
         },50);
       }
   }
</script>
</body>
</html>
