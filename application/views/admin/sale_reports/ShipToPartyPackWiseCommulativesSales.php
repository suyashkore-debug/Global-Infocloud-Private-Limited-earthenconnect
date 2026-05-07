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
            					<li class="breadcrumb-item active" aria-current="page"><b>Ship To Party PackWise Commulative Sales</b></li>
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
											<?php echo render_date_input('from_date','From Date',$from_date);  ?>
										</div>
										
										<div class="col-md-2">
											<?php echo render_date_input('to_date','To Date',$to_date); ?>
										</div>
										
										<div class="col-md-4">
											<?php echo render_select( 'states',$states,array( 'short_name',array( 'state_name')), 'client_state',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'))); ?>
										</div>
										
										<div class="col-md-3">
											<div class="form-group">
												<label class="control-label">Location Type</label>
												<select name="loc_type" id="loc_type" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
													<option value="1">Local</option>
													<option value="2">OutStation</option>
													<option value="3">NotDefined</option>
												</select>
											</div>
										</div>
										
										
										
										<div class="clearfix"></div>
										
										
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Report Type</label>
												<select name="report_in2" id="report_in2" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
													<option value="netsales">Net Sales</option>
													<option value="sales">Sales</option>
													<option value="freshrtn">Fresh Return</option>
													<option value="damage">Damage</option>
													
												</select>
											</div>
										</div>
										
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Report In</label>
												<select name="report_in" id="report_in" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
													<option value="value">Value</option>
													<option value="unit">Unit</option>
													<option value="cases">Cases</option>
													<option value="tonnage">Tonnage</option>
												</select>
											</div>
										</div>
										<!--<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Staff Type</label>
												<select name="report_in3" id="report_in3" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
													<option value=""></option>
													<option value="vp">VP</option>
													<option value="dgm">DGM</option>
													<option value="4">RSM</option>
													<option value="5">ASM</option>
													<option value="6">ASE</option>
													<option value="7">TSI</option>
													<option value="8">SO</option>
												</select>
											</div>
										</div>
										
										<div class="col-md-4">
											<div class="form-group">
												<label class="control-label">Staff Name</label>
												<select name="staff_name" id="staff_name" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
													<option value=""></option>
													
												</select>
											</div>
										</div>-->
										
										<div class="col-md-4">
											<?php echo render_select('client_type',$groups,array('id','name'),'distributor_type'); ?>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<label class="control-label">Values In</label>
												<select name="values_in" id="values_in" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true">
													
													<option value="1">With GST</option>
													<option value="2">Without GST</option>
													
												</select>
											</div>
										</div>
										<div class="clearfix"></div>
										<div class="col-md-2" >
											
											<button class="btn btn-info pull-left mleft5 search_data" id="search_data" style="font-size:12px;padding:8px 15px;">Show</button>
										</div>
										
										<div class="col-md-2" >
											<div class="custom_button">
												
												<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="daily_report" href="#" id="caexcel" style="font-size:12px;padding:8px 15px;"><span>Export To Excel</span></a>
												<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
											</div>
										</div>
										
										<div class="col-md-8" style="margin-top:10px;">
											<p style="color:red;">Note : Amount includes GST</p>
										</div>
										
									</div> 
								</div>
								<div class="col-md-4" style="padding-left: 1px">
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<div class='fixTableHead1 '>
													<table id="itemdivision" class="fixed_headers table-striped table-bordered" width="100%">
														<thead>
															<tr>
																<th style="padding:5px;outline:none;"><input id="All" name="All" type="checkbox" value="true" onclick="toggle(this);"><input name="All" type="hidden" value="false"> &nbsp;All
																</th>
															</tr>
														</thead>
														<tbody class="itemgroup_body" style="display:grid;grid-template-columns: 4fr 4fr 4fr;">
															
														</tbody>
													</table>
												</div>
												
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
	background-color: #438EB9;
	padding: 5px 5px;
	text-align: left;
    vertical-align: middle;
    }
	.fixed_headers th, td { padding: 1px 5px !important; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	
	.fixed_headers thead {
    background-color: #f5f5f5;
    color: #FFF;
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
<!--<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>-->
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
							} else {
							tr[i].style.display = "none";
						}}}
			}       
		}
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		
		var from_date = $("#from_date").val();
		var to_date = $("#to_date").val();
		get_item_group(from_date,to_date);
		
		function get_item_group(from_date,to_date)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>sale_reports/get_item_group",
				dataType:"JSON",
				method:"POST",
				data:{from_date:from_date, to_date:to_date},
				success:function(data){
					var html = '';
					if(data.length === 0){
						html += '<tr>';
						html += '<td style="border-bottom:none;width:100px;outline:none;"> No sale for selected date</td></tr>';
						$('.itemgroup_body').html(html);
						}else{
						
						for(var count = 0; count < data.length; count++)
						{
							
							html += '<tr>';
							html += '<td style="border:none !important;outline:none;">';
							html += '<input id="'+data[count].id+'" name="chk" class="chk" type="checkbox" value="'+data[count].id+'">';
							html += '</td>';
							html += '<td style="border:none !important;outline:none;">';
							html += '<label for="'+data[count].name+'" style="font-size:11px;">'+data[count].name+'</label>';
							html += '</td>';
							html += '</tr>';
						}
						//$('#All').prop('checked', false); // Unchecks it
						toggle(true);
						$('.itemgroup_body').html(html);
					}
					
				}
			});
		}
		$('#from_date').on('change',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			get_item_group(from_date,to_date);
			
		});
		
		$('#to_date').on('change',function(){
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			get_item_group(from_date,to_date);
			
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
		    var AcountType = "ShipTo";
			var from_date = $("#from_date").val();
			var to_date = $("#to_date").val();
			var report_in = $("#report_in").val();
			var states = $("#states").val();
			var client_type = $("#client_type").val();
			var loc_type = $("#loc_type").val();
			var report_type = $("#report_in2").val();
			var staff_designation = $("#report_in3").val();
			var staff_id = $("#staff_name").val();
			var values_in = $("#values_in").val();
			var item_group = '';
			var favorite = [];
            $.each($("input[name='chk']:checked"), function(){
                favorite.push($(this).val());
			});
            //alert("My favourite sports are: " + favorite.join(", "));
			var item_group = favorite.join(",");
			var msg = "Sales Report "+from_date +" To " + to_date;
			$(".report_for").text(msg);
			
			if(item_group == "" || item_group== null){
				alert('please select item group');
				}else{
				$.ajax({
					//url:"<?php echo admin_url(); ?>sale_reports/shipto_get_commulative_data",
					url:"<?php echo admin_url(); ?>sale_reports/get_commulative_data",
					dataType:"JSON",
					method:"POST",
					cache: false,
					data:{from_date:from_date, to_date:to_date, report_in:report_in, states:states, client_type:client_type, item_group:item_group, loc_type:loc_type, 
					report_type:report_type,staff_designation:staff_designation,staff_id:staff_id,values_in:values_in,AcountType:AcountType},
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
	    var AcountType = "ShipTo";
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
	    var report_in = $("#report_in").val();
	    var states = $("#states").val();
	    var client_type = $("#client_type").val();
	    var loc_type = $("#loc_type").val();
	    var report_type = $("#report_in2").val();
	    var staff_designation = $("#report_in3").val();
	    var staff_id = $("#staff_name").val();
	    var values_in = $("#values_in").val();
	    var item_group = '';
	    var favorite = [];
		$.each($("input[name='chk']:checked"), function(){
			favorite.push($(this).val());
		});
		//alert("My favourite sports are: " + favorite.join(", "));
	    var item_group = favorite.join(",");
	    $.ajax({
            url:"<?php echo admin_url(); ?>sale_reports/export_party_commulative_report",
            method:"POST",
            data:{from_date:from_date, to_date:to_date, report_in:report_in, states:states, client_type:client_type, item_group:item_group, 
			loc_type:loc_type, report_type:report_type,staff_designation:staff_designation,staff_id:staff_id,values_in:values_in,AcountType:AcountType},
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
	.fixTableHead {
	overflow-y: auto;
	max-height: 350px;
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
    }
    .fixTableHead th {
	/*background-color: #438EB9;*/
	padding: 5px 5px;
	text-align: left;
    vertical-align: middle;
    color:#FFFFFF;
    }/*
	.fixTableHead td:nth-child(1){
    position: sticky;
    left: 0px;
    z-index: 1;
    background: #ffffff;
    
	}
	
	.fixTableHead td:nth-child(2){
    position: sticky;
    left: 33px;
    z-index: 1;
    background: #ffffff;
    
	}
	
	.fixTableHead td:nth-child(3){
    position: sticky;
    left: 110px;
    z-index: 1;
    background: #ffffff;
    
	}
	
	.fixTableHead th:nth-child(1){
    position: sticky;
    left: 0px;
    z-index: 99;
    background-color: #438EB9;
    color:#FFFFFF;
    
	}
	
	.fixTableHead th:nth-child(2){
    position: sticky;
    left: 33px;
    z-index: 99;
    background: #438EB9;
    color:#FFFFFF;
	}
	
	.fixTableHead th:nth-child(3){
    position: sticky;
    left: 110px;
    z-index: 99;
    background: #438EB9;
	color:#FFFFFF;
	}
    */
    .fixed_headers tbody {
	
    display: block;
    width: 100%;
	} 
	.acctname {
    white-space: nowrap;
	}
	
	.daily_report th, td { padding: 1px 5px !important; border:1px solid #ddd !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	
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

<style>
	thead, th{
	top:0px;
	position:sticky;
	z-index:20;
	}
	.col-id-no{
	left:0px;
	position:sticky !important;
	min-width:34px;
	background-color:#438eb9;
	color:#fff;
	}
	.fixed-header{
	z-index:50;
	}
	.col-id-ordid{
	left:34px;
	position:sticky !important;
	min-width:93px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-custname{
	left:127px;
	position:sticky !important;
	min-width:73px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-custstate{
	left:200px;
	position:sticky !important;
	min-width:135px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-ordtype{
	left:355px;
	position:sticky !important;
	min-width:78px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-saleid{
	left:433px;
	position:sticky !important;
	min-width:84px;
	background-color:#438eb9;
	color:#fff;
	}
	.col-id-saledate{
	left:517px;
	position:sticky !important;
	min-width:84px;
	background-color:#438eb9;
	color:#fff;
	}
	table.daily_report > th, td{
	outline:1px solid #ddd;
	}
	/*tfoot{
	bottom:0px;
	position:sticky;
	z-index:20;
	background: #438EB9;
	color: #FFF;
	}*/
</style>   