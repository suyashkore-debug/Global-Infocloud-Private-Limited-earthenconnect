<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
             <?php hooks()->do_action('before_items_page_content'); ?>
            <?php if(has_permission('hsnmaster','','create')){ ?>
            <div class="col-md-2">
             <div class="_buttons">
		       <a href="<?php echo admin_url('target'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('target_list'); ?></a>  
		    </div>
		    </div>
              <div class="_buttons">
				<h4>Add New Target</h4>
			  </div>
              <div class="clearfix"></div>
			   <?php echo form_open('admin/target/add',array('id'=>'salereceipts_form')); ?>

			  <div class="row">
			  
			   <!--<div class="col-md-4">
                    <?php // echo render_input('staff','Sales Person'); ?>
                  <div class="" id="serchh" style="display:none;">Serching</div>
                </div>  -->
				
				<div class="col-md-4 leads-filter-column ">
				<label class="control-label " for="Sales Person"><?php echo _l('Sales Person'); ?></label>
					<select name="staff" class="selectpicker" id="staff" data-width="100%" required data-live-search="true"> 
					<option value="">Select</option>
					<?php 
						foreach ($staff_data as $value) { ?>
						<option value="<?php echo html_entity_decode($value['staffid']); ?>"><?php echo html_entity_decode($value['firstname']." ".$value['lastname']) ?></option>
					<?php } ?>              
					</select>
				</div>
							   
				<div class="col-md-4">
				<label class="control-label" for="year"><?php echo _l('Year'); ?></label>
				 <select class="form-control selectpicker" required name="year">
                  <?php for($i=0;$i<=5;$i++){
                  $year=date('Y',strtotime("last day of +$i year"));
				  $year1 = substr($year, -2);
				  ?>
                  <!--<option name="year"><?php// echo $year?></option>-->
                  <option value="<?php echo $year1?>"><?php echo $year;?></option>
                   <?php } ?>
                 </select>
				</div>
				
				<div class="col-md-4">
				<label class="control-label " for="month"><?php echo _l('Month'); ?></label>
				 <select class="form-control selectpicker" name="month" required>
				   <?php for($i=0;$i<=11;$i++){
                         $month=date('F',strtotime("first day of -$i month"));
                          if($month=="January"){
                           $mon="01";
                          }elseif($month=="February"){
                           $mon="02";
                          }elseif($month=="March"){
                           $mon="03";
                          }elseif($month=="April"){
                           $mon="04";
                          }elseif($month=="May"){
                           $mon="05";
                          }elseif($month=="June"){
                           $mon="06";
                          }elseif($month=="July"){
                           $mon="07";
                          }elseif($month=="August"){
                           $mon="08";
                          }elseif($month=="September"){
                           $mon="09";
                          }elseif($month=="October"){
                           $mon="10";
                          }elseif($month=="November"){
                           $mon="11";
                          }elseif($month=="December"){
                           $mon="12";
                          } 
                           
                          ?>
                          <option  value="<?php echo $mon?>"><?php echo $month;?></option>
                           <?php } ?>  

                 </select>
				</div>
			</div>	
			
			<div class="row">
				<div class="col-md-4" style="margin-top:10px;">
					<label class="control-label" for="month"><?php echo _l('Target in Rupees'); ?></label>
					<input type="text" name="total" required class="form-control" id="total" placeholder=""  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">

                </div>
				
            </div>
		
			
			<div class="row">
				<div class="col-md-3" style="margin-top:10px;">
                    <br>
                    <button type="submit" id="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
			</div>
			 <?php echo form_close(); ?>
			 <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>

<style>
    #errmsg
{
color: red;
}
.table>tbody>tr>td, .table>tfoot>tr>td {
    padding: 3px 0px 3px 3px;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
<script type='text/javascript'>
    $(document).ready(function(){
		
      // Initialize 
     $( "#staff" ).autocomplete({
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url: "<?=base_url()?>admin/Target/staff_list1",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            beforeSend: function () {
               
               $('#serchh').css('display','block');
               //$("#ui-id-2").prepend("<li value='' class='ui-menu-item'>Serching</li>");
            },
            complete: function () {
                //$("#item_code").val("");
                $('#serchh').css('display','none');
            },
            success: function( data ) {
                
              response( data );
            }
            
          });
        },
        select: function (event, ui) {
                
                $('#staff').val(ui.item.label);
				//$('#staff').val(ui.item.id);
				//$('#unit1').val(ui.item.units); // save selected id to input
            }
       
      });
    });
   
    </script>
  
	
    