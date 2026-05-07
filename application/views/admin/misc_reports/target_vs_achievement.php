<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php  init_head(); ?>
<style>
    .table-table_staff tbody{
  display: block;
  max-height: 450px;
  overflow-y: scroll;
  width: calc(100% - -8.9em);
}
.table-table_staff thead, .table-table_staff tbody tr{
  display: table;
  table-layout: fixed;
  width: 100%;
  
}
.table-table_staff thead{
  width: calc(100% - -5.9em);
}
.table-table_staff thead{
  position: relative;
}
.table-table_staff thead th:last-child:after{
  content: ' ';
  position: absolute;
  background-color: #337ab7;
  width: 1.3em;
  height: 38px;
  right: -1.3em;
  top: 0;
  border-bottom: 2px solid #ddd;
}

/*.staff_name{*/
/*width:21%;*/
/*}*/
.table-table_staff th td{padding: 32px -20px 12px 14px;
}

.fontsize{
		font-size:13px;
	}
.fontsize2{
		font-size:15px;
	}

</style>
<style>
    .table-daily_report          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
.table-daily_report tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
					
						<form method="post" action="<?php echo admin_url('misc_reports/submit_targetSale');?>">
						<div class="row">

							<!-- fillter by teammanage -->
							<!--<div class="col-md-2 pull-right hide">
								<input type="text" id="staff_dep_tree" name="staff_dep_tree" class="selectpicker" placeholder="<?php echo _l('hr_team_manage'); ?>" autocomplete="off">
								<input type="hidden" name="staff_tree" id="staff_tree"/>
							</div>-->

							<!--<div class="col-md-3 pull-right">
								<select name="status_work[]" class="selectpicker" multiple="true" id="status_work" data-width="100%" data-none-selected-text="<?php echo _l('hr_status_label'); ?>"> 
									<option value="<?php echo 'working' ?>"><?php echo _l('hr_working'); ?></option>
									<option value="<?php echo 'maternity_leave'; ?>"><?php echo _l('hr_maternity_leave'); ?></option>
									<option value="<?php echo 'inactivity'; ?>">Not Working</option>
								</select>
							</div>-->
						
							<div class="col-md-2 ">
							     <?php $month = date('m');?>
								<!--<label for="month_data">Month</label>-->
        <!--                            <input type="month" id="month_data" name="month_data">-->
                                <div class="form-group">
                                    <label class="control-label" for="month_data">Month</label>
                                    <select name="month_data" class="selectpicker form-control" id="month_data" data-none-selected-text="<?php echo _l('Select Month'); ?>" data-live-search="true">
                                      <option></option>
                                        <option value="04" <?php if($month == '04'){ echo 'Selected';}   ?>>Apr - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="05" <?php if($month == '05'){ echo 'Selected';}   ?>>May - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="06" <?php if($month == '06'){ echo 'Selected';}   ?>>Jun  - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="07" <?php if($month == '07'){ echo 'Selected';}   ?>>Jul - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="08" <?php if($month == '08'){ echo 'Selected';}   ?> >Aug - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="09" <?php if($month == '09'){ echo 'Selected';}   ?> >Sep - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="10" <?php if($month == '10'){ echo 'Selected';}   ?> >Oct - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="11" <?php if($month == '11'){ echo 'Selected';}   ?> >Nov - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                        <option value="12" <?php if($month == '12'){ echo 'Selected';}   ?> >Dec - <?php  echo $this->session->userdata('finacial_year'); ?></option>
                                         <option value="01" <?php if($month == '01'){ echo 'Selected';}   ?> >Jan - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
                                        <option value="02" <?php if($month == '02'){ echo 'Selected';}   ?> >Feb - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
                                        <option value="03" <?php if($month == '03'){ echo 'Selected';}   ?> >Mar - <?php  echo $this->session->userdata('finacial_year')+1; ?></option>
                                    </select>
                                </div> 
							</div>
						
						
						<input type="hidden" id="staff_account_name" name="staff_account_name" >
						
							<div class="col-md-3 leads-filter-column">
							    	<label for="month_data">Staff</label>
								<select name="Staff_AccountID" class="selectpicker" id="Staff_AccountID" data-width="100%"  data-live-search="true" data-none-selected-text="<?php echo _l('Select Staff Name'); ?>"> 
									<option value=""></option>
									<?php 
									foreach ($staff as $value) { ?>
										<option data-name="<?php echo $value['firstname'].' '.$value['lastname'] ?>" data-id="<?php echo html_entity_decode($value['AccountID']); ?>" value="<?php echo html_entity_decode($value['AccountID']); ?>"><?php echo $value['firstname'].' '.$value['lastname'] ?></option>
									<?php }
									?>              
								</select>
							</div>
							
							


						</div>
						<br>
						
						<div class="row">
							<div class="col-md-3">
							    <a class="btn btn-info pull-left mleft5 search_data" style="" id="search_data">Show</a>
                             &nbsp;<a class="btn btn-default pull-left mleft5 buttons-html5" onclick="printPage();" style=""  tabindex="0" aria-controls="table-daily_report"><span>Print</span></a>
           &nbsp;<a class="btn btn-default pull-left mleft5 buttons-html5"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
                   
							 </div>
							 <div class="col-md-7">
							     <span ><b style="color:red;">Note:</b> Report will shown in Target/Achievement format. (For ex. Target=100 & Achievement=90 then report will show 100/90)</span>
							 </div>
							 <div class="col-md-2">
							     <input type="text" id="myInput1" class="form-control" onkeyup="myFunction2()" placeholder="Search.." title="Type in a name" style="float: right;">
							 </div>
						</div>

						<div class="row">
						    
							<div class="col-md-12">
							<!--
								
								  <div class="clearfix mtop20"></div>
								   &nbsp;&nbsp;-->
            <div class="table-daily_report tableFixHead2"> 
             
              <table class="tree table table-striped table-bordered table-daily_report tableFixHead2 " id="table-daily_report" width="100%">
                  
             
               
              </table>   
            </div>
            <span id="searchh2" style="display:none;">
                                Loading.....
                            </span>
                 
						

								<?php
							
									?>
								</div>
								 <!--<button type="submit" class="btn btn-info pull-right mleft5 " style="margin-top: 19px;">Submit</button>-->
							</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<style>

