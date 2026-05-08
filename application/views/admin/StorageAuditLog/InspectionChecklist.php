<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    // .table-daily_report { 
        // overflow: auto;
        // max-height: 150vh;
        // width:100%;
        // position:relative;
        // top: 0px; 
    // }
    .table-daily_report thead th { 
        position: sticky; 
        top: 0; 
        z-index: 1; 
    }
    .table-daily_report tbody th { 
        position: sticky; 
        left: 0; 
    }
    
    table { 
        border-collapse: collapse; 
        width: 100%; 
    }
    th, td { 
        padding: 1px 5px !important; 
        white-space: nowrap; 
        border:1px solid !important;
        font-size:11px; 
        line-height:1.42857143!important;
        vertical-align: middle !important;
    }
    th { 
        background: #50607b;
        color: #fff !important; 
    }
</style>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-8">
				<div class="row">
					<div class="panel_s invoice accounting-template">				
						<div class="panel-body" style="padding-top:5px;">
							<div class="row">
								<div class="col-md-12">
									<nav aria-label="breadcrumb">
										<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
											<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
											<li class="breadcrumb-item active text-capitalize"><b>Transaction</b></li>
											<li class="breadcrumb-item active" aria-current="page"><b>Receiving Inspection Checklist</b></li>
										</ol>
									</nav>
									<hr class="hr_style">							
								</div>
							</div>
							<form action="" method="post" id="ReceivingInspectionChecklistForm">
								<input type="hidden" name="update_id" id="update_id" value="">
								<div class="row">
									<div class="col-md-3">								 
										<?php                               
											$fy = $this->session->userdata('finacial_year');
											$fy_new  = $fy + 1;
											$lastdate_date = '20'.$fy_new.'-03-31';
											$curr_date = date('Y-m-d');
											$curr_date_new    = new DateTime($curr_date);
											$last_date_yr = new DateTime($lastdate_date);
											if($last_date_yr < $curr_date_new){
												$date1 = _d($lastdate_date);
												} else {
												$date1 = _d(date('Y-m-d'));
											}								
										echo render_date_input('date','Date',$date1); ?>							
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="WarehouseID" class="control-label" ><small class="req text-danger">* </small> Warehouse Name</label>
											<select class="selectpicker" name="WarehouseID" id="WarehouseID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required>
												<option value="0" selected>None Selected</option>
												<?php
													if(isset($WarehouseList) && !empty($WarehouseList)){
														foreach($WarehouseList as $key => $value) {
															echo '<option value="'.$value->id.'" >'.$value->AccountName.'</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="LocationID" class="control-label" ><small class="req text-danger">* </small> Location</label>
											<select class="selectpicker" name="LocationID" id="LocationID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value="0" selected>None Selected</option>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="SupplierID" class="control-label" ><small class="req text-danger">* </small>Supplier Name</label>
											<select class="selectpicker" name="SupplierID" id="SupplierID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required onchange="getProductsBySupplier(this.value)">
												<option value="0" selected>None Selected</option>
												<?php
													if(isset($SupplierList) && !empty($SupplierList)){
														foreach($SupplierList as $key => $value) {
															echo '<option value="'.$value->userid.'" >'.$value->company.' ('.$value->AccountID.')</option>';
														}
													}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="ProductID" class="control-label" ><small class="req text-danger">* </small>Product Name</label>
											<select class="selectpicker" name="ProductID" id="ProductID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>" required onchange="getBatchByProduct(this.value)">
												<option value="0" selected>None Selected</option>
												<?php
													// if(isset($ProductList) && !empty($ProductList)){
													// 	foreach($ProductList as $key => $value) {
													// 		echo '<option value="'.$value->id.'" >'.$value->description.' ('.$value->item_code.')</option>';
													// 	}
													// }
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label for="BatchNo" class="control-label" >Batch / Lot No</label>
											<select class="selectpicker" name="BatchNo" id="BatchNo" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value="0" selected>None Selected</option>
												<?php
													// if(isset($BatchList) && !empty($BatchList)){
													// 	foreach($BatchList as $key => $value) {
													// 		echo '<option value="'.$value->batch_no.'" >'.$value->batch_no.'</option>';
													// 	}
													// }
												?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<label for="remark" class="control-label" >Reason(if rejected)</label>
										<div class="form-group">
											<textarea id="remark" name="remark" class="form-control"></textarea>
										</div>
									</div>
									
									<div class="clearfix"></div>
									<div class="col-md-12">
										<div class="table-daily_report tableFixHead2">
											<table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
												<thead>
													<tr>
														<th class="sortable">Parameter</th>
														<th class="sortable">OK ✔/ X</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td style="font-weight:700;text-align:left;">Packaging Intact (no tears/leaks)</td>
														
														<td>
															<select class="selectpicker" name="PackagingStatus" id="PackagingStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
																<option value="0" selected>None Selected</option>
																<option value="Y">Ok</option>
																<option value = "N">Not Ok</option>
															</select>
														</td>
														
													</tr>
													<tr>
														<td style="font-weight:700;text-align:left;">No Moisture Damage</td>
														
														<td>
															<select class="selectpicker" name="MoistureStatus" id="MoistureStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
																<option value="0" selected>None Selected</option>
																<option value="Y">Ok</option>
																<option value = "N">Not Ok</option>
															</select>
														</td>
													</tr>
													<tr>
														<td style="font-weight:700;text-align:left;">No pest Contamination</td>
														<td>
															<select class="selectpicker" name="PestStatus" id="PestStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
																<option value="0" selected>None Selected</option>
																<option value="Y">Ok</option>
																<option value = "N">Not Ok</option>
															</select>
														</td>
														
													</tr>
													<tr>
														<td style="font-weight:700;text-align:left;">Labels Present (ingredient,origin,expiry)</td>
														
														<td>
															<select class="selectpicker" name="LabelsStatus" id="LabelsStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
																<option value="0" selected>None Selected</option>
																<option value="Y">Ok</option>
																<option value = "N">Not Ok</option>
															</select>
														</td>
														
													</tr>
													<tr>
														<td style="font-weight:700;text-align:left;">COA / Document Received</td>
														
														<td>
															<select class="selectpicker" name="COAStatus" id="COAStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
																<option value="0" selected>None Selected</option>
																<option value="Y">Ok</option>
																<option value = "N">Not Ok</option>
															</select>
														</td>
														
													</tr>
												</tbody>
											</table>   
										</div>
									</div>
									
									<div class="col-md-6"style = "Margin-top:10px;">
										<button type="submit" class="btn-tr btn btn-info btn-submit" id="btn-save">Save</button>
										<button type="submit" class="btn-tr btn btn-info btn-submit" id="btn-update" style="display:none;">Update</button>
										<button type="button" class="btn-tr btn btn-danger" onclick="ResetForm()">Reset</button>
										<button type="button" class="btn-tr btn btn-primary" data-toggle="modal" data-target="#ListModal">List</button>
										<button type="button" class="btn-tr btn btn-warning" id="btn-print-pdf" style="display:none;" onclick="printPDF()">
											<i class="fa fa-print"></i> Print PDF
										</button>
									</div>
									
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- ===== LIST MODAL ===== -->
<div class="modal fade" id="ListModal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog" role="document" style="width: 80vw;">
    <div class="modal-content">
      <div class="modal-header" style="padding:5px 10px;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Receiving Inspection Checklist List</h4>
      </div>
      <div class="modal-body" style="padding: 5px 5px !important">

        <!-- List filter form -->
        <form action="" method="post" id="filter_list_form">
          <div class="row">
            <!-- Date range filters -->
            <div class="col-md-2 mbot5">
              <div class="form-group" app-field-wrapper="fromDate">
                <?= render_date_input('fromDate', 'From Date', date('01/m/Y'), []); ?>
              </div>
            </div>
            <div class="col-md-2 mbot5">
              <div class="form-group" app-field-wrapper="toDate">
                <?= render_date_input('toDate', 'To Date', date('d/m/Y'), []); ?>
              </div>
            </div>
            <div class="col-md-2 mbot5">
							<div class="form-group">
								<label for="WarehouseID" class="control-label">Warehouse</label>
								<select class="selectpicker" name="WarehouseID" id="WarehouseID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true">
									<option value="" selected>None Selected</option>
									<?php
										if(isset($WarehouseList) && !empty($WarehouseList)){
											foreach($WarehouseList as $key => $value) {
												echo '<option value="'.$value->id.'" >'.$value->AccountName.'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>

            <div class="col-md-3 mbot5" style="padding-top: 20px;">
              <button type="submit" class="btn btn-success" id="searchBtn">Show</button>
            </div>
            <div class="col-md-3 mbot5" style="padding-top: 20px;">
              <input type="search" class="form-control" id="myInput1" onkeyup="myFunction2()" placeholder="Search..." title="Type in a table">
            </div>
          </div>
        </form>

        <!-- Progress bar for chunked data loading -->
        <div class="progress" style="margin-bottom: 5px; height: 3px;">
          <div id="fetchProgress" class="progress-bar" style="width:0%"></div>
        </div>

        <!-- List results table -->
				<style>#table_ListModal tr {cursor: pointer;}</style>
        <div class="table-ListModal tableFixHead2">
          <table class="tree table table-striped table-bordered table-ListModal tableFixHead2" id="table_ListModal" width="100%">
            <thead>
              <tr>
                <th class="sortable">Log Date</th>
                <th class="sortable">Warehouse</th>
                <th class="sortable">Location</th>
                <th class="sortable">Supplier</th>
                <th class="sortable">Product</th>
                <th class="sortable">Batch No</th>
                <th class="sortable">Packaging</th>
                <th class="sortable">Moisture</th>
                <th class="sortable">Pest</th>
								<th class="sortable">Labels</th>
								<th class="sortable">COA</th>
              </tr>
            </thead>
            <tbody>
							<?php
							// print_r($log_list);
							if(isset($log_list) && !empty($log_list)){
								foreach($log_list as $key => $value) {
									echo '<tr class="get_Details" data-id="'.$value['InspectionID'].'" onclick="getDetails(\''.$value['InspectionID'].'\', this)">
										<td>'.explode(' ', _d($value['TransDate']))[0].'</td>
										<td>'.$value['WarehouseName'].'</td>
										<td>'.$value['LocationID'].'</td>
										<td>'.$value['SupplierName'].'</td>
										<td>'.$value['ProductName'].'</td>
										<td>'.$value['BatchNo'].'</td>
										<td>'.($value['PackagingStatus'] == 'Y' ? 'Yes' : 'No').'</td>
										<td>'.($value['MoistureStatus'] == 'Y' ? 'Yes' : 'No').'</td>
										<td>'.($value['PestStatus'] == 'Y' ? 'Yes' : 'No').'</td>
										<td>'.($value['LabelsStatus'] == 'Y' ? 'Yes' : 'No').'</td>
										<td>'.($value['COAStatus'] == 'Y' ? 'Yes' : 'No').'</td>
									</tr>';
								}
							}
							?>
						</tbody>
          </table>
        </div>
        <br>
      </div>
    </div>
  </div>
</div>

<?php init_tail(); ?>

<script type="text/javascript">

		function printPDF() {
			let InspectionID = $('#update_id').val();
			if (!InspectionID) {
				alert_float('warning', 'No record selected for print.');
				return;
			}
			let url = '<?= admin_url('StorageAuditLog/PrintPDFInspectionChecklist'); ?>?InspectionID=' + InspectionID;
			window.open(url, '_blank');
		}

	$('#Temp,#Humidity').on('keypress',function (event) {
		if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
			event.preventDefault();
		}
		var input = $(this).val();
		if ((input.indexOf('.') != -1) && (input.substring(input.indexOf('.')).length > 2)) {
			event.preventDefault();
		}
	});

	function ResetForm() {
		$('#update_id').val('');
		$('#ReceivingInspectionChecklistForm')[0].reset();
		$('.selectpicker').selectpicker('refresh');
		$('#btn-save').show();
		$('#btn-update').hide();
		$('#btn-print-pdf').hide();

	}

	function getProductsBySupplier(SupplierID) {
		return $.ajax({
			url: '<?= admin_url('StorageAuditLog/getProductsBySupplier'); ?>',
			method: 'POST',
			dataType: 'json',
			data: { SupplierID }
		}).then(response => {
			let options = '<option value="0">None Selected</option>';
			if (response.status) {
				$.each(response.data, function(i, item) {
					options += `<option value="${item.id}">${item.description} (${item.item_code})</option>`;
				});
			}
			$('#ProductID').html(options).selectpicker('refresh');
		});
	}

	function getBatchByProduct(ProductID) {
		let SupplierID = $('#SupplierID').val();

		return $.ajax({
			url: '<?= admin_url('StorageAuditLog/getBatchByProduct'); ?>',
			method: 'POST',
			dataType: 'json',
			data: { ProductID, SupplierID }
		}).then(response => {
			let options = '<option value="0">None Selected</option>';
			if (response.status) {
				$.each(response.data, function(i, item) {
					options += `<option value="${item.batch_no}">${item.batch_no}</option>`;
				});
			}
			$('#BatchNo').html(options).selectpicker('refresh');
		});
	}

	$('#ReceivingInspectionChecklistForm').on('submit', function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		let urlLink = '<?= admin_url('StorageAuditLog/saveReceivingInspectionChecklist'); ?>';
		if($('#update_id').val() != ''){
			urlLink = '<?= admin_url('StorageAuditLog/updateReceivingInspectionChecklist'); ?>';
		}
		$.ajax({
			url: urlLink,
			method: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			beforeSend: function() {
				$('.btn-submit').attr('disabled', true).text('Saving...');
			},
			complete: function() {
				$('.btn-submit').attr('disabled', false);
				$('#btn-save').text('Save');
				$('#btn-update').text('Update');
			},
			success: function(response) {
				response = JSON.parse(response);
				if(response.status) {
					alert_float('success', response.message);
					ResetForm();
					$('.selectpicker').selectpicker('refresh');
					$('#filter_list_form').submit();
				} else {
					alert_float('danger', response.message);
				}
			},
			error: function() {
				alert_float('danger', 'An error occurred while saving the log.');
			}
		});
	});

	async function getDetails(InspectionID, row) {
		let $row = $(row);
		if ($row.hasClass('processing')) return;

		$row.addClass('processing');
		ResetForm();

		try {
			const response = await $.ajax({
				url: "<?= admin_url('StorageAuditLog/getReceivingInspectionChecklist'); ?>",
				method: "POST",
				dataType: "json",
				data: { InspectionID }
			});

			console.log('Main response:', response);

			if (response.success !== true) {
				alert_float('warning', response.message);
				return;
			}

			let d = response.data;

			$('#update_id').val(d.InspectionID);
			$('#date').val(moment(d.TransDate).format('DD/MM/YYYY'));
			$('#WarehouseID').val(d.WarehouseID || '0');
			$('#LocationID').val(d.LocationID || '0');
			$('#SupplierID').val(d.SupplierID || '0');

			console.log('Fetching products...');
			await getProductsBySupplier(d.SupplierID);
			$('#ProductID').val(d.ProductID || '0').selectpicker('refresh');

			console.log('Fetching batch...');
			await getBatchByProduct(d.ProductID);
			$('#BatchNo').val(d.BatchNo || '0').selectpicker('refresh');

			$('#remark').val(d.Remark);
			$('#btn-save').hide();
			$('#btn-update').show();
			$('#btn-print-pdf').show();


			if (d.details && d.details.length > 0) {
				d.details.forEach(item => {
					switch (item.ParameterName) {
						case 'Packaging':
							$('#PackagingStatus').val(item.Status);
							break;
						case 'Moisture':
							$('#MoistureStatus').val(item.Status);
							break;
						case 'Pest':
							$('#PestStatus').val(item.Status);
							break;
						case 'Labels':
							$('#LabelsStatus').val(item.Status);
							break;
						case 'COA':
							$('#COAStatus').val(item.Status);
							break;
						default:
							console.warn('Unknown parameter:', item.ParameterName);
					}
				});
			}

			$('.selectpicker').selectpicker('refresh');
			$('#ListModal').modal('hide');

		} catch (err) {
			console.error('getDetails error:', err);
			alert_float('danger', 'Something went wrong.');
		} finally {
			$row.removeClass('processing');
		}
	}
</script>

<!-- Chunked data loading -->
<script>
	// Submit filter form and load list records in chunks for performance
  $('#filter_list_form').submit(function(e) {
    e.preventDefault();
    let form = this;
    let limit = 1;
    let offset = 0;
    let totalRecords = 0;
    let loadedRecords = 0;
    $('#searchBtn').prop('disabled', true);
    $('#table_ListModal tbody').html('');

    function fetchChunk() {
      var form_data = new FormData(form);
      $.ajax({
        url: "<?= admin_url('StorageAuditLog/getReceivingInspectionChecklistList') ?>",
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        success: function(res) {
          let json = JSON.parse(res);
          if (!json.success) {
            $('#searchBtn').prop('disabled', false);
            if (offset === 0) {
              $('#table_ListModal tbody').html('<tr><td colspan="11" class="text-center">No Data Found</td></tr>');
            }
            return;
          }
          // Store total count on first chunk
          if (offset === 0) { totalRecords = parseInt(json.total) || 0; }
          if (json.rows && json.rows.length > 0) {
            appendRows(json.rows);
            loadedRecords += json.rows.length;
            offset += limit;
          }
          updateProgress(loadedRecords, totalRecords);
          // Stop fetching when all records are loaded
          if (loadedRecords >= totalRecords) {
            $('#searchBtn').prop('disabled', false);
            $('#fetchProgress').css('width', '0%');
            return;
          }
          fetchChunk(); // Recursively fetch next chunk
        }
      });
    }
    fetchChunk();
  });

  // Build and append HTML rows to the list table from fetched data
  function appendRows(rows) {
    let html = '';
    rows.forEach(function(row) {
      html += `<tr class="get_Details" data-id="${row.InspectionID}" onclick="getDetails('${row.InspectionID}', this)">
        <td>${moment(row.TransDate).format('DD/MM/YYYY')}</td>
				<td>${row.WarehouseName || ''}</td>
				<td>${row.LocationID || ''}</td>
				<td>${row.SupplierName || ''}</td>
				<td>${row.ProductName || ''}</td>
				<td>${row.BatchNo || ''}</td>
				<td>${row.PackagingStatus == 'Y' ? 'Yes' : 'No'}</td>
				<td>${row.MoistureStatus == 'Y' ? 'Yes' : 'No'}</td>
				<td>${row.PestStatus == 'Y' ? 'Yes' : 'No'}</td>
				<td>${row.LabelsStatus == 'Y' ? 'Yes' : 'No'}</td>
				<td>${row.COAStatus == 'Y' ? 'Yes' : 'No'}</td>
      </tr>`;
    });
    $('#table_ListModal tbody').append(html);
  }

  // Update progress bar width based on loaded vs total records
  function updateProgress(loaded, total) {
    let percent = Math.floor((loaded / total) * 100);
    $('#fetchProgress').css('width', percent + '%');
  }
</script>

<!-- table sorting and search -->
<script>
  $(document).on("click", ".sortable", function() {
    var table = $("#table_ListModal tbody");
    var rows = table.find("tr").toArray();
    var index = $(this).index();
    var ascending = !$(this).hasClass("asc");
    $(".sortable").removeClass("asc desc");
    $(".sortable span").remove();
    $(this).addClass(ascending ? "asc" : "desc");
    $(this).append(ascending ? '<span> &#8593;</span>' : '<span> &#8595;</span>');
    // Sort numerically if both values are numbers, otherwise sort alphabetically
    rows.sort(function(a, b) {
      var valA = $(a).find("td").eq(index).text().trim();
      var valB = $(b).find("td").eq(index).text().trim();
      if ($.isNumeric(valA) && $.isNumeric(valB)) { return ascending ? valA - valB : valB - valA; }
      else { return ascending ? valA.localeCompare(valB) : valB.localeCompare(valA); }
    });
    table.append(rows);
  });

  function myFunction2() {
    var input = document.getElementById("myInput1");
    var filter = input.value.toUpperCase();
    var table = document.getElementById("table_ListModal");
    var tbody = table.getElementsByTagName("tbody")[0];
    var tr = tbody.getElementsByTagName("tr");
    // Show only rows that contain the search text in any column
    for (var i = 0; i < tr.length; i++) {
      var tds = tr[i].getElementsByTagName("td");
      var rowMatch = false;
      for (var j = 0; j < tds.length; j++) {
        var txtValue = tds[j].textContent || tds[j].innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) { rowMatch = true; break; }
      }
      tr[i].style.display = rowMatch ? "" : "none";
    }
  }
</script>