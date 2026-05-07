<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .tableFixHead2          { overflow: auto;max-height: 40vh;width:100%;position:relative;top: 0px; }
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
     <?php if(has_permission_new('vehiclemaster','','create')){ ?>
       <div class="_buttons">
        <a href="#" class="btn btn-info pull-left" data-toggle="modal" data-target="#vehicle_modal">Add Vehicle</a>
        </div>
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
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
                    <th style="text-align:center;" colspan="6"><?php echo $company_detail->company_name; ?></th>
                </tr>
                <tr style="display:none;">
                    <th style="text-align:center;" colspan="6"><?php echo $company_detail->address; ?></th>
                </tr>
                <tr style="display:none;">
                    <th colspan="6" style="text-align:center;">Vehicles Master </th>
                </tr>
                <tr>
                    <th>Vehicle RegNo</th>
                    <th>Vehicle Type</th>
                    <th>Vehicle Capacity</th>
                    <th>Start Day</th>
                    <th>Active</th>
                    <th class="hide_in_print">Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($vehicle_data as $key => $value) {
                ?>
                    <tr>
                    <td><?php echo $value["VehicleID"];?></td>
                    <td><?php 
                    if($value["VehicleTypeID"] == "0"){
                        echo "Own";
                    }else{
                        echo "Other";
                    }
                    ?></td>
                    <td><?php echo $value["VehicleCapacity"];?></td>
                    <td><?php echo substr(_d($value['StartDate']),0,10);?></td>
                   <?php if($value['ActiveYN'] == "1"){
                        $status = "Y";
                    }else{
                        $status = "N";
                    } ?>
                    <td><?php echo $status;?></td>
                   <?php  if (has_permission_new('vehiclemaster', '', 'edit')) {
    $action = '<a href="#" data-toggle="modal" data-target="#vehicle_modal" data-id="' . $value['VehicleID'] . '"><i class="fa fa-pencil"></i></a>';
    }
    if (has_permission_new('vehiclemaster', '', 'delete')) {
    //$action .= '  <a href="' . admin_url('vehicles/delete/' . $value['VehicleID']) . '" class="text-danger _delete"><i class="fa fa-trash"></i></a>';
    }?>
                    <td class="hide_in_print"><?php echo $action;?></td>
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
        'Vehicle RegNo',
      "Vehicle Type",
     "vehicle Capacity",
      "Start Day",
      'Active'
      ));

    $cf = get_custom_fields('items',array('show_on_table'=>1));
    foreach($cf as $custom_field) {
      array_push($table_data,$custom_field['name']);
    }
    $table_data = array_merge($table_data, array(
        
      'Action'
      ));
    render_datatable($table_data,'vehicle-table'); */
    ?>
  </div>
</div>
</div>
</div>
</div>
</div>
<?php $this->load->view('admin/vehicle/add_model'); ?>





<?php init_tail(); ?>
<script>
  $(function(){

    var notSortableAndSearchableItemColumns = [];
    <?php if(has_permission_new('vehiclemaster','','delete')){ ?>
      //notSortableAndSearchableItemColumns.push(0);
    <?php } ?>

    initDataTable('.table-vehicle-table', admin_url+'vehicles/table', notSortableAndSearchableItemColumns, notSortableAndSearchableItemColumns,'undefined',[0,'DESC']);

    

    
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
        url:"<?php echo admin_url(); ?>Vehicles/export_VehicleMaster",
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
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .hide_in_print{ display:none; }</style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="6"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="6"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td colspan="6" style="text-align:center;">Vehicles details </td>';
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
