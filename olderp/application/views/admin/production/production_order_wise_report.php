<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-PRD_List          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-PRD_List thead th { position: sticky; top: 0; z-index: 1; }
.table-PRD_List tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
   

#table_PRD_List td:hover {
    cursor: pointer;
}
#table_PRD_List tr:hover {
    background-color: #ccc;
}

</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
           

      
      <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
      <?php if(has_permission_new('production_order_report','','view')){ ?>
      <div class="_buttons">
         
            <div class="col-md-4">
                <div class="form-group" app-field-wrapper="status_list">
                    <label for="Production Order number" class="control-label"><?php echo _l('Production Order Number'); ?></label>
                    <input type="text" name="pro_order_id" id="pro_order_id" class="form-control" placeholder="POI21300719" >
                       
                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
            </div>
            
            <div class="col-md-6">
                <div class="custom_button" style="margin-top: 19px;">
                &nbsp;<!--<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>-->
                <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                </div>
            </div>
     
      </div>
         <div class="clearfix"></div>
    <!-- Iteme List Model-->
        <div class="modal fade PRD_List" id="PRD_List" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-lg" role="document" style="max-width:650px;">
            <div class="modal-content">
                <div class="modal-header" style="padding:5px 10px;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Production List</h4>
                    </div>
                <div class="modal-body" style="padding:0px 5px !important">
                    <div class="table-PRD_List tableFixHead2">
                        <table class="tree table table-striped table-bordered table-PRD_List tableFixHead2" id="table_PRD_List" width="100%">
                            <thead>
                                <tr style="display:none;">
                                    <td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="" style="font-size:10px;">Production List</span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
                                </tr>
                                <tr>
                                    <th>ProductionID </th>
                                    <th>PRDDate</th>
                                    <th style="text-align:left;">ItemID</th>
                                    <th style="text-align:left;">ItemName</th>
                                    <th>PRDQty</th>
                                    <th>ProductionBy</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                        <?php
                            foreach ($PrdList as $key => $value) {
                        ?>
                                <tr class="get_PRDID" data-id="<?php echo $value["pro_order_id"]; ?>">
                                    <td><?php echo $value['pro_order_id'];?></td>
                                    <td><?php echo _d(substr($value['TransDate'],0,10));?></td>
                                    <td><?php echo $value['recipeID'];?></td>
                                    <td><?php echo $value['description'];?></td>
                                    <td><?php echo $value['Finish_good_qty_new'];?></td>
                                    <?php 
                                    if(is_null($value['manager_name'])){
                                        $prdBy = $value['contractor_name'];
                                    }else{
                                        $prdBy = $value['manager_name'];
                                    }
                                    ?>
                                    <td><?php echo $prdBy;?></td>
                                    <td><?php echo $value['production_status'];?></td>
                                </tr>
                        <?php } ?>
                            </tbody>
                        </table>   
                    </div>
                </div>
                <div class="modal-footer" style="padding:0px;">
                    <input type="text" id="productionListInput" onkeyup="productionList()" placeholder="Search for names.." title="Type in a name" style="float: left;width: 100%;">
                </div>
            </div>
            <!-- /.modal-content -->
            </div>
        <!-- /.modal-dialog -->
        </div>
    <!-- /.modal -->
      <hr class="hr-panel-heading" />
        
    <?php } ?>
    
        <?php
        //print_r($company_detail);
        ?>
        <span id="searchh3" style="display:none;"> please wait exporting data...</span>
        <span id="searchh2" style="display:none;">please wait fetching data...</span>
            <div class="table-daily_report tableFixHead2">
                <div class="production_table">
                 
                </div>
                <br>
                <br>
                 
                <div class="col-md-12">
                    <input type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Search .."  style="float: right;">
                    
                </div>
                <div class="row_material_table">
                 
                </div>
            </div>
            <br>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <div class="lower_table">
                
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cost_table">
                
                    </div>
                </div>
            </div>
        
                
  </div>
