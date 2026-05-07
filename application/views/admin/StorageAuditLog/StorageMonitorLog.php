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
											<li class="breadcrumb-item active" aria-current="page"><b>Daily Storage Monitoring Log</b></li>
										</ol>
									</nav>
									<hr class="hr_style">							
								</div>
							</div>
							<form action="" method="post" id="StorageMonitorLogForm">
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
									
									<div class="col-md-4">
										<div class="form-group">
											<label for="LocationID" class="control-label" ><small class="req text-danger">* </small> Location</label>
											<select class="selectpicker" name="LocationID" id="LocationID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
												<option value="" selected>None Selected</option>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<label for="remark" class="control-label" >Remark</label>
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
															<th class="sortable">Reading</th>
															
															<th class="sortable">Acceptable Range</th>
															<th class="sortable">OK ✔/ X</th>
															<th class="sortable">Action Taken(if any)</th>
															<th class="sortable">Initials</th>
													</tr>
												</thead>
												<tbody>
												<tr>
													<td style="font-weight:700;text-align:left;">Tramperature (°C)</td>
													<td>
														<div class="form-group">
															<input type="text" id="Temp" name="Temp" class="form-control">
														</div>
													</td>
													<td style="font-weight:700;text-align:center;">10-25 °C</td>
													<td>
														<select class="selectpicker" name="TempStatus" id="TempStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Ok</option>
															<option value="N">Not Ok</option>
														</select>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="TempAction" id="TempAction"></textarea>
														</div>
													</td>
													<td>
														<div class="form-group">
															<textarea id="TempInitial" name="TempInitial" class="form-control"></textarea>
														</div>
													</td>
												</tr>
												<tr>
													<td style="font-weight:700;text-align:left;">Humidity (%)</td>
													<td>
														<div class="form-group">
															<input type="text" id="Humidity" name="Humidity" class="form-control">
														</div>
													</td>
													<td style="font-weight:700;text-align:center;"> &lt; 60%</td>
													<td>
														<select class="selectpicker" name="HumidityStatus" id="HumidityStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Ok</option>
															<option value="N">Not Ok</option>
														</select>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="HumidityAction" id="HumidityAction"></textarea>
														</div>
													</td>
													<td>
														<div class="form-group">
															<textarea id="HumidityInitial" name="HumidityInitial" class="form-control"></textarea>
														</div>
													</td>
												</tr>
												<tr>
													<td style="font-weight:700;text-align:left;">Pest Activity Observed</td>
													<td>
														<select class="selectpicker" name="Pest" id="Pest" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Yes</option>
															<option value="N">No</option>
														</select>
													</td>
													<td style="font-weight:700;text-align:center;">None</td>
													<td>
														<select class="selectpicker" name="PestStatus" id="PestStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Ok</option>
															<option value="N">Not Ok</option>
														</select>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="PestStatusAction" id="PestStatusAction"></textarea>
														</div>
													</td>
													<td>
														<div class="form-group">
															<textarea id="PestInitial" name="PestInitial" class="form-control"></textarea>
														</div>
													</td>
												</tr>
												<tr>
													<td style="font-weight:700;text-align:left;">Cleanlines Status</td>
													<td>
														<select class="selectpicker" name="Clean" id="Clean" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Good</option>
															<option value="N">Poor</option>
														</select>
													</td>
													<td style="font-weight:700;text-align:center;">Clean</td>
													<td>
														<select class="selectpicker" name="CleanStatus" id="CleanStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Ok</option>
															<option value="N">Not Ok</option>
														</select>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="CleanAction" id="CleanAction"></textarea>
														</div>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="CleanInitial" id="CleanInitial"></textarea>
														</div>
													</td>
												</tr>
												<tr>
													<td style="font-weight:700;text-align:left;">Packaging Condition</td>
													<td>
														<select class="selectpicker" name="Packaging" id="Packaging" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Good</option>
															<option value="N">Damage</option>
														</select>
													</td>
													<td style="font-weight:700;text-align:center;">Intact</td>
													<td>
														<select class="selectpicker" name="PackagingStatus" id="PackagingStatus" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
															<option value=""></option>
															<option value="Y">Ok</option>
															<option value="N">Not Ok</option>
														</select>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="PackagingAction" id="PackagingAction"></textarea>
														</div>
													</td>
													<td>
														<div class="form-group">
															<textarea class="form-control" name="PackagingInitial" id="PackagingInitial"></textarea>
														</div>
													</td>
												</tr>
												<!-- Data will be loaded via AJAX -->
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
        <h4 class="modal-title">Daily Storage Monitoring Log List</h4>
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
                <th class="sortable">Temperature</th>
                <th class="sortable">Humidity</th>
                <th class="sortable">Pest Control</th>
								<th class="sortable">Cleanliness</th>
								<th class="sortable">Packaging</th>
              </tr>
            </thead>
            <tbody>
							<?php
							// print_r($log_list);
							if(isset($log_list) && !empty($log_list)){
								foreach($log_list as $key => $value) {
									echo '<tr class="get_Details" data-id="'.$value['LogID'].'" onclick="getDetails(\''.$value['LogID'].'\', this)">
										<td>'.explode(' ', _d($value['TransDate']))[0].'</td>
										<td>'.$value['WarehouseName'].'</td>
										<td>'.$value['LocationID'].'</td>
										<td>'.$value['TempReading'].' °C</td>
										<td>'.$value['HumidityReading'].' %</td>
										<td>'.($value['PestReading'] == 'Y' ? 'Yes' : 'No').'</td>
										<td>'.($value['CleanReading'] == 'Y' ? 'Good' : 'Poor').'</td>
										<td>'.($value['PackagingReading'] == 'Y' ? 'Good' : 'Damaged').'</td>
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
        let logID = $('#update_id').val();
        if (!logID) {
            alert_float('warning', 'No record selected for print.');
            return;
        }
        let url = '<?= admin_url('StorageAuditLog/PrintPDF'); ?>?LogID=' + logID;
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
		$('#StorageMonitorLogForm')[0].reset();
		$('.selectpicker').selectpicker('refresh');
		$('#btn-save').show();
		$('#btn-update').hide();
		$('#btn-print-pdf').hide();
	}

	$('#StorageMonitorLogForm').on('submit', function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		let urlLink = '<?= admin_url('StorageAuditLog/saveStorageMonitorLog'); ?>';
		if($('#update_id').val() != ''){
			urlLink = '<?= admin_url('StorageAuditLog/updateStorageMonitorLog'); ?>';
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

	function getDetails(LogID, row) {
    let $row = $(row);
    if ($row.hasClass('processing')) return; // Prevent double-click
    $row.addClass('processing');
    ResetForm();

    $.ajax({
      url: "<?= admin_url('StorageAuditLog/getStorageMonitorLog'); ?>",
      method: "POST",
      dataType: "JSON",
      data: { LogID },
      complete: function() {
        $row.removeClass('processing');
      },
      success: function(response) {
        if (response.success == true) {
          let d = response.data;
          $('#update_id').val(d.LogID);
          $('#date').val(moment(d.TransDate).format('DD/MM/YYYY'));
					$('#WarehouseID').val(d.WarehouseID || '');
					$('#LocationID').val(d.LocationID || '');
					$('#remark').val(d.Remark);
					$('#btn-save').hide();
					$('#btn-update').show();
					$('#btn-print-pdf').show();

					if (d.details && d.details.length > 0) {
						$.each(d.details, function(i, item) {
							switch (item.ParameterName) {
								case 'Temp':
									$('#Temp').val(item.ReadingValue);
									$('#TempStatus').val(item.Status);
									$('#TempAction').val(item.ActionTaken);
									$('#TempInitial').val(item.Initials);
									break;
								case 'Humidity':
									$('#Humidity').val(item.ReadingValue);
									$('#HumidityStatus').val(item.Status);
									$('#HumidityAction').val(item.ActionTaken);
									$('#HumidityInitial').val(item.Initials);
									break;
								case 'Pest':
									$('#Pest').val(item.ReadingValue);
									$('#PestStatus').val(item.Status);
									$('#PestAction').val(item.ActionTaken);
									$('#PestInitial').val(item.Initials);
									break;
								case 'Clean':
									$('#Clean').val(item.ReadingValue);
									$('#CleanStatus').val(item.Status);
									$('#CleanAction').val(item.ActionTaken);
									$('#CleanInitial').val(item.Initials);
									break;
								case 'Packaging':
									 $('#Packaging').val(item.ReadingValue);
									 $('#PackagingStatus').val(item.Status);
									 $('#PackagingAction').val(item.ActionTaken);
									 $('#PackagingInitial').val(item.Initials);
									break;
								default:
									 console.warn('Unknown parameter:', item.ParameterName);
									 break;
							}
						});
					}
          $('.selectpicker').selectpicker('refresh');
					$('#ListModal').modal('hide');
        } else {
          alert_float('warning', response.message);
        }
      },
      error: function() {
        $row.removeClass('processing');
        alert_float('danger', 'Error fetching quotation details.');
      }
    });
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
        url: "<?= admin_url('StorageAuditLog/getStorageMonitorLogList') ?>",
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        success: function(res) {
          let json = JSON.parse(res);
          if (!json.success) {
            $('#searchBtn').prop('disabled', false);
            if (offset === 0) {
              $('#table_ListModal tbody').html('<tr><td colspan="8" class="text-center">No Data Found</td></tr>');
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
      html += `<tr class="get_Details" data-id="${row.LogID}" onclick="getDetails('${row.LogID}', this)">
        <td>${moment(row.TransDate).format('DD/MM/YYYY')}</td>
				<td>${row.WarehouseName || ''}</td>
				<td>${row.LocationID || ''}</td>
				<td>${row.TempReading ? row.TempReading + ' °C' : ''}</td>
				<td>${row.HumidityReading ? row.HumidityReading + ' %' : ''}</td>
				<td>${row.PestReading == 'Y' ? 'Yes' : (row.PestReading == 'N' ? 'No' : '')}</td>
				<td>${row.CleanReading == 'Y' ? 'Good' : (row.CleanReading == 'N' ? 'Poor' : '')}</td>
				<td>${row.PackagingReading == 'Y' ? 'Good' : (row.PackagingReading == 'N' ? 'Damaged' : '')}</td>
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