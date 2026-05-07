 <?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
    .table-daily_report { 
        overflow: auto;
        max-height: 55vh;
        width:100%;
        position:relative;
        top: 0px; 
    }
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
    .hover-row {
        background-color: #f5f5f5 !important;
    }
    .shortage-id-link {
        color: #438eb9;
        font-weight: bold;
        text-decoration: none;
    }
    .shortage-id-link:hover {
        text-decoration: underline;
        color: #2a6496;
    }
    .clickable-row {
        cursor: pointer;
    }
    .report_for {
        font-weight: bold;
        margin-bottom: 10px;
        text-align: center;
    }
</style>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12" id="small-table">
                <div class="panel_s">
                    <div class="panel-body">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
                                <li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
                                <li class="breadcrumb-item active text-capitalize"><b>Transaction</b></li>
                                <li class="breadcrumb-item active" aria-current="page"><b>Shortage List</b></li>
                            </ol>
                        </nav>
                        <hr class="hr_style">
                        
                        <div class="_buttons">
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
                                } else {
                                    $from_date = "01/".date('m')."/".date('Y');
                                    $to_date = date('d/m/Y');
                                }
                            ?> 
                            
                            <div class="col-md-2">
                                <?php
                                    echo render_date_input('from_date','From',$from_date);
                                ?>
                            </div>
                            <div class="col-md-2">
                                <?php
                                    echo render_date_input('to_date','To',$to_date);
                                ?>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="AccountID" class="control-label" ><small class="req text-danger"></small> Party</label>
                                    <select class="selectpicker" name="AccountID" id="AccountID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
										<option value=""></option>
										<?php
											foreach($PartyList as $key => $value) {
											?>
                                            <option value="<?php echo $value["AccountID"]?>" ><?php echo $value["company"]?></option>
											<?php
											}
										?>
										
									</select>
								</div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="VehicleID" class="control-label" ><small class="req text-danger"></small> Vehicle</label>
                                    <select class="selectpicker" name="VehicleID" id="VehicleID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
										<option value=""></option>
										<?php
											foreach($VehicleList as $key => $value) {
											?>
                                            <option value="<?php echo $value["VehicleID"]?>" ><?php echo $value["VehicleID"]?></option>
											<?php
											}
										?>
										
									</select>
								</div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="DriverID" class="control-label" ><small class="req text-danger"></small> Driver</label>
                                    <select class="selectpicker" name="DriverID" id="DriverID" data-width="100%"  data-action-box="true"  data-hide-disabled="true" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
										<option value=""></option>
										<?php
											foreach($DriverList as $key => $value) {
											?>
                                            <option value="<?php echo $value["AccountID"]?>" ><?php echo $value["DriverName"]?></option>
											<?php
											}
										?>
										
									</select>
								</div>
                            </div>
                            
                            <div class="col-md-7">
                                <button class="btn btn-info pull-left mleft5 search_data" style="margin-top: 19px;" id="search_data">Show</button>
                                <div class="custom_button">
                                    &nbsp;<a class="btn btn-default buttons-excel buttons-html5"  style="margin-top: 19px;"  tabindex="0" aria-controls="table-daily_report" href="#" id="caexcel"><span>Export to excel</span></a>
                                    <a class="btn btn-default" href="javascript:void(0);"  style="margin-top: 19px;"  onclick="printPage();">Print</a>
                                </div>
                            </div>
                            <div class="col-md-5" style="margin-top: 20px;">
                                <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                            </div>
                        </div>
                        
                        <div class="clearfix"></div><br>
                        
                        <!-- Report Title -->
                        
                        
                        <div class="table-daily_report tableFixHead2">
                            <table class="tree table table-striped table-bordered table-daily_report tableFixHead2" id="table-daily_report" width="100%">
                                <thead>
                                    <tr>
                                        <th class="sortable">Short ID</th>
                                        <th class="sortable">Short Date</th>
                                        
                                        <th class="sortable">Driver Name</th>
                                        <th class="sortable">Vehicle No</th>
                                        <th class="sortable">Challan ID</th>
                                        <th class="sortable">Party Name</th>
                                        <th class="sortable">Order ID</th>
                                        <th class="sortable">Sale ID</th>
                                        <th class="sortable">Item ID</th>
                                        <th class="sortable">Item Name</th>
                                        <th class="sortable">Bill Qty</th>
                                        <th class="sortable">Shortage Qty</th>
                                        <th class="sortable">Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
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

<?php init_tail(); ?>

<script>
$(document).ready(function() {
    // Initialize datepickers
    init_datepicker();
    
    // Load data automatically when page loads
    load_shortage_data();
    
    // Make rows clickable for edit - opens in new tab
    $(document).on('click', 'tbody tr.clickable-row', function(event) {
        // Don't trigger if clicking on the link itself
        if (!$(event.target).is('a')) {
            var shortageId = $(this).data('shortage-id');
            if (shortageId) {
                window.open('<?= admin_url('ShortQtyMaster/edit_shortage/') ?>' + shortageId, '_blank');
            }
        }
    });
    
    // Style the clickable rows
    $(document).on('mouseenter', 'tbody tr.clickable-row', function() {
        $(this).addClass('hover-row');
    }).on('mouseleave', 'tbody tr.clickable-row', function() {
        $(this).removeClass('hover-row');
    });
});