</div>
</div>
</div>
</div>
</div>


<?php init_tail(); ?>
 <script>
 $(document).ready(function(){
    $('#myInput2').css('display','none'); 
    $("#pro_order_id").dblclick(function(){
        $('#myInput1').focus();
        $('#PRD_List').modal('show');
            
    });
    
    $('.get_PRDID').on('click',function(){ 
            PRDID = $(this).attr("data-id");
            $.ajax({
                url:"<?php echo admin_url(); ?>production/GetPRDDetailByID",
                dataType:"JSON",
                method:"POST",
                data:{PRDID:PRDID},
                
                success:function(data){
                       $('#pro_order_id').val(data.pro_order_id);
                }
            });
            $('#PRD_List').modal('hide');
        });
  function load_data(pro_order_id)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>production/load_table_production_report",
      dataType:"JSON",
      method:"POST",
      data:{pro_order_id:pro_order_id},
      beforeSend: function () {
        $('#searchh2').css('display','block');
        $('#searchh2').css('color','blue');
        $('#table-daily_report tbody').css('display','none');
        
     },
      complete: function () {
        $('#table-daily_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
      
        $('#myInput2').css('display','block');
         $('.production_table').html(data.production_table);
         $('.row_material_table').html(data.row_material_table);
         $('.lower_table').html(data.lower_table);
         $('.cost_table').html(data.cost_table);
    
      }
    });
  }
  
 $('#search_data').on('click',function(){
        var pro_order_id = $("#pro_order_id").val();
	   
        load_data(pro_order_id);
        
 });

});
function productionList() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("productionListInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_PRD_List");
  tr = table.getElementsByTagName("tr");
 for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
      td2 = tr[i].getElementsByTagName("td")[2];
      td3 = tr[i].getElementsByTagName("td")[3];
      td5 = tr[i].getElementsByTagName("td")[5];
      td6 = tr[i].getElementsByTagName("td")[6];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td2){
         txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td3){
         txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td5){
         txtValue = td5.textContent || td5.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td6){
         txtValue = td6.textContent || td6.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else{
           tr[i].style.display = "none";
      } 
    }
    }
    }
    }     
  }
}
}

function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput2");
  filter = input.value.toUpperCase();
  table = document.getElementById("row_material_table");
  tr = table.getElementsByTagName("tr");
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[0];
          td2 = tr[i].getElementsByTagName("td")[1];
          td3 = tr[i].getElementsByTagName("td")[2];
          td5 = tr[i].getElementsByTagName("td")[3];
          td6 = tr[i].getElementsByTagName("td")[4];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else if(td2){
             txtValue = td2.textContent || td2.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else if(td3){
             txtValue = td3.textContent || td3.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if(td5){
             txtValue = td5.textContent || td5.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          }else if(td6){
             txtValue = td6.textContent || td6.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
            
          }else{
               tr[i].style.display = "none";
          } 
        }
        }
        }
        }     
        }
    }
}
 </script>
<script>

 </script>
 
 <script>
 
$("#caexcel").click(function(){
    var pro_order_id = $("#pro_order_id").val();
	  
  $.ajax({
            url:"<?php echo admin_url(); ?>production/export_production_report",
            method:"POST",
           data:{pro_order_id:pro_order_id},
            beforeSend: function () {
                $('#searchh3').css('display','block');
                $('#searchh3').css('color','blue');
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
        var html_filter_name =    $('.report_for').html();
        var PO =  $('#pro_order_id').val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
	     
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById("production_table").innerHTML+'</table><h5>RM Summery</h5>';
        tableData += '<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById('row_material_table').innerHTML+'</table><br>';
        tableData += '<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById('lower_table').innerHTML+'</table><br>';
        tableData += '<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById('cost_table').innerHTML+'</table>';
        
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:left;"colspan="3">Production Order Report </td>';
         heading_data += '</tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:left;"colspan="3">Production Order : '+PO+' </td>';
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
