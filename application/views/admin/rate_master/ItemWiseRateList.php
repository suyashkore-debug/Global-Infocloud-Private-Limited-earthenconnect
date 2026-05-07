<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="panel_s">
					<div class="panel-body">
						
						<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Sale Reports</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Item Wise Rate List</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
						<div class="_buttons">
							<div class="row">  
								<div class="col-md-4">
									<label class="control-label">Item List</label>
									<select name="ItemID" id="ItemID" class="selectpicker" data-none-selected-text="Non selected" data-width="100%" data-live-search="true" tabindex="-98">
									    <option value="">None selected</option>
										<?php
										foreach($ItemList as $val){
										?>
										    <option value="<?php echo $val["item_code"]?>"><?php echo $val["description"]?></option>
										<?php
										}
										?>
									</select>
								</div>
								
								<div class="col-md-2" style="margin-top:20px;">
								    <?php
    								        if (has_permission_new('ItemWiseRateList', '', 'view')) {
    								    ?>
									<button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
									<?php } ?>
								</div>
								<div class="col-md-6" style="margin-top:20px;">
    								<div class="custom_button">
    								    <?php
    								        if (has_permission_new('ItemWiseRateList', '', 'export')) {
    								    ?>
    									<a class="btn btn-default buttons-excel buttons-html5" tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
    									<?php } ?>
    									<?php
    								        if (has_permission_new('ItemWiseRateList', '', 'print')) {
    								    ?>
    									<a class="btn btn-default" href="javascript:void(0);" onclick="printPage();">Print</a>
    									<?php } ?>
    								</div>
    							</div>
    							
							</div>
						</div>
						<div class="clearfix"></div>
						
						<div class="row">
							
							<div class="col-md-7">
							    <span id="searchh3" style="display:none;">please wait exporting data....</span>
							    <span id="searchh2" style="display:none;">please wait fetching data....</span>
							</div>
							<div class="col-md-5">
								<input type="text" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
							</div>
						</div>
						
						<?php
							//print_r($company_detail);
						?>
						<div class="table-itemwise_rate_report">
							
							<table class="tree table table-striped table-bordered table-itemwise_rate_report" id="table-itemwise_rate_report" width="100%">
								
								<thead>
									<tr style="display:none;">
										<td colspan="9" ><h5 style="text-align:center;"><span style="font-size:15px;font-weight:700;"><?php echo $company_detail->company_name; ?></span><br><span style="font-size:10px;font-weight:600;"><?php echo $company_detail->address; ?></span><br><span class="report_for" style="font-size:10px;"></span></h5></td>
									</tr>
									<tr>
									    <th style="text-align:left;">Sr. No.</th>
										<th style="text-align:left;">Distributor Name</th>
										<th style="text-align:left;">State Name</th>
										<th style="text-align:left;">Basic Rate</th>
										<th style="text-align:left;">Sale Rate</th>
										<!--<th style="text-align:left;">Effective Date</th>-->
										<th style="text-align:left;">Created By</th>
										<th style="text-align:left;">Created Date</th>
									</tr>
								</thead>
								<tbody id="tableBody">
								</tbody>
							</table>   
						</div>
						<span id="searchh2" style="display:none;">Loading.....</span>
						
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
    .table-itemwise_rate_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table-itemwise_rate_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-itemwise_rate_report tbody th { position: sticky; left: 0; }
	
	/* Just common table stuff. Really. */
	.table-itemwise_rate_report table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	.table-itemwise_rate_report th     { background: #50607b;color: #fff !important; }
	table.table {
        margin-top: 0px !important;
    }
</style>


<?php init_tail(); ?>
<!--new update -->
<script>
    function myFunction2() 
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput1");
        filter = input.value.toUpperCase();
        table = document.getElementById("table-itemwise_rate_report");
        tr = table.getElementsByTagName("tr");
        for (i = 2; i < tr.length; i++) 
        {
            tr[i].style.display = "none"; 
            td = tr[i].getElementsByTagName("td"); 
            for (j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;                
                    if (txtValue.toUpperCase().indexOf(filter.toUpperCase()) > -1) {
                        tr[i].style.display = "";  
                        break; 
					}
				}
			}
		}
	}
</script>
<script type="text/javascript" language="javascript" >
	$(document).ready(function(){
		function load_data(ItemID)
		{
			$.ajax({
				url:"<?php echo admin_url(); ?>Rate_master/GetItemWiseRateList",
				//dataType:"JSON",
				method:"POST",
				data:{ItemID:ItemID},
				beforeSend: function () {
					$('#searchh2').css('display','block');
					$('.table-itemwise_rate_report tbody').css('display','none');
				},
				complete: function () {
					$('.table-itemwise_rate_report tbody').css('display','');
					$('#searchh2').css('display','none');
				},
				success:function(data){
					$('#tableBody').html(data);
				}
			});
		}
		$('#search_data').on('click',function(){
			var ItemID = $("#ItemID").val();
			if(ItemID == "" || ItemID == null){
			    alert("Please Select Item");
			}else{
			    load_data(ItemID);   
			}
		});
	});
	
	$("#caexcel").click(function(){
		var ItemID = $("#ItemID").val();
		var ItemName = $("#ItemID option:selected").text();
		if(ItemID == "" || ItemID == null){
		    alert("Please Select Item");
		}else{
		    $.ajax({
                url:"<?php echo admin_url(); ?>Rate_master/ExportItemWiseRateList",
                method:"POST",
                data:{ItemID:ItemID, ItemName:ItemName},
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
		}
	});
	
	
</script>
<script type="text/javascript">
	function printPage(){
		var ItemName = $("#ItemID option:selected").text();
	    var stylesheet = '<style type = "text/css"> th, td { padding: 5px 5px;} </style>';
		var tableData = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var heading_data = '<table  border="1" cellpadding="0" cellspacing="0" width="100%" class="tree table table-striped table-bordered" style="font-size:12px;"><tbody><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->FIRMNAME; ?></td></tr><tr><td style="text-align:center;" colspan="9"><?php echo $PlantDetail->ADDRESS1.' '.$PlantDetail->ADDRESS2; ?></td></tr>';
		heading_data += '<tr>';
		heading_data += '<td style="text-align:center;"colspan="9">ItemName : '+ItemName+'</td>';
		heading_data += '</tr>';
		heading_data += '</tbody></table>';
        var print_data = stylesheet+heading_data+tableData
		newWin= window.open("");
		newWin.document.write(print_data);
		newWin.print();
		newWin.close();
	};
</script>