// Initialize datepicker with financial year constraints
function init_datepicker() {
    var maxEndDate = new Date();
    var fin_y = "<?php echo $this->session->userdata('finacial_year'); ?>";
    var year = "20" + fin_y;
    var cur_y = new Date().getFullYear().toString().substr(-2);
    
    var maxEndDate_new;
    if (cur_y >= fin_y) {
        var year2 = parseInt(fin_y) + parseInt(1);
        var year2_new = "20" + year2;
        var e_dat = new Date(year2_new + '/03/31');
        maxEndDate_new = e_dat;
    } else {
        var e_dat2 = new Date(year + '/03/31');
        maxEndDate_new = e_dat2;
    }
    
    var minStartDate = new Date("20" + fin_y + '/04/01');
    
    $('.datepicker').datetimepicker({
        format: 'd/m/Y',
        minDate: minStartDate,
        maxDate: maxEndDate_new,
        timepicker: false
    });
}

// Load data function
function load_shortage_data() {
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    var AccountID = $("#AccountID").val();
    var VehicleID = $("#VehicleID").val();
    var DriverID = $("#DriverID").val();
    
    // Update report title
    var msg = "Shortage List " + from_date + " To " + to_date;
    $(".report_for").text(msg);
    
    $.ajax({
        url: "<?php echo admin_url('ShortQtyMaster/load_shortage_data'); ?>",
        method: "POST",
        data: {from_date: from_date, to_date: to_date,AccountID:AccountID,VehicleID:VehicleID,DriverID:DriverID},
        beforeSend: function () {
            $('#searchh2').css('display','block');
            $('#table-daily_report tbody').css('display','none');
        },
        complete: function () {
            $('#table-daily_report tbody').css('display','');
            $('#searchh2').css('display','none');
        },
        success: function(data) {
            $('#table-daily_report tbody').html(data);
        },
        error: function(xhr, status, error) {
            $('#table-daily_report tbody').html('<tr><td colspan="13" class="text-center">Error loading data: ' + error + '</td></tr>');
        }
    });
}

// Search button click event
$('#search_data').on('click', function(){
    load_shortage_data();
});

// Search functionality
function myFunction2() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("myInput1");
    filter = input.value.toUpperCase();
    table = document.getElementById("table-daily_report");
    tr = table.getElementsByTagName("tr");
    
    for (i = 1; i < tr.length; i++) {
        var tds = tr[i].getElementsByTagName("td");
        var found = false;
        
        for (var j = 0; j < tds.length; j++) {
            td = tds[j];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        if (found) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// Export to Excel
$("#caexcel").click(function(){
    var from_date = $("#from_date").val();
    var to_date = $("#to_date").val();
    
    // Create a form and submit it to trigger download
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = "<?php echo admin_url('ShortQtyMaster/export_shortage_list'); ?>";
    
    var input1 = document.createElement('input');
    input1.type = 'hidden';
    input1.name = 'from_date';
    input1.value = from_date;
    form.appendChild(input1);
    
    var input2 = document.createElement('input');
    input2.type = 'hidden';
    input2.name = 'to_date';
    input2.value = to_date;
    form.appendChild(input2);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
});

// Print functionality
function printPage(){
    var html_filter_name = $('.report_for').html();
    var stylesheet = '<style type="text/css">' +
        'table { border-collapse: collapse; width: 100%; }' +
        'th, td { padding: 5px; border: 1px solid #000; font-size: 11px; }' +
        'th { background: #50607b; color: #fff; }' +
        '</style>';
    
    var tableData = document.getElementById('table-daily_report').outerHTML;
    
    var heading_data = '<table border="1" cellpadding="0" cellspacing="0" width="100%" style="font-size:12px; margin-bottom: 10px;">' +
        '<tr><td style="text-align:center; padding: 10px;" colspan="13"><strong><?php echo get_option('companyname'); ?></strong></td></tr>' +
        '<tr><td style="text-align:center; padding: 5px;" colspan="13"><?php echo get_option('address'); ?></td></tr>' +
        '<tr><td style="text-align:center; padding: 10px;" colspan="13"><strong>Shortage List</strong></td></tr>' +
        '<tr><td style="text-align:center; padding: 5px;" colspan="13">' + html_filter_name + '</td></tr>' +
        '</table>';
    
    var print_data = stylesheet + heading_data + tableData;
    var newWin = window.open("", "_blank");
    newWin.document.write(print_data);
    newWin.document.close();
    newWin.focus();
    newWin.print();
    newWin.close();
}

// Sorting functionality
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
            return ascending ? valA.localeCompare(valB) : valB.localeCompare(valA);
        }
    });
    table.append(rows);
});
</script>
</body>
</html>