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
											<li class="breadcrumb-item active" aria-current="page"><b>Monthly Inspection Checklist</b></li>
										</ol>
									</nav>
									<hr class="hr_style">							
								</div>
							</div>
							<form action="" method="post" id="MonthlyInspectionChecklistForm">
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
									
									<div class="col-md-6">
										<label for="Type" class="control-label" ><small class="req text-danger">* </small>Check Points</label><br>
										<?php
										$check_points = [
											1 => 'No water leakage / dampness',
											2 => 'No mold / odor',
											3 => 'Walls and floors intact',
											4 => 'No pest entry points',
											5 => 'Door seals intact',
											6 => 'No Chemicals stored nearby',
											7 => 'No non-foods items present',
											8 => 'Temperature control equipment working'
										];
										foreach ($check_points as $key => $value) {
											echo '<label for="type'.$key.'"><input type="checkbox" name="type[]" id="type'.$key.'" value="'.$key.'">&nbsp;'.$value.'&emsp;</label><br>';
										}
										?>
									</div>
									<div class="col-md-6">
										<label for="status" class="control-label" ><small class="req text-danger">* </small>Overall Status</label><br>
										<?php
										$overall_status = [
											1 => 'Acceptable',
											2 => 'Needs improvement'
										];
										foreach ($overall_status as $key => $value) {
											echo '<label for="status'.$key.'"><input type="radio" name="status" id="status'.$key.'" value="'.$key.'" required>&nbsp;'.$value.'&emsp;</label><br>';
										}
										?>
									</div>
									
									<div class="clearfix"></div>
									
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
        <h4 class="modal-title">Monthly Inspection Checklist List</h4>
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
                <th class="sortable">Check Points</th>
                <th class="sortable">Overall Status</th>
              </tr>
            </thead>
            <tbody>
							<?php
							// print_r($log_list);
							if(isset($log_list) && !empty($log_list)){
								foreach($log_list as $key => $value) {
									$show_check_points = array_intersect_key($check_points, array_flip(json_decode($value['CheckPointIDs'])));
									echo '<tr class="get_Details" data-id="'.$value['InspectionID'].'" onclick="getDetails(\''.$value['InspectionID'].'\', this)">
										<td>'.explode(' ', _d($value['TransDate']))[0].'</td>
										<td>'.$value['WarehouseName'].'</td>
										<td>'.$value['LocationID'].'</td>
										<td>'.implode('<br>', $show_check_points).'</td>
										<td>'.$overall_status[$value['OverallStatus']].'</td>
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
			let url = '<?= admin_url('StorageAuditLog/PrintPDFgetMonthlyInspectionChecklist'); ?>?PestLogID=' + PestLogID;
			window.open(url, '_blank');
		}


	function ResetForm() {
		$('#update_id').val('');
		$('#MonthlyInspectionChecklistForm')[0].reset();
		$('.selectpicker').selectpicker('refresh');
		$('#btn-save').show();
		$('#btn-update').hide();
	      $('#btn-print-pdf').hide();

	}

	$('#MonthlyInspectionChecklistForm').on('submit', function(e) {
		e.preventDefault();
		var formData = new FormData(this);
		let urlLink = '<?= admin_url('StorageAuditLog/saveMonthlyInspectionChecklist'); ?>';
		if($('#update_id').val() != ''){
			urlLink = '<?= admin_url('StorageAuditLog/updateMonthlyInspectionChecklist'); ?>';
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
				url: "<?= admin_url('StorageAuditLog/getMonthlyInspectionChecklist'); ?>",
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
			$('#btn-save').hide();
			$('#btn-update').show();
		       $('#btn-print-pdf').show();


			let ids = [];

			try {
				ids = JSON.parse(d.CheckPointIDs || '[]');
			} catch (e) {
				ids = (d.CheckPointIDs || '').split(',');
			}

			ids.forEach(item => {
				if (item) {
					$(`#type${item}`).prop('checked', true);
				}
			});

			if (d.OverallStatus) {
				$(`#status${d.OverallStatus}`).prop('checked', true);
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
        url: "<?= admin_url('StorageAuditLog/getMonthlyInspectionChecklistList') ?>",
        type: "POST",
        data: form_data,
        processData: false,
        contentType: false,
        success: function(res) {
          let json = JSON.parse(res);
          if (!json.success) {
            $('#searchBtn').prop('disabled', false);
            if (offset === 0) {
              $('#table_ListModal tbody').html('<tr><td colspan="5" class="text-center">No Data Found</td></tr>');
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
		let check_points = <?php echo json_encode($check_points); ?>;
		let overall_status = <?php echo json_encode($overall_status); ?>;
    rows.forEach(function(row) {
			let ids = [];
			if (row.CheckPointIDs) {
				if (Array.isArray(row.CheckPointIDs)) {
					ids = row.CheckPointIDs;
				} else {
					try {
						ids = JSON.parse(row.CheckPointIDs);
					} catch (e) {
						ids = row.CheckPointIDs.split(',');
					}
				}
			}
			let selected = ids.map(id => check_points[id]).filter(Boolean);
			let checkPointHtml = selected.length ? selected.join('<br>') : '-';

      html += `<tr class="get_Details" data-id="${row.InspectionID}" onclick="getDetails('${row.InspectionID}', this)">
        <td>${moment(row.TransDate).format('DD/MM/YYYY')}</td>
				<td>${row.WarehouseName || ''}</td>
				<td>${row.LocationID || ''}</td>
				<td>${checkPointHtml}</td>
				<td>${overall_status[row.OverallStatus] || ''}</td>
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