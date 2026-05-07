<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    tableFixHead2          { overflow: auto;max-height: 40vh;width:100%;position:relative;top: 0px; }
.tableFixHead2 thead th { position: sticky; top: 0; z-index: 1; }
.tableFixHead2 tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="panel_s">
          <div class="panel-body">
           
     <?php hooks()->do_action('before_items_page_content'); ?>
     <?php if(has_permission_new('tcsmaster','','create')){ ?>
       <div class="_buttons">
            <h4>Add New TCS</h4>
        </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
      <!--<hr class="hr-panel-heading" />-->
      <?php echo form_open('admin/tcs_master/manage',array('id'=>'tcs_form')); ?>
            <div class="row">
                <div class="col-md-3">
                    <?php echo render_input('tcspercent','TCS %'); ?>
                    &nbsp;<span id="errmsg"></span>
                </div>
                <div class="col-md-3">
                    <?php echo render_date_input('tcsdate','TCS EffDate'); ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button type="submit" class="btn btn-info add_tcs" id="add_tcs"><?php echo _l('submit'); ?></button>
                </div>
            </div>
      <?php echo form_close(); ?>
    <?php } ?>
    <div class="row">
        
    <div class="col-md-6">
        <div class="custom_button">
            <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
            <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
            <!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
        </div>
    </div>
    <div class="col-md-6">
        <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
            
    </div>
     <div class="col-md-12">
            <div class="tableFixHead2">
                <table class="table table-striped table-bordered tableFixHead2" width="100%" id="user_list">
                <thead>
                <tr style="display:none;">
                    <th style="text-align:center;" colspan="4"><?php echo $company_detail->company_name; ?></th>
                </tr>
                <tr style="display:none;">
                    <th style="text-align:center;" colspan="4"><?php echo $company_detail->address; ?></th>
                </tr>
                <tr style="display:none;">
                    <th colspan="4" style="text-align:center;">TCS Master </th>
                </tr>
                <tr>
                    <th>TCS %</th>
                    <th>Effected Date</th>
                    <th>Created staff</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($tcs_table as $aRow) {
                    
                    $date = substr($aRow['EffDate'],0,10);
                    $cur_date = date('Y-m-d');
                    if($date<=$cur_date){
                        $active = "Active";
                        $tcs_id = $aRow['id'];
                    }
                }
                    foreach ($tcs_table as $key => $value) {
                ?>
                    <tr>
                    <td><?php echo $value["tcs"];?></td>
                    <td><?php echo substr($value['EffDate'],0,10);?></td>
                  <?php  $full_name = get_staff_name($value['UserId']);
                    if(empty($full_name)){
                        $row = $value['UserId'];
                    }else{
                        $row = $full_name->firstname .' '.$full_name->lastname;
                    }?>
                    <td><?php echo $row;?></td>
                    <?php  if($tcs_id == $value['id']){
                            $row_a = "Active";
                        }else{
                            $row_a = "DeActive";
                    }?>
                    
                    <td><?php echo $row_a;?></td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
                </table>
            </div>
            <span id="searchh3" style="display:none;">Please wait data exporting.....</span>
                </div>
    </div>
    <?php
    /*$table_data = [];

    $table_data = array_merge($table_data, array(
        'TCS %',
        "Effected Date",
        'Created staff',
      "Status",
     
      ));

    
    render_datatable($table_data,'tcs-table');*/
    ?>
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
</style>
<script>
    $(document).ready(function () {
  
       $("#tcspercent").on("input", function(evt) {
       var self = $(this);
       self.val(self.val().replace(/[^0-9\.]/g, ''));
       if ((evt.which != 46 || self.val().indexOf('.') != -1) && (evt.which < 48 || evt.which > 57)) 
       {
         evt.preventDefault();
         
       }
     });
     
       
        // Set validation for TCS form
        appValidateForm($('#tcs_form'), {
            tcspercent: 'required',
            tcsdate: 'required',
            
            
        });
    
});
</script>

 <script>
     function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("user_list");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
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
$("#caexcel").click(function(){
    var data_val = "data";
    $.ajax({
        url:"<?php echo admin_url(); ?>Tcs_master/export_TcsMaster",
        method:"POST",
        data:{data_val:data_val,},
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


</script> 
<script type="text/javascript">
 function printPage(){
        
         var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="4"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="4"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="4">TCS Master</td>';
         heading_data += '</tr>';
         heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
 </script>
 <style type="text/css">
   body{
    overflow: hidden;
   }
 </style>
</body>
</html>
