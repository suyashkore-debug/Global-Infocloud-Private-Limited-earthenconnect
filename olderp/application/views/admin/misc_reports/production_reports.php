<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .production_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.production_report thead th { position: sticky; top: 0; z-index: 1; }
.production_report tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
   .production_report tr:hover {
    background-color: #ccc;
}

.production_report td:hover {
    cursor: pointer;
} 



</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-8">
        <div class="panel_s">
          <div class="panel-body">
              <div class="_buttons">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="act_name">AccountID</label>
                            <input type="text" name="act_name" id="act_name" class="form-control" value="">
                                        
                        </div>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <div class="form-group">
                        <input type="text" name="account_full_name" id="account_full_name" class="form-control" value="" readonly>
                        <input type="hidden" name="account_source" id="account_source" class="form-control" value="">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="act_name">Item ID</label>
                            <input type="text" name="item_code" id="item_code" style="width: 100%;border-radius: 2px;height: 30px;">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <br>
                            <div class="form-group">
                            <input type="text" name="item_fill_name" id="item_fill_name" class="form-control" value="<?php echo $value; ?>" readonly>
                            </div>
                        </div>
                    </div>
                 
                
                <div class="row"> 
                
                       <div class="col-md-3">
                           <?php  $from_date = "01/".date('m')."/".date('Y');?>
                            <?php echo render_date_input('from_date','FROM',$from_date);  ?>
                        </div>
                        
                        <div class="col-md-3">
                            <?php  $to_date = date('d/m/Y');?>
                            <?php echo render_date_input('to_date','TO',$to_date); ?>
                        </div>
                        
                        <div class="col-md-3">
                            <br>
                            <div class="form-group">
                           <select name="report_type" id="report_type" class="form-control">
                               <option value="1">Detailed</option>
                               <option value="2">Summary</option>
                               <!--<option value="3">ItemDetails</option>-->
                           </select>
                           </div>
                        </div>
                <div class="clearfix"></div>    
                   
                <div class="col-md-1">
                    
                    <div class="custom_button">
                        <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;">Show</button>
                    </div>
                </div>
                <div class="col-md-1">
                    
                    <div class="custom_button">
                        <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="production_report" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
                                <!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
                    </div>
                </div> 
                <div class="col-md-10">
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>  
             </div>
            </div>
            <div class="clearfix"></div>
            
        <?php
        //print_r($company_detail);
        ?>
            <div class="fixTableHead load_data">
              
            </div>
            <span id="searchh" style="display:none;">
                                Loading.....
                            </span>
    
              
          </div>
</div>
</div>
</div>
</div>
</div>

<style>
    .fixTableHead { overflow: auto;max-height: 60vh;position:relative;top: 0px; }
.fixTableHead thead th { position: sticky; top: 0; z-index: 1; }
.fixTableHead tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.fixTableHead table  { border-collapse: collapse; }
.fixTableHead th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.fixTableHead th     { background: #50607b;color: #fff !important; }


</style>
<?php init_tail(); ?>
<!--new update -->
<!--<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>-->
<script>
    
function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("production_report");
  tr = table.getElementsByTagName("tr");
for (i = 3; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];
      td2 = tr[i].getElementsByTagName("td")[2];
      td3 = tr[i].getElementsByTagName("td")[3];
      td4 = tr[i].getElementsByTagName("td")[4];
      td5 = tr[i].getElementsByTagName("td")[5];
      td6 = tr[i].getElementsByTagName("td")[6];
      td7 = tr[i].getElementsByTagName("td")[7];
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
    }
    }     
  }
}
}
}
}
}
</script>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    

