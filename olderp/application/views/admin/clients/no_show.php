<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
   <div class="content">
       <div class="row">
           <div class="col-md-8">
       <div class="panel_s">
            <div class="panel-body">
                <?php
                   /* echo "<pre>";
                    print_r($all_clients);*/
                    
                ?>
                <div class="row">
                <?php echo form_open($this->uri->uri_string(),array('class'=>'client-form','autocomplete'=>'off')); ?>
                                  
                            <div class="col-md-12">
                             <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                                <div class="tableFixHead">
                                  
                                  <table class="tableFixHead" id="pending_data" width="100%" >
                                    <thead>
                                      <tr>
                                        
                                        <th>AccountID</th>
                                        <th>Account Name</th>
                                        <th>Account Source</th>
                                        <!--<th>Dist Type</th>
                                        <th>State</th>-->
                                        <th>Action ?</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        foreach ($NoShowclients as $key => $value) {
                                    ?>
                                        <tr>
                                            <td>
                                            <?php echo $value["AccountID"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value["company"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value["SubActGroupName"]; ?>
                                            </td>
                                            <td><input name="user_id[]" value="<?php echo $value["AccountID"];?>" type="checkbox" checked></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php
                                        foreach ($NoShowStaff as $key1 => $value1) {
                                    ?>
                                        <tr>
                                            <td>
                                            <?php echo $value1["AccountID"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value1["firstname"]." ".$value1["lastname"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value1["SubActGroupName"]; ?>
                                            </td>
                                            <td><input name="user_id[]" value="<?php echo $value1["AccountID"];?>" type="checkbox" checked></td>
                                        </tr>
                                        <?php } ?>
                                <!-- Non No show Accounts        -->
                                        <?php
                                        foreach ($NonNoShowclients as $key => $value) {
                                    ?>
                                        <tr>
                                            <td>
                                            <?php echo $value["AccountID"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value["company"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value["SubActGroupName"]; ?>
                                            </td>
                                            <td><input name="user_id[]" value="<?php echo $value["AccountID"];?>" type="checkbox"></td>
                                        </tr>
                                        <?php } ?>
                                        
                                        <?php
                                        foreach ($NonNoShowStaff as $key1 => $value1) {
                                    ?>
                                        <tr>
                                            <td>
                                            <?php echo $value1["AccountID"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value1["firstname"]." ".$value1["lastname"]; ?>
                                            </td>
                                            <td>
                                            <?php echo $value1["SubActGroupName"]; ?>
                                            </td>
                                            <td><input name="user_id[]" value="<?php echo $value1["AccountID"];?>" type="checkbox"></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                  </table> 
                                  
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <?php
                        if (has_permission_new('no_show', '', 'edit')) {
                        ?>
                               <button type="submit" class="btn btn-info pull-right mleft5 update_noshow" id="update_noshow">Update</button>
                         <?php }else{
                        echo "<span style='color:red'>Your not permitted to update record..</span>";
                        } ?>
                            </div>
                    <?php echo form_close(); ?>
                    
                        </div>
            </div>
        </div>
        </div>
            
        </div>
    </div>
</div>
<style>
    .tableFixHead          { overflow: auto;max-height: 50vh;width:100%;position:relative;top: 0px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1; }
.tableFixHead tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px; white-space: nowrap; border:1px solid; font-size:11px; line-height:1.42857143;vertical-align: middle;}
th     { background: #50607b;
    color: #fff; }
</style>

<?php init_tail(); ?>
<script>
    
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("pending_data");
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

<!--<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
<script>
    $(document)
  .ready(function () {
    $('#pending_data')
      .DataTable({
        "order": [[ 0, "asc" ],[ 2, "false" ]],
        "paging": false
    });
  });
</script>-->