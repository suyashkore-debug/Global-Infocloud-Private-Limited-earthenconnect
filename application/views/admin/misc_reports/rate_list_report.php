<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    
.stock_position          { overflow: auto;max-height: 55vh;width:100%;position:relative;top: 0px; }
.stock_position thead th { position: sticky; top: 0; z-index: 1; }
.stock_position tbody th { position: sticky; left: 0; }

 .fixed_headers tbody td {
    border: 1px solid #E3E3E3;
    padding: 0px 5px; 
}
    
.fixed_headers thead tr th{
    background-color: #f5f5f5 !important;
    color: #333;
    height: 20px;
}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
th     { background: #50607b;
    color: #fff !important; }
    
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-10">
        <div class="panel_s">
          <div class="panel-body">
				<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Item Rate List</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
              <div class="_buttons">
                <div class="row"> 
                <div class="col-md-3">
                   <div class="">
                       <div class="col-md-12">
                        <?php 
                            $selected = 'UP';
                            echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                        ?>  
                        </div>
                        
                        <div class="col-md-12">
                            <?php 
                              $selected = 22;
                                echo render_select('distributor_id',$groups,array('id','name'),'customer_groups',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex')));
                            ?>   
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Items</label>
                                <select name="item_data" id="item_data" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
                                    <option value="1">Active Item Only</option>
                                    <option value="2">All Item</option>
                            </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="custom_button">
                                <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;margin-top: 8px;">Show</button>
                            </div>
                        </div>
                        <div class="col-md-8" style="margin-top: 8px;">
                            <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="stock_position" href="#" id="caexcel" style="font-size:12px;"><span>Export</span></a>
                            <a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
                        </div>
                        
                    </div> 
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12" style="border:1px solid #ccc;height:222px">
                            <div class="row" id="item_group_show">
                                
                            </div>
                            
                        </div>
                        
                        
                    </div>
                </div>
             </div>
        
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-8">
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search .." title="Type in a name" style="float: right;width:100%;">
                </div>
            </div>
        <?php
        //print_r($company_detail);
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="stock_position load_data">
                </div>
            </div>
        </div>
            <span id="searchh" style="display:none;">Loading.....</span>
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
}
</script>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    finished_good = 1 // this why i take main_item_group_id = 1
    var main_item_group_id = 1;
	get_item_group(main_item_group_id);
 
  function get_item_group(main_item_group_id)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>misc_reports/get_item_group",
      dataType:"JSON",
      method:"POST",
      data:{main_item_group_id:main_item_group_id},
      success:function(data){
          var html = '';
        if(data.length === 0){
            
            html += '<p style="font-weight:bold;"> Item group not available</p>';
            $('#item_group_show').html(html);
        }else{
            
            html += '<table class="fixed_headers">';
            html += '<thead>';
            html += '<tr>';
            html += '<th style="padding:5px;">';
            html += '<input id="All" name="All" type="checkbox" value="true" onclick="toggle(this);" checked><input name="All" type="hidden" value="true"> ';
            html += '</th>';
            html += '<th>';
            html += '<label ">All</label>';
            html += '</th>';
            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';
            html += '<tr>';
            for(var count = 0; count <= 4; count++)
            {
            if(data.length <= count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
            html += '</tr>';
            if(data.length > 5 ){
                html += '<tr>';
                for(var count = 5; count <= 9; count++)
            {
            if(data.length <= count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
           
            }
            html += '</tr>';
            
            //3rd row
            html += '</tr>';
            if(data.length > 9 ){
                html += '<tr>';
                for(var count = 10; count <= 14; count++)
            {
            if(data.length <= count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
                html += '</tr>';
            }
            
            //4th row
            html += '</tr>';
            if(data.length > 14 ){
                html += '<tr>';
                for(var count = 15; count <=19; count++)
            {
                
           
            if(data.length <= count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
                html += '</tr>';
            }
            //5th row
            html += '</tr>';
            if(data.length > 19 ){
                html += '<tr>';
                for(var count = 20; count <=24; count++)
            {
                
            if(data.length <= count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
                html += '</tr>';
            }
            
            //6th row
            html += '</tr>';
            if(data.length > 24 ){
                html += '<tr>';
                for(var count = 25; count <=29; count++)
            {
               
            if(data.length < count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
           
            }
                html += '</tr>';
            }
            
            //7th row
            html += '</tr>';
            if(data.length > 29 ){
                html += '<tr>';
                for(var count = 30; count <=34; count++)
            {
                
           
            if(data.length < count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
                html += '</tr>';
            }
            
            //8th row
            html += '</tr>';
            if(data.length > 34 ){
                html += '<tr>';
                for(var count = 35; count <=35; count++)
            {
                
           
            if(data.length < count){
                
            }else{
                html += '<td style="border:none !important;width:2%;">';
                html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'" checked>';
                html += '</td>';
                html += '<td style="border:none !important;width:23%;">';
                html += '<label for="'+data[count].name+'" >'+data[count].name+'</label>';
                html += '</td>';
            }
            }
                html += '</tr>';
            }
            
            }
            html += '</tbody>';
            html += '</table>';
            $('#item_group_show').html(html);
        }
      }
    });
  }
 
 $('#item_main_group').on('change',function(){
        var main_item_group_id = $("#item_main_group").val();
	    get_item_group(main_item_group_id);
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
        var states = $("#states").val();
        if(states == ''){
            states = 'UP';
        }
	    var distributor_id = $("#distributor_id").val();
	     if(distributor_id == ''){
            // distributor_id = 22;
        }
	    var item_data = $("#item_data").val();
	    var item_group = '';
	    var favorite = [];
            $.each($("input[name='chk']:checked"), function(){
                favorite.push($(this).val());
            });
	    var item_group = favorite.join(",");
	   
	    $.ajax({
          url:"<?php echo admin_url(); ?>misc_reports/get_rate_report",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{states:states, distributor_id:distributor_id, item_group:item_group,item_data:item_data},
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
  var states = $("#states").val();
        if(states == ''){
            states = 'UP';
        }
	    var distributor_id = $("#distributor_id").val();
	     if(distributor_id == ''){
            // distributor_id = 22;
        }
	    var item_data = $("#item_data").val();
	    var item_group = '';
	    var favorite = [];
            $.each($("input[name='chk']:checked"), function(){
                favorite.push($(this).val());
            });
	    var item_group = favorite.join(","); 
	    
	    $.ajax({
            url:"<?php echo admin_url(); ?>misc_reports/export_rate_list",
            method:"POST",
            data:{states:states, distributor_id:distributor_id, item_group:item_group,item_data:item_data},
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
         var comp_name = $(".co_name").html();
         var comp_addr = $(".co_add").html();
         var state_dist = $(".state_dist").html();
         var item_grp = $(".item_grp").html();
        //  console.log(print);
        //  return false;
    //     var from_date = $("#from_date").val();
	   // var to_date = $("#to_date").val();
	   // var comp_name = $("#comp_name").val();
	   // var comp_addr = $("#comp_addr").val();
	   // var filterdate = $("#filterdate").val();
	   // var rate_base = $("#rate_base").val();
	    var filter_group = $("#filter_group").val();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} .hide_in_print{ display:none; }</style>';
    var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[1].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9">'+comp_name+'</td></tr><tr><td style="text-align:center;" colspan="9">'+comp_addr+'</td></tr>';
        if(state_dist != undefined){
        heading_data += '<tr><td style="text-align:left;"colspan="9">'+state_dist+'</td></tr>';
        }
        if(item_grp != undefined){
             heading_data += '<tr><td style="text-align:left;"colspan="9">'+item_grp+'</td></tr>';
        }
       
        // heading_data += '<tr><td style="text-align:left;"colspan="9">'+filter_group+'</td></tr>';
        
         heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
   newWin= window.open("");
   newWin.document.write(print_data);
   newWin.print();
   newWin.close();
    };
	
	$(document).on("click", ".sortablePop", function () {
		var table = $("#stock_position tbody");
		var rows = table.find("tr").toArray();
		var index = $(this).index();
		var ascending = !$(this).hasClass("asc");
		
		
		// Remove existing sort classes and reset arrows
		$(".sortablePop").removeClass("asc desc");
		$(".sortablePop span").remove();
		
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
 </script>
