<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report          { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
	.table-daily_report thead th { position: sticky; top: 0; z-index: 1; }
	.table-daily_report tbody th { position: sticky; left: 0; }
	
	
	table  { border-collapse: collapse; width: 100%; }
	th, td { padding: 1px 5px !important; white-space: nowrap; border:1px solid !important;font-size:11px; line-height:1.42857143!important;vertical-align: middle !important;}
	th     { background: #50607b;
    color: #fff !important; }
    input:focus{
	outline: none;
	box-shadow: none;
	border: none;
	}
	.for-item-id{
    position: sticky !important;
    left: 0;
    width: 43px;
    background-color:#fff;
    }
	.for-item-name{
    position: sticky;
    width: 81px;
    left: 43px;
    background-color:#fff;
    }
    
	.for-item-idth{
    position: sticky !important;
    left: 0;
    width: 43px;
    }
    
    .for-item-nameth{
	position: sticky;
    width: 81px;
    left: 43px;
    }
	
	.table-daily_report {
    max-height: 60vh;
    overflow: auto;
    position: relative;
	}
	
	.table-daily_report {
    border-collapse: separate;
    border-spacing: 0;
	}
	/* Second header row */
	.table-daily_report thead tr:nth-child(2) th,
	.table-daily_report thead tr:nth-child(2) td {
    position: sticky;
    top: 18px; /* height of first header row */
    z-index: 4;
	background: #50607b;
    color: #fff !important;
	box-shadow: inset 0 -1px 0 #dee2e6;
	}
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="panel_s">
					<div class="panel-body">
						<div class="row">
							<div class="col-md-12 text-centerr"  >
								<nav aria-label="breadcrumb" >
									<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
										<li class="breadcrumb-item" ><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
										<li class="breadcrumb-item active text-capitalize"><b>HR</b></li>
										<li class="breadcrumb-item active" aria-current="page"><b>Staff Payout</b></li>
										
									</ol>
								</nav>
								<hr style="margin-Bottom:12px !important;">
							</div>
						</div>
						<div class="row">
							
							<div class="col-sm-6 col-md-2">
								<div class="form-group">					
									<label>Month</label>
									<input type="month" class="form-control select2" name="month" id="month" value="<?php echo date('Y-m');?>">
								</div>
							</div>
							<div class="col-md-12">
								<span id="searchh" style="display:none">Please wait while fetching data..</span>
								
								<div class="table-daily_report tableFixHead2">
									
									
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	
	
	<div class="modal fade" id="accountModal" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				
				<div class="modal-header">
					<h5 class="modal-title">Salary Details</h5>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				
				<div class="modal-body SalaryModalBody" >
				</div>
				
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
				
			</div>
		</div>
	</div>
	<?php init_tail(); ?>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
	
	<script>
		$(document).on('click', '.openPopup', function () {
			
			var AccountID = $(this).data('id');
			var Month = $("#month").val();
			
			$.ajax({
				url:"<?php echo base_url(); ?>admin/payroll/GetStaffPayoutMonthlyData",
				//dataType:"JSON",
				method:"POST",
				cache: false,
				data: { 
					AccountID: AccountID,					
					Month: Month,					
				},
				success:function(data){
					$('.SalaryModalBody').html(data);
					
				}
			});
			
			// Open Bootstrap Modal
			$('#accountModal').modal('show');
		});
	</script>
	<script type="text/javascript">
		$('.AmtEnter').on('keypress',function (event) {
			if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
				event.preventDefault();
			}
			var input = $(this).val();
			if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
				event.preventDefault();
			}
		});
		$('.cancelBtn').on('click',function () {
			window.location.reload(true);
		})
	</script>
	
	<script>
		
		$(document).ready(function () {
			load_data();
			$("#month").on("change", function () {
				load_data();
			});
		});	
		function load_data()
		{
			let Month = $("#month").val();
			$.ajax({
				url:"<?php echo base_url(); ?>admin/payroll/StaffPayoutData",
				//dataType:"JSON",
				method:"POST",
				cache: false,
				data: { 
					Month: Month					
				},
				beforeSend: function () {
					$('#searchh').css('display','block');
					$('.table-daily_report').css('display','none');
				},
				complete: function () {
					$('.table-daily_report').css('display','');
					$('#searchh').css('display','none');
				},
				success:function(data){
					$('.table-daily_report').html(data);
					
				}
			});
		}
		
		
		$(document).on("click", ".sortablePop", function () {
			var table = $("#table-daily_report tbody");
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