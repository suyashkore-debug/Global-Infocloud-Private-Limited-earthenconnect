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
            					<li class="breadcrumb-item active text-capitalize"><b>Misc. Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Customer List </b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<!--<div class="_buttons">
							<?php if (has_permission_new('customers','','create')) { ?>
								<a href="<?php echo admin_url('clients/client'); ?>" class="btn btn-info mright5 test pull-left display-block">
								<?php echo _l('new_client'); ?></a>
								
							<?php } ?>
							
							<div class="visible-xs">
							<div class="clearfix"></div>
							</div>
							
						</div>-->
						<!-- <div class="clearfix"></div>
							<hr class="hr-panel-heading" />
						-->
						<div class="row ">
							
							<div class="col-md-2">
								<?php echo render_select('client_type',$groups,array('id','name'),'distributor_type'); ?>
							</div>
							<div class="col-md-2 leads-filter-column">
								
								<?php echo render_select('distributor_state',$state,array('short_name','state_name'),'distributor_state'); ?>
							</div>
							<div class="col-md-2 leads-filter-column">
								<?php echo render_select('division',$itemdivision,array('id','name'),'division'); ?>
							</div>
							<div class="col-md-2 leads-filter-column">
								<?php echo render_select('responsible_admin',$staff_list,array('staffid',array('firstname','lastname')),'responsible_admin'); ?>
							</div>
							
							<div class="col-md-2 leads-filter-column">
								<!--<?php echo render_select('status',$staffs,array('staffid',array('firstname','lastname')),'status','',array(),array(),'','',false); ?>-->
								<div class="form-group">
									<label class="control-label">Status</label>
									<select name="status" id="status" class="form-control">
										<option value="">Non selected</option>
										<option value="1">Active</option>
										<option value="0">InActive</option>
									</select>
								</div>
								
							</div>
							<button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
							
						</div>
						<?php if(has_permission_new('customers','','view') || have_assigned_customers()) {
							$where_summary = '';
							if(!has_permission_new('customers','','view')){
								$where_summary = ' AND userid IN (SELECT customer_id FROM '.db_prefix().'customer_admins WHERE staff_id='.get_staff_user_id().')';
							}
						?>
						
						<?php } ?>
						<hr class="hr-panel-heading" />
						<div class="row">
							<div class="col-md-9">
								<div class="custom_button">&nbsp;&nbsp;
									<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
									<!--<a class="dt-button buttons-pdf buttons-html5" tabindex="0" aria-controls="ca_datatable" href="#"><span>Export to PDF</span></a>-->
								</div>
							</div>
							<div class="col-md-3">
								<input type="text" id="myInput1" class="form-control" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
							</div>
						</div>
						
						<div class="table-daily_report tableFixHead2">
							
							
						</div>
						<span id="searchh2" style="display:none;">
							Loading.....
						</span>
						<span id="searchh3" style="display:none;">Please wait data exporting...</span>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script>
	
	$('#search_data').on('click',function(){
        var client_type = $("#client_type").val();
	    var distributor_state = $("#distributor_state").val();
	    var division = $("#division").val();
	    var responsible_admin = $("#responsible_admin").val();
	    var status = $("#status").val();
		
		$.ajax({
			url:"<?php echo admin_url(); ?>Clients/load_data_filter",
			dataType:"json",
			method:"POST",
			data:{client_type:client_type, distributor_state:distributor_state, division:division,responsible_admin:responsible_admin,status:status},
			beforeSend: function () {
				$('.tableFixHead2 table').remove();
				$('#searchh22').css('display','none');
				$('#searchh2').css('display','block');
				$('.table-daily_report tbody').css('display','none');
				
			},
			complete: function () {
				
				
				$('.table-daily_report tbody').css('display','');
				$('#searchh2').css('display','none');
				
			},
			success:function(data){
				
				$('.tableFixHead2').append(data.html);
				
			}
		});
		
        
	});
	
	
</script>
<script src="<?= base_url() ?>public/plugins/jquery.table2excel.js"></script>
<script>
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
<script>
	$("#caexcel").click(function(){
		
        var client_type = $("#client_type").val();
	    var distributor_state = $("#distributor_state").val();
	    var division = $("#division").val();
	    var responsible_admin = $("#responsible_admin").val();
	    var status = $("#status").val();
		$.ajax({
			url:"<?php echo admin_url(); ?>Clients/export_party_list",
			method:"POST",
			data:{client_type:client_type, distributor_state:distributor_state, division:division,responsible_admin:responsible_admin,status:status},
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
</script>
<script type="text/javascript">
	function printPage(){
        
		var htmlString = $('.report_for').html();
		//   $( this ).text( htmlString );
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->company_name; ?></td></tr><tr><td style="text-align:center;" colspan="3"><?php echo $company_detail->address; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">Customer Master</td>';
		heading_data += '</tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="3">'+htmlString+'</td>';
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
    var f_sl = 1;
    
    function dercment_increment(){
		
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
	};
    
    var f_sl1 = 1;
    
    function dercment_increment_account(){
		
		if ( $('.up').css('display') == 'none')
		{
			$(".up1").show()
			$(".down1").hide()
			}else{
			$(".up1").hide()
			$(".down1").show()
		}
        f_sl *= -1;
        var n = $(this).prevAll().length;
        sortTable(f_sl,n);
	};
	
	$(document).on("click", ".sortable", function () {
		var table = $("#table-daily_report tbody");
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
</body>
</html>
