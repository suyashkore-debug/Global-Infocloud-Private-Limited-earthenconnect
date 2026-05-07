<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
.table-daily_report tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
   .table-daily_report tr:hover {
    background-color: #ccc;
}

.table-daily_report td:hover {
    cursor: pointer;
} 
#sl :hover {
    /*background-color: #ccc;*/
    cursor: pointer;
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
      <?php if(has_permission_new('cost_report','','view')){ ?>
      <div class="_buttons">
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
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date','From Date',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date','To Date',$to_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <div class="form-group" app-field-wrapper="status_list">
                       <label for="Product" class="control-label"><?php echo _l('Product Name'); ?></label>
                       <input type="text" name="product_name" id="product_name" class="form-control"  >
                      
                   </div>
                </div>
         <div class="col-md-3">
            <button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
        
      </div>
     
      </div>
         <div class="clearfix"></div>
      <hr class="hr-panel-heading" />
        
    <?php } ?>
    
        <?php
        //print_r($company_detail);
        ?>
        <span id="searchh2" style="display:none;">please wait fetching data...</span>
        <span id="searchh3" style="display:none;">please wait exporting data...</span>
            <div class="table-daily_report tableFixHead2">
             <div class="production_table">
                 
             </div>
             <br>
             <br>
             <div class="col-md-6">
            <div class="custom_button">
            &nbsp;<a class="btn btn-default buttons-excel buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
            <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
            </div>
        </div>
        <div class="col-md-6">
            <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search .."  style="float: right;">
            
        </div>
              <div class="row_material_table">
                 
             </div>
             
            </div>
            
                            <br>
                            <br>
     <div class="lower_table">
   
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
 $( "#product_name" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/production/product_list",
            type: 'post',
            dataType: "json",
            data: {
              search: request.term
            },
            success: function( data ) {
              response( data );
            }
          });
        },
        select: function (event, ui) {
             
       //alert(Conform);
     
          $('#product_name').val(ui.item.value);
          
        //   get_sale_item(ui.item.value);
            $("#product_name").focus();
            return false;
   
       
        }
      });
      
  function load_data(product_name,from_date,to_date)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>production/load_table_production_cost_report",
      dataType:"JSON",
      method:"POST",
      data:{product_name:product_name,from_date:from_date,to_date:to_date},
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
      
        
         $('.production_table').html(data.production_table);
         $('.row_material_table').html(data.row_material_table);
         $('.lower_table').html(data.lower_table);
    
      }
    });
  }
  
 $('#search_data').on('click',function(){
        var product_name = $("#product_name").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
	    if(product_name == ''){
	       alert('please select the Product name ');
	       return false;
	   }else{
        load_data(product_name,from_date,to_date);
	   }
 });

});
     function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("row_material_table");
  tr = table.getElementsByTagName("tr");
 for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
      td1 = tr[i].getElementsByTagName("td")[2];
      td2 = tr[i].getElementsByTagName("td")[3];
      td3 = tr[i].getElementsByTagName("td")[4];
      td4 = tr[i].getElementsByTagName("td")[5];
      td5 = tr[i].getElementsByTagName("td")[6];
      td6 = tr[i].getElementsByTagName("td")[7];
      td7 = tr[i].getElementsByTagName("td")[8];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td1){
         txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else if(td2){
         txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td3){
         txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td4){
         txtValue = td4.textContent || td4.innerText;
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
        
      }else if(td7){
         txtValue = td7.textContent || td7.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else{
           tr[i].style.display = "none";
      } 
    }
    }
    }}}}
    }     
  }
}
}
 </script>
<script>

 </script>
 
 <script>
 
$("#caexcel").click(function(){
    var product_name = $("#product_name").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
	   if(product_name == ''){
	       alert('please select the Product name ');
	       return false;
	   }else{
  $.ajax({
            url:"<?php echo admin_url(); ?>production/export_production_cost_report",
            method:"POST",
            data:{product_name:product_name,from_date:from_date,to_date:to_date},
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
	   }
    
});

</script>
<script type="text/javascript">
 function printPage(){
    //   var html_filter_name =    $('.report_for').html();
         var product_name = $("#product_name").val();
        var from_date = $("#from_date").val();
        var to_date = $("#to_date").val();
        var html_filter_name = '<span>Date from: '+from_date+' date to: '+to_date+'  </span>';
        if(product_name != ''){
             html_filter_name += '<span>Product : '+product_name;
        }
        
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
	     
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById("production_table").innerHTML+'</table><h5>RM Summery</h5>';
        tableData += '<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById('row_material_table').innerHTML+'</table><br>';
        tableData += '<table border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementById('lower_table').innerHTML+'</table>';
        
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">Production Cost Report</td>';
         heading_data += '</tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">'+html_filter_name+'</td>';
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
 $(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    
    
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y > fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
   /* console.log(minStartDate);
    console.log(maxEndDate_new);*/
    
    $('#from_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    $('#to_date').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
    
    
    });
 </script>
</body>
</html>
