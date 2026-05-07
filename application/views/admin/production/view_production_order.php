<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); 

?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
            <?php if(has_permission('hsnmaster','','delete')){ ?>
            <!-- <a href="#" data-toggle="modal" data-table=".table-invoice-items" data-target="#items_bulk_actions" class="hide bulk-actions-btn table-btn"><?php echo _l('bulk_actions'); ?></a>-->
             <div class="modal fade bulk_actions" id="items_bulk_actions" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
               <div class="modal-content">
                <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title"><?php echo _l('bulk_actions'); ?></h4>
               </div>
               <div class="modal-body">
                 <?php if(has_permission('leads','','delete')){ ?>
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
     <?php if(has_permission('hsnmaster','','create')){ ?>
       <div class="_buttons">
        <h4>List Production Order</h4>
      </div>
      <!--<hr class="hr-panel-heading" />-->
      
    <?php } ?>
    <?php
    $table_data = [];

    $table_data = array_merge($table_data, array(
	    'ProductinID',
	    "PRD.Date",
        'Recipe Name',
        'Batch Qty',
        'F.G.Qty.',
        "Req.Time",
		"Manager/Contractor Name",
		
		"Status",
		"comment",
      ));

    
    render_datatable($table_data,'production-table'); ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php //$this->load->view('admin/hsn_master/add_model'); ?>



<?php init_tail(); ?>
<style>
    #errmsg
{
color: red;
}
</style>
<script>
    $(document).ready(function () {
  
   
       $("#recipepercent").on("input", function(evt) {
       var self = $(this);
       self.val(self.val().replace(/[^0-9\.]/g, ''));
       if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
       {
         evt.preventDefault();
         
       }
     });
     
       
        // Set validation for Recipe form
        appValidateForm($('#production_form'), {
            recipepercent: 'required',
            recipedate: 'required',
          
            
        });
    
});
</script>
<script>
  $(function(){

    var notSortableAndSearchableItemColumns = [];
    <?php if(has_permission('hsnmaster','','delete')){ ?>
      notSortableAndSearchableItemColumns.push(0);
    <?php } ?>

    initDataTable('.table-production-table', admin_url+'production/table_production', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[0,'desc']);

   });
  
 </script>
</body>
</html>
