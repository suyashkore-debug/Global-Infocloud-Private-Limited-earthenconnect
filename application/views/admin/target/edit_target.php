<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head();
foreach($editRecipe_details as $object){	
			   $id= $object['id'];
			   }
 ?>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              
              <div class="_buttons">
				<h4>Edit Target</h4>
			  </div>
              <div class="clearfix"></div>
			   <?php echo form_open('admin/target/editTarget/'.$object['id'],array('id'=>'salereceipts_form'));
			  // echo form_open(base_url().'khanposts/editpost/'.$row['post_id']);
             
			   ?>
			  
			<input type="hidden" name="id" id="id" value="<?php echo $object['id']?>">
			  
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
						<!--<option value="<?php //echo html_entity_decode($value['staffid']); ?>">
						<?php //echo html_entity_decode($value['firstname']." ".$value['lastname']) ?></option>-->
						
						<option value="<?php echo html_entity_decode($value['staffid']); ?>"
						<?php if($object['staff_id']==$value['staffid']) echo "selected"; ?>>
						<?php echo html_entity_decode($value['firstname']." ".$value['lastname']) ?></option>
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
                  <!--<option value="<?php //echo $year1?>"><?php //echo $year;?></option>-->
				  <option value="<?php echo $object['year'];?>" 
                        <?php if($object['year']==$year1) echo "selected"; ?>>
                        <?php echo $year;?>
                        </option>
                   <?php } ?>
				
                 </select>
				</div>
				
				<div class="col-md-4">
				<label class="control-label " for="month"><?php echo _l('Month'); ?></label>
				 <select class="form-control selectpicker" name="month" required>
				    <option value="01" <?php if($object['month']=="01") echo "selected"; ?>>January</option>
                    <option value="02" <?php if($object['month']=="02") echo "selected"; ?>>February</option>
                    <option value="03" <?php if($object['month']=="03") echo "selected"; ?>>March</option>
                    <option value="04" <?php if($object['month']=="04") echo "selected"; ?>>April</option>
                    <option value="05" <?php if($object['month']=="05") echo "selected"; ?>>May</option>     
                    <option value="06" <?php if($object['month']=="06") echo "selected"; ?>>June</option>
                    <option value="07" <?php if($object['month']=="07") echo "selected"; ?>>July</option>
                    <option value="08" <?php if($object['month']=="08") echo "selected"; ?>>August</option>
                    <option value="09" <?php if($object['month']=="09") echo "selected"; ?>>September</option>
                    <option value="10" <?php if($object['month']=="10") echo "selected"; ?>>October</option>
                    <option value="11" <?php if($object['month']=="11") echo "selected"; ?>>November</option>
                    <option value="12" <?php if($object['month']=="12") echo "selected"; ?>>December</option>   
                
                 </select>
				</div>
			</div> 
			 
			 
            <div class="row">
				<div class="col-md-4" style="margin-top:10px;">
					<label class="control-label" for="month"><?php echo _l('Target in Rupees'); ?></label>
					<input type="text" name="total" value="<?php echo $object['total'];?>" class="form-control" id="total" placeholder=""  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">

                </div>
				
            </div>
		 
			<div class="row">
				<div class="col-md-3" style="margin-top:10px;">
                    <br>
                    <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
                </div>
			</div>
			   <?php echo form_close(); ?>
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
.table>thead>tr>th{
    padding:4px;
}
table.dataTable thead th, table.dataTable thead td {
    padding: 2px 2px !important;
    
}
table.dataTable tbody  td {
    padding: 3px 3px !important;
    
}
</style>


<!-- Script -->
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
    
    <!-- jQuery UI -->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    
 <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>-->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>


