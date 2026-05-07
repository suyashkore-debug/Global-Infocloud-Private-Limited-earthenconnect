<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
           
     <?php hooks()->do_action('before_items_page_content'); ?>
     
      <div class="clearfix"></div>
      
      <div class="_buttons">
         <div class="col-md-3">
             <?php
                    $fy = $this->session->userdata('finacial_year');
                    $fy_new  = $fy + 1;
                    $lastdate_date = '20'.$fy_new.'-03-31';
                    $firstdate_date = '20'.$fy_new.'-04-01';
                    $curr_date = date('Y-m-d');
                    $curr_date_new    = new DateTime($curr_date);
                    $last_date_yr = new DateTime($lastdate_date);
                    if($last_date_yr < $curr_date_new){
                        $to_date = '31/03/20'.$fy_new;
                        $from_date = '01/03/20'.$fy_new;
                    }else{
                        $from_date = "01/".date('m')."/".date('Y');
                        $to_date = date('d/m/Y');
                    }
            ?> 
            <?php 
            echo render_date_input('date','Date',$to_date);          
            ?>
         </div>
         
        <!-- <div class="col-md-3">
               <?php 
            
             echo render_select('distributor_id',$groups,array('id','name'),'customer_groups',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        
            ?>
         </div>-->
         
                  
      </div>
      
      <div class="col-md-3" style="margin-top:10px;">
          <br>
      <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
      </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
    
    <?php
    $table_data = [];

    if(has_permission('ratemaster','','delete')) {
      //$table_data[] = '<span class="hide"> - </span><div class="checkbox mass_select_all_wrap"><input type="checkbox" id="mass_select_all" data-to-table="invoice-items"><label></label></div>';
    }

    $table_data = array(
  "Challan No.",
 /* "Order Date",*/
  /*"Invoice",
  "Invoice Date",
  "Challan",*/
  "Staff Name",
  "Vehicle",
  "Driver Name",
  "Route",/*
  "CS/CR",*/
  
  "Cases",
  /*"OpenBalAmt",
  */
  "Crates"/*,
  "Remark1"*/);

    /*$cf = get_custom_fields('items');
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }*/
    render_datatable($table_data,'gatepass'); ?>
  </div>
</div>
</div>
</div>
</div>
</div>


<?php init_tail(); ?>
<script>

  $(function(){

    $('#search_data').on('click',function(){
    
    var notSortableAndSearchableItemColumns = [];
    <?php if(has_permission('items','','delete')){ ?>
      notSortableAndSearchableItemColumns.push(0);
    <?php } ?>
    
    
	var CustomersServerParams = {};

	CustomersServerParams['date'] = '[name="date"]';
	//CustomersServerParams['distributor_id'] = '[name="distributor_id"]';
	var dates = $("#date").val();
	//var distributor_id = $("#distributor_id").val();
	//alert(customer_type);
	if (dates.trim()!=''){
	    if ($.fn.DataTable.isDataTable('.table-gatepass')) {
	 		$('.table-gatepass').DataTable().destroy();
	 	} 
	  initDataTable('.table-gatepass', admin_url+'challan/gatepass_list', [0], [0], CustomersServerParams,<?php echo hooks()->apply_filters('customers_table_default_order', json_encode(array(0,'DESC'))); ?>);
       
	}
            else{
              alert("Please select Date First");
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
 
  <script>
    $(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y => fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
   
    $('#date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    });
</script> 
</body>
</html>