// Initialize For Account
     $( "#act_name" ).autocomplete({
        
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/misc_reports/accountlist",
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
          
          
          $('#act_name').val(ui.item.value); // display the selected text
          $('#account_full_name').val(ui.item.label); // display the selected text
          $('#account_source').val(ui.item.source);
            return false;      
            
        }
      });
    $('#act_name').on('focus',function(){
        $('#act_name').val('');
        $('#account_full_name').val('');
        var ItemID = $('#item_code').val();
        if(ItemID == "")
        {
            $("#report_type").children().remove();
        // APPEND OR INSERT DATA TO SELECT ELEMENT.
            $('#report_type').append('<option value="1">Detailed</option>');
            $('#report_type').append('<option value="2">Summary</option>');
            $('#report_type').append('<option value="3">ItemDetails</option>');
            $("#report_type").selectpicker("refresh");
        }
    })
    
     $('#item_code').on('focus',function(){
        $('#item_code').val('');
        $('#item_fill_name').val('');
        var act_name = $('#act_name').val();
        if(act_name == "")
        {
            $("#report_type").children().remove();
        // APPEND OR INSERT DATA TO SELECT ELEMENT.
            $('#report_type').append('<option value="1">Detailed</option>');
            $('#report_type').append('<option value="2">Summary</option>');
            $('#report_type').append('<option value="3">ItemDetails</option>');
            $("#report_type").selectpicker("refresh");
        }
    })
    $('#act_name').on('blur',function(){
         
        var act_id = $(this).val();
        $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/get_account_details",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{act_id:act_id,},
          
          success:function(data){
            var ItemID = $('#item_code').val();
            if (data == 0 || data == null){
                if(act_id !== ""){
                   alert("AccountID not found..."); 
                }
                $('#act_name').val('');
                $('#account_full_name').val('');
                if(ItemID == ""){
                    $("#report_type").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#report_type').append('<option value="1">Detailed</option>');
                    $('#report_type').append('<option value="2">Summary</option>');
                    $('#report_type').append('<option value="3">ItemDetails</option>');
                    $("#report_type").selectpicker("refresh");
                }
                
            }else{
                if(data.account_data == null){
                    var source_type = 'staff';
                    $('#account_full_name').val(data.staff_data.firstname);
                }else{
                     var source_type = 'con';
                    $('#account_full_name').val(data.account_data.company);
                }
                $('#account_source').val(source_type);
                $("#report_type").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                $('#report_type').append('<option value="3">ItemDetails</option>');
                $("#report_type").selectpicker("refresh");
                $('#search_data').focus();
            }
            
            $('#search_data').focus();
          }
        });
        
     })
     
    $('#item_code').on('blur',function(){
         
        var ItemID = $(this).val();
        $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/get_item_details",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{ItemID:ItemID,},
          
          success:function(data){
            var AccounID = $('#act_name').val();
            if (data == 0 || data == null){
                if(ItemID !== ""){
                    alert("ItemID not found...");
                }
                
                $('#item_code').val('');
                $('#item_fill_name').val('');
                if(AccounID == ""){
                    $("#report_type").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#report_type').append('<option value="1">Detailed</option>');
                    $('#report_type').append('<option value="2">Summary</option>');
                    $('#report_type').append('<option value="3">ItemDetails</option>');
                    $("#report_type").selectpicker("refresh");
                }
                
            }else{
                $('#item_fill_name').val(data.description);
                
                $("#report_type").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                $('#report_type').append('<option value="3">ItemDetails</option>');
                             
                $("#report_type").selectpicker("refresh");
                $('#search_data').focus();
                
            }
            
            
          }
        });
        
     })
     
    $('#report_type').on('change',function(){
         
        var report_type = $(this).val();
        
        if(report_type == 3){
            var ItemID = $('#item_code').val();
            var AccounID = $('#act_name').val();
            if(ItemID == ""){
                alert("Enter ItemID"); 
                    $("#report_type").children().remove();
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#report_type').append('<option value="1">Detailed</option>');
                    $('#report_type').append('<option value="2">Summary</option>');
                    $('#report_type').append('<option value="3">ItemDetails</option>');
                    $("#report_type").selectpicker("refresh");
                    //$('#item_code').focus();
            }
        }
    });
      // Initialize For Account
     $( "#item_code" ).autocomplete({
         
        source: function( request, response ) {
          // Fetch data
          
          $.ajax({
            url: "<?=base_url()?>admin/misc_reports/itemlist",
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
          
          
          $('#item_code').val(ui.item.value); // display the selected text
          $('#item_fill_name').val(ui.item.label); // display the selected text
          $('#search_data').focus();
            return false;      
            
        }
      });
 
 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var report_type = $("#report_type").val();
	    var accountID = $("#act_name").val();
	    var accountName = $("#account_full_name").val();
	    var ItemID = $("#item_code").val();
	    var Itemname = $("#item_fill_name").val();
	    var source = $("#account_source").val();
	    
	        $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/get_production_data",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{from_date:from_date, to_date:to_date, report_type:report_type,accountID:accountID,ItemID:ItemID,accountName:accountName,Itemname:Itemname,source:source},
          beforeSend: function () {
                   
            $('#searchh').css('display','block');
            $('.load_data').css('display','none');
            
         },
          complete: function () {
                                
            $('.load_data').css('display','');
            $('#searchh').css('display','none');
         },
          success:function(data){
              
                $('.load_data').html(data);
           
            
          }
        });
	    
 });

});

 $("#caexcel").click(function(){
   var from_date = $("#from_date").val();
	     var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var report_type = $("#report_type").val();
	    var accountID = $("#act_name").val();
	    var accountName = $("#account_full_name").val();
	    var ItemID = $("#item_code").val();
	    var Itemname = $("#item_fill_name").val();
	    var source = $("#account_source").val();
	  
  $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/export_production_report",
            method:"POST",
            data:{from_date:from_date, to_date:to_date, report_type:report_type,accountID:accountID,ItemID:ItemID,accountName:accountName,Itemname:Itemname,source:source},
          beforeSend: function () {
               
                
            },
            complete: function () {
                
            },
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
            }
        });
});

function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
             // Just this once, load all data from the server...
             data.start = 0;
             data.length = 2147483647;
             dt.one('preDraw', function (e, settings) {
                 // Call the original action function
                 if (button[0].className.indexOf('buttons-copy') >= 0) {
                     $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                     $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                     $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                     $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                         $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                         $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
                 } else if (button[0].className.indexOf('buttons-print') >= 0) {
                     $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                 }
                 dt.one('preXhr', function (e, s, data) {
                     // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                     // Set the property to what it was before exporting.
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                 // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                 setTimeout(dt.ajax.reload, 0);
                 // Prevent rendering of the full data to the DOM
                 return false;
             });
         });
         // Requery the server with the new one-time export settings
         dt.ajax.reload();
     }
     
    $(document).ready(function() {
  $('tbody').scroll(function(e) { //detect a scroll event on the tbody
  	/*
    Setting the thead left value to the negative valule of tbody.scrollLeft will make it track the movement
    of the tbody element. Setting an elements left value to that of the tbody.scrollLeft left makes it maintain 			it's relative position at the left of the table.    
    */
    $('thead').css("left", -$("tbody").scrollLeft()); //fix the thead relative to the body scrolling
    $('thead th:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first cell of the header
    $('tbody td:nth-child(1)').css("left", $("tbody").scrollLeft()); //fix the first column of tdbody
  });
});
</script>

<style>
    input[type=checkbox], input[type=radio] {
    margin: 4px 4px 0px;
    line-height: normal;
}
</style>

<script>
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