</style>
	<div id="modal_wrapper"></div>
	<?php init_tail(); ?>
	<?php 
	?>
	<script>
	 $(document).ready(function(){
	 
  function load_data(staff_d,month_data,staff_account_name,fullMonth,account_name)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>misc_reports/get_target_achivement",
      dataType:"json",
      method:"POST",
      data:{staff_d:staff_d, month_data:month_data,staff_account_name:staff_account_name},
      beforeSend: function () {
               
        $('#searchh2').css('display','block');
        $('.table-daily_report tbody').css('display','none');
        $('.table-daily_report tfoot').css('display','none');
        
     },
      complete: function () {
                            
        $('.table-daily_report tbody').css('display','');
        $('.table-daily_report tfoot').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
       
           $('#table-daily_report ').html(data);
        var msg = " Month: "+fullMonth+", Staff: "+account_name;
	    $(".report_for").text(msg);
	    
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
    
              $('.target_data_value').on('keypress',function (event) {
                  //console.log('t')
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
    var input = $(this).val();
    if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
        event.preventDefault();
    }
});
     
  
      }
    }); 
  }

 $('#search_data').on('click',function(){
        var month_data = $("#month_data").val();
	    var staff_d = $("#Staff_AccountID").val();
	    var staff_account_name = $("#staff_account_name").val();
	   if(month_data == ''){
	      alert('Please select Month') 
	      return false;
	   }
	   //if(staff_d == ''){
	   //   alert('Please select Staff')
	   //   return false;
	   //}
	   
	    var account_name = $("#Staff_AccountID").find(':selected').attr('data-name')
	    const dt = new Date(month_data);
        const locale = navigator.languages != undefined ? navigator.languages[0] : navigator.language;
        const fullMonth = dt.toLocaleDateString(locale, {month: 'long'});
        // console.log(fullMonth);
	    
        load_data(staff_d,month_data,staff_account_name,fullMonth,account_name);
   
 });

  
  
});
</script>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
 <script>
 
       $("#Staff_AccountID").change(function(){
           var id = $(this).find(':selected').attr('data-id')

$('#staff_account_name').val(id);
});
        
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table-daily_report");
  tr = table.getElementsByTagName("tr");
for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
      td1 = tr[i].getElementsByTagName("td")[1];
      td2 = tr[i].getElementsByTagName("td")[2];
      td3 = tr[i].getElementsByTagName("td")[3];
      td4 = tr[i].getElementsByTagName("td")[4];
      td5 = tr[i].getElementsByTagName("td")[5];
      td6 = tr[i].getElementsByTagName("td")[6];
      td7 = tr[i].getElementsByTagName("td")[7];
      td8 = tr[i].getElementsByTagName("td")[8];
      td9 = tr[i].getElementsByTagName("td")[9];
      td10 = tr[i].getElementsByTagName("td")[10];
    //   td11 = tr[i].getElementsByTagName("td")[11];
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
        
      }else if(td8){
         txtValue = td8.textContent || td8.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td9){
         txtValue = td9.textContent || td9.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
        
      }else if(td10){
         txtValue = td10.textContent || td10.innerText;
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
}}
}
}

 </script>
 <script>
 
$("#caexcel").click(function(){
       $('.up_starting').remove();
 $('.up').remove();
 $('.down').remove();
  $(".tableFixHead2").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    action: newexportaction,
    name: "Worksheet Name",
    filename: "Traget vs Achievement", //do not include extension
    fileext: ".xlsx" // file extension
  }); 
  html_data = 'Party Name <span class="up_starting">  &#8593;</span><span class="down" style="display:none;"> &#8593;</span><span class="up" style="display:none;"> &#8595;</span>';

    $('#sl').html(html_data);
});

function newexportaction(e, dt, button, config) {
         var self = this;
         var oldStart = dt.settings()[0]._iDisplayStart;
         dt.one('preXhr', function (e, s, data) {
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
                    
                     settings._iDisplayStart = oldStart;
                     data.start = oldStart;
                 });
                  setTimeout(dt.ajax.reload, 0);
                  return false;
             });
         });
          dt.ajax.reload();
     }
</script>
<script type="text/javascript">
 function printPage(){
    var html_filter_name =    $('.report_for').html();
         var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
         heading_data += '<tr>';
         heading_data += '<td style="text-align:center;"colspan="3">Target vs Achievement</td>';
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
 </script>
 <script>
    
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
   
   
 
      
   
 </script>
</body>
</html>
