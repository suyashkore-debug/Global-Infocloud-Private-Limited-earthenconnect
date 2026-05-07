<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    
.stock_position          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.stock_position thead th { position: sticky; top: 0; z-index: 1; }
.stock_position tbody th { position: sticky; left: 0; }


table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
    
    
 .fixed_headers tbody td {
    border: 1px solid #E3E3E3;
    padding: 0px 5px; 
}
    
.fixed_headers thead tr th{
    background-color: #f5f5f5 !important;
    color: #333;
    height: 20px;
    /*width: 100%;*/
}
</style>
<style>
    .No-Padding {
    padding:0px;
}
.fixTableHead {
      overflow-y: auto;
      max-height: 175px;
    }
    .fixTableHead thead th {
      position: sticky;
      top: 0;
    }
    .fixTableHead table {
      border-collapse: collapse;        
      width: 100%;
      
    }
   .fixTableHead th,
    td {
      padding: 5px 5px;
      border: 2px solid #529432;
      white-space: nowrap;
    }
    .fixTableHead th {
      background: #51647c;
      padding: 5px 5px;
      text-align: left;
    vertical-align: middle;
    }
#itemdivision td { padding: 0px 5px !important; border:1px solid !important;font-size:11px; line-height:0.7!important;vertical-align: middle !important;}
#itemdivision th { padding: 0px 5px !important; border:1px solid !important;font-size:11px; line-height:0.7!important;vertical-align: middle !important;}

