<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
				
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Order Vs Dispatch</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
              <div class="_buttons">
                <div class="row"> 
                <div class="col-md-8" style="padding: 1px">
                   <div class="">
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
                       <div class="col-md-2">
                            <?php echo render_date_input('from_date','FROM',$from_date);  ?>
                        </div>
                        
                        <div class="col-md-2">
                            <?php echo render_date_input('to_date','TO',$to_date); ?>
                        </div>
                        
                        <div class="col-md-4">
                            <?php echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                        </div>
						
                        <div class="col-md-4">
                            <?php echo render_select( 'PartyList',$PartyList,array( 'AccountID',array( 'company')), 'Party Name',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
                        </div>
                        <!--<div class="clearfix"></div>-->
                        <div class="col-md-4" style="display:none;">
                            <div class="form-group">
                                <label class="control-label">Staff Name</label>
                                <select name="staff_name" id="staff_name" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
                                <option value=""></option>
                            <?php
                            //print_r($SO);
                               foreach ($SO as $K => $value) {
                            ?>
                               <option value="<?php echo $value['staffid']; ?>"><?php echo $value['firstname'].' '.$value['lastname']; ?></option>
                            <?php
                               }
                            ?>
                            </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4"  style="display:none;">
                            <?php echo render_select('client_type',$groups,array('id','name'),'distributor_type'); ?>
                        </div>
                        
                        
                        <div class="col-md-2" >
                            <br>
                          <button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;padding:8px 15px;">Show</button>
                        </div>
                        
                        <div class="col-md-2" >
                        <div class="custom_button">
                            <br>
                        <a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="daily_report" href="#" id="caexcel" style="font-size:12px;padding:8px 15px;"><span>Export To Excel</span></a>
                        </div>
                        </div>
                    </div> 
                </div>
             </div>
        
               
            </div>
            <div class="clearfix"></div>
        <div class="row"> 
        <div class="col-md-6" >
            <span id="searchh3" style="display:none;">Please wait exporting data...</span>
            <span id="searchh" style="display:none;">Please wait loading data...</span>
        </div>
        <div class="col-md-6" >
         <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
        </div>   
        <div class="col-md-12" >
            <div class="fixTableHead load_data">
              
            </div>
            <!--<span id="searchh" style="display:none;">
                                Loading.....
                            </span>-->
        </div>
        </div>
              
          </div>
</div>
</div>
</div>
</div>
</div>

<style>
    .fixTableHead1 {
      overflow-y: auto;
      max-height: 150px;
    }
    .fixTableHead1 thead th {
      position: sticky;
      top: 0;
    }
    .fixTableHead1 table {
      border-collapse: collapse;        
      width: 100%;
      
    }
   .fixTableHead1 th,
    td {
      padding: 5px 5px;
      border: 2px solid #529432;
      white-space: nowrap;
    }
    .fixTableHead1 th {
      background: #ABDD93;
      padding: 5px 5px;
      text-align: left;
    vertical-align: middle;
    }
.fixed_headers th, td { padding: 1px 5px !important; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}

.fixed_headers thead {
    background-color: #f5f5f5;
    color: #333;
    height: 20px;
    width: 100%;
}
.fixed_headers {
    table-layout: fixed;
    border-collapse: collapse;
    border: 1px solid #E3E3E3;
    border-radius: 4px;
}
</style>
<?php init_tail(); ?>
<!--new update -->

<script>
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("daily_report");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
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
    if (td1) {
      txtValue = td1.textContent || td1.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td2) {
      txtValue = td2.textContent || td2.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td3) {
      txtValue = td3.textContent || td3.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td4) {
      txtValue = td4.textContent || td4.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td5) {
      txtValue = td5.textContent || td5.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td6) {
      txtValue = td6.textContent || td6.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td7) {
      txtValue = td7.textContent || td7.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td8) {
      txtValue = td8.textContent || td8.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td9) {
      txtValue = td9.textContent || td9.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      }else if (td10) {
      txtValue = td10.textContent || td10.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }}}}}}}}}}
    }       
  }
}
</script>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    
    var from_date = $("#from_date").val();
	var to_date = $("#to_date").val();
    $('#from_date').on('change',function(){
       
    });
 
    $('#to_date').on('change',function(){
            
    });
    $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var states = $("#states").val();
	    var client_type = $("#client_type").val();
	    var AccountID = $("#PartyList").val();
	    var staff_id = $("#staff_name").val();
	    
	    $.ajax({
          url:"<?php echo admin_url(); ?>sale_reports/GetOrderVsDispatch",
          dataType:"JSON",
          method:"POST",
          cache: false,
          data:{from_date:from_date, to_date:to_date,states:states, client_type:client_type,staff_id:staff_id,AccountID:AccountID},
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
	    var to_date = $("#to_date").val();
	    var states = $("#states").val();
	    var client_type = $("#client_type").val();
	    var AccountID = $("#PartyList").val();
	    var staff_id = $("#staff_name").val();
	    $.ajax({
            url:"<?php echo admin_url(); ?>sale_reports/GetOrderVsDispatchExport",
            method:"POST",
            data:{from_date:from_date, to_date:to_date,states:states, client_type:client_type,staff_id:staff_id,AccountID:AccountID},
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

<style>
.fixTableHead {
      overflow-y: auto;
      max-height: 350px;
    }
    
    .fixTableHead table {
      border-collapse: collapse;        
      width: 100%;
      
    }
   .fixTableHead th,
    td {
      padding: 5px 5px;
      border: 2px solid #529432;
    }
    .fixTableHead th {
      background: #ABDD93;
      padding: 5px 5px;
      text-align: left;
    vertical-align: middle;
    }

    .fixed_headers tbody {

    display: block;
    width: 100%;
}
.acctname {
    white-space: nowrap;
}

.daily_report th, td { padding: 1px 5px !important; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}

.col-md-2{
    padding:2px;
}
.col-md-1{
    padding:2px;
}
.col-md-4{
    padding:2px;
}
.col-md-3{
    padding:2px;
}
.fixed_headers tbody td {
    border: 1px solid #E3E3E3;
    padding: 0px 5px; 
}
#daily_report tr:hover {
    background-color: #ccc;
}

#daily_report td:hover {
    cursor: pointer;
}

</style>

<script>
$(document).ready(function(){
    var maxEndDate = new Date('Y/m/d');
    var fin_y = "<?php echo $this->session->userdata('finacial_year')?>";
    
    var year = "20"+fin_y;
    
    
    var cur_y = new Date().getFullYear().toString().substr(-2);
    if(cur_y => fin_y){
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20"+year2;
        
        var e_dat = new Date(year2_new+'/03/31');
        var maxEndDate_new = e_dat;
    }else{
         var maxEndDate_new = maxEndDate;
    }
    
    var minStartDate = new Date(year, 03);
  
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

$(document).on("click", ".sortable", function () {
		var table = $("#daily_report tbody");
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
</script> 