</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
		  
				<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Stock Cummulative</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
              <div class="_buttons">
                <div class="row"> 
                <div class="col-md-2">
                   <div class="">
                       <div class="col-md-12">
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
                                $FirstDateY = "01/04/20".$fy;
                                $attr = array(
                                    'disabled'=>true
                                    );
                            ?>   
                            <?php echo render_date_input('from_date','FROM',$FirstDateY,$attr);  ?>
                        </div>
                        
                        <div class="col-md-12">
                            
                            <?php echo render_date_input('to_date','TO',$to_date); ?>
                        </div>
                        <div class="col-md-12">
                            
                            <div class="form-group">
                                <label class="control-label">Group</label>
                                <select name="item_main_group" id="item_main_group" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
                                <?php
                                foreach ($main_item_group as $key => $value) {
                                ?>
                                    <option value="<?php echo $value["id"];?>"><?php echo $value["name"];?></option>
                                <?php
                                }
                                ?>
                                
                            </select>
                            </div>
                        </div>
                        
                        
                        
                        
             
                    </div> 
                </div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-12" style="border:1px solid #ccc;height:175px;padding: 0px;">
                        <div class='fixTableHead '>
                            <div class="form-group">
                                <table id="itemdivision" class="table-striped table-bordered itemdivision">
                                    <thead>
                                        <tr>
                                            
                                            <th style="border:none !important;"><input id="All" name="All" type="checkbox" value="true" onclick="toggle(this);">
                                            
                                                <input name="All" type="hidden" value="true"> &nbsp;All
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody style="display:grid;grid-template-columns: 3fr 3fr 3fr 3fr;" class="itemgroup_body" >
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                            
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    
                    <div class="row">
                        <!--<div class="col-md-8">
                            <div class="form-group" app-field-wrapper="GodownID">
                                <small class="req text-danger">* </small>
                                <label for="GodownID" class="form-label">GodownID</label> 
                                <select name="GodownID" id="GodownID" class="selectpicker form-control" data-none-selected-text="Non Selected" data-live-search="true" <?php echo $GodownStatus;?>>
                                    <option value="">ALL</option>
                                <?php
                                    foreach ($GodownData as $key => $value) {
                                ?>
                                        <option value="<?php echo $value['AccountID'];?>" <?php if($GodownID == $value['AccountID']){ echo 'selected';}?>><?php echo $value['AccountName'];?></option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>-->
                        
                    </div>
                    
                    
                    <div class="row">
                        
                        <div class="col-md-12">
                            <br>
                            <div class="custom_button">
                                <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;margin-right:5px">Show</button>
                               <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="stock_position" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
                                <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                            </div>
                        </div>
                        
                    </div>
                    
                    
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 23%;">
                            <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="width: 100%;">
                        </div>
                    </div>
                </div>
           
        
             </div>
        
               
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <span id="searchh" style="display:none;">Loading.....</span>
            <span id="searchh2" style="display:none;">Please wait exporting data.....</span>
                    
                    <div class="stock_position load_data">
                  
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
<!--new update -->
<script>
    
function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("stock_position");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    td1 = tr[i].getElementsByTagName("td")[1];
    td2 = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td1){
         txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if(td2){
         txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }    }}   
  }
}
</script>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    var main_item_group_id = $("#item_main_group").val();
    var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
	
	get_item_group(main_item_group_id,from_date,to_date);
 
  function get_item_group(main_item_group_id,from_date,to_date)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>misc_reports/get_item_groupFR_StkP",
      dataType:"JSON",
      method:"POST",
      data:{main_item_group_id:main_item_group_id,from_date:from_date,to_date:to_date},
      success:function(data){
          var html = '';
          if(data.length === 0){
            html += '<tr>';
            html += '<td style="border-bottom:none;width:100px;"> Item Group not available...</td></tr>';
            $('.itemgroup_body').html(html);
        }else{
            
            for(var count = 0; count < data.length; count++)
            {
                
            html += '<tr>';
            html += '<td style="border:none !important;">';
            html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
            html += '</td>';
            html += '<td style="border:none !important;">';
            html += '<label for="'+data[count].name+'" style="font-size:11px;">'+data[count].name+'</label>';
            html += '</td>';
            html += '</tr>';
            }
            toggle(true);
            $('.itemgroup_body').html(html);
        }
        }
    });
  }
 
  $('#from_date').on('change',function(){
        
        var main_item_group_id = $("#item_main_group").val();
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    get_item_group(main_item_group_id,from_date,to_date);
        
 });
 
 $('#to_date').on('change',function(){
        
        var main_item_group_id = $("#item_main_group").val();
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    get_item_group(main_item_group_id,from_date,to_date);
        
 });
 $('#item_main_group').on('change',function(){
     
        
	    var main_item_group_id = $("#item_main_group").val();
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    get_item_group(main_item_group_id,from_date,to_date);
        
 });
 
 $('#report_in3').on('change', function() {
	var id = $(this).val();
	//alert(id);
	var url = "<?php echo base_url(); ?>admin/sale_reports/staff_list_by_role";
        jQuery.ajax({
                type: 'POST',
                url:url,
                data: {id: id},
                dataType:'json',
                success: function(data) {
                           
                    $("#staff_name").children().remove();
                    $('#staff_name').append('<option value="">Non Selected</option>');
                    $.each(data, function (index, value) {
                    // APPEND OR INSERT DATA TO SELECT ELEMENT.
                    $('#staff_name').append('<option value="' + value.staffid + '">' + value.firstname +' '+ value.lastname + '</option>');
                    });
                               
                    $("#staff_name").selectpicker("refresh");
                             
                            
                    }
        });
	});


 
 $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var item_main_group = $("#item_main_group").val();
	    var GodownID = $("#GodownID").val();
	    var item_group = '';
	    var favorite = [];
            $.each($("input[name='chk']:checked"), function(){
                favorite.push($(this).val());
            });
	    var item_group = favorite.join(",");
	    //alert(item_group);

	    if(item_group == "" || item_group== null){
	        alert('please select item group');
	    }else{
	        $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/getCummulativeStock",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{from_date:from_date, to_date:to_date, item_group:item_group,item_main_group:item_main_group,GodownID:GodownID},
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
	    }
 });
});

 $("#caexcel").click(function(){
  var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var item_main_group = $("#item_main_group").val();
	    var item_group = '';
	    var favorite = [];
            $.each($("input[name='chk']:checked"), function(){
                favorite.push($(this).val());
            });
	    var item_group = favorite.join(",");
	    //alert(item_group);

	    if(item_group == "" || item_group== null){
	        alert('please select item group');
	    }else{
  $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/exportCummulativeStock",
            method:"POST",
           data:{from_date:from_date, to_date:to_date, item_group:item_group,item_main_group:item_main_group},
            beforeSend: function () {
               $('#searchh2').css('display','block');
            },
            complete: function () {
                $('#searchh2').css('display','none');
            },
            success:function(data){
                response = JSON.parse(data);
                window.location.href = response.site_url+response.filename;
            }
        });
	    } 
});

     
$(document).ready(function() {
  $('tbody').scroll(function(e) { //detect a scroll event on the tbody
  	
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
    
	$(document).on("click", ".sortable", function () {
		var table = $("#stock_position tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortable").removeClass("asc desc");
		$(".sortable span").remove();
		
		// Add sort classes and arrows
		$(this).addClass(ascending ? "asc" : "desc");
		$(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
		
		rows.sort(function (a, b) {
			var valA = $(a).find("td").eq(index).text().trim();
			var valB = $(b).find("td").eq(index).text().trim();
			
			if ($.isNumeric(valA) && $.isNumeric(valB)) {
				return ascending ? valA - valB : valB - valA;
				} else {
				return ascending
                ? valA.localeCompare(valB)
                : valB.localeCompare(valA);
			}
		});
		table.append(rows);
	});
});
</script> 

<script>
function toggle(source) {
    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i] != source)
            checkboxes[i].checked = source.checked;
    }
}
</script>

<script type="text/javascript">
 function printPage(){ 
        
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var comp_name = $("#comp_name").val();
	    var comp_addr = $("#comp_addr").val();
	    var filterdate = $("#filterdate").val();
	    var rate_base = $("#rate_base").val();
	    var filter_group = $("#filter_group").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .hide_in_print{ display:none; }</style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[1].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9">'+comp_name+'</td></tr><tr><td style="text-align:center;" colspan="9">'+comp_addr+'</td></tr>';
        
        heading_data += '<tr><td style="text-align:left;"colspan="9">'+filterdate+'</td></tr>';
        heading_data += '<tr><td style="text-align:left;"colspan="9">'+rate_base+'</td></tr>';
        heading_data += '<tr><td style="text-align:left;"colspan="9">'+filter_group+'</td></tr>';
        
         heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
 </script>
