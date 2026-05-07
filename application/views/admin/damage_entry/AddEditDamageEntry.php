<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<?php
			    echo form_open($this->uri->uri_string(),array('id'=>'pur_order-form','class'=>'_transaction_form'));
			?>
			<div class="col-md-12">
        <div class="panel_s accounting-template estimate">
        <div class="panel-body">
		
				<nav aria-label="breadcrumb">
            				<ol class="breadcrumb custombreadcrumb" style="background-color:#fff !important; margin-Bottom:0px !important;">
            					<li class="breadcrumb-item"><a href="<?= admin_url();?>"><b><i class="fa fa-home fa-fw fa-lg"></i></b></a></li>
            					<li class="breadcrumb-item active text-capitalize"><b>Inventory</b></li>
            					<li class="breadcrumb-item active" aria-current="page"><b>Damage Entry</b></li>
							</ol>
						</nav>
                        <hr class="hr_style">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="general_infor">
                <div class="row">
                        <?php
            			   $selected_company = $this->session->userdata('root_company');
            			   $fy = $this->session->userdata('finacial_year');
                            if($selected_company == 1){
                                $next_damage_entry_number = get_option('next_dmg_number_for_gf');
                            }
                            $prefix = "DMG";
                            $prefix = $prefix.'<span id="prefix_year">'.$fy.'</span>';
                            $_damage_entry_number = str_pad($next_damage_entry_number, get_option('number_padding_prefixes'), '0', STR_PAD_LEFT);
                        ?>
                        <?php 
                        if(isset($damage_details)){
                            $oldNo = $damage_details->DamageID;
                        } 
                        ?>
                          
                          <div class="col-md-2">
                            <div class="form-group ">
                                <label for="DamageNo">Damage No.</label>
                                <input type="hidden" name="OldDamageNo" id="OldDamageNo" value="<?php echo $oldNo; ?>">
                                <div class="input-group">
                                    <span class="input-group-addon"><?php echo $prefix; ?></span>
                                    <input type="text" name="DamageNo" id="DamageNo" class="form-control " value="<?php if(isset($damage_details)){ echo substr($damage_details->DamageID,5);}else{ echo $_damage_entry_number; } ?>" <?php if(isset($damage_details)){ echo "disabled";} ?>>
                                </div>
                            </div>
                           
                      </div>
                        <div class="col-md-2" >
                           <?php
                                $fy_new  = $fy + 1;
                                $lastdate_date = '20'.$fy_new.'-03-31';
                                $curr_date = date('Y-m-d');
                                $curr_date_new    = new DateTime($curr_date);
                                $last_date_yr = new DateTime($lastdate_date);
                                if($last_date_yr < $curr_date_new){
                                    $date1 = $lastdate_date;
                                }else{
                                    $date1 = date('Y-m-d');
                                }
                                $damage_details_date = (isset($damage_details) ? _d(substr($damage_details->Transdate,0,10)) : _d($date1));
                                echo render_date_input('DamageDate','Damage Date',$damage_details_date);  
                            ?>
                        </div>
                        <!--<div class="col-md-2">
                            <div class="form-group">
                                <label for="TotalCrates">Total Crates</label>
                                <input type="text" readonly class="form-control" value="<?= number_format($damage_details->TotalCrates, 2, '.', ''); ?>" name="TotalCrates" id="TotalCrates" placeholder="Total Crates" aria-invalid="false">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="TotalCases">Total Cases</label>
                                <input type="text" readonly class="form-control" value="<?= number_format($damage_details->TotalCases, 2, '.', ''); ?>" name="TotalCases" id="TotalCases" placeholder="Total Cases" aria-invalid="false">
                            </div>
                        </div>-->
                        <!--<div class="clearfix"></div>-->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AccountID">Account Name</label>
                                  <?php $value = (isset($damage_details) ? $damage_details->AccountID : ''); ?>
                                <select name="AccountID" id="AccountID" class="selectpicker" data-live-search="true" data-width="100%" data-none-selected-text="<?php echo _l('ticket_settings_none_assigned'); ?>" >
                                    
                                <?php foreach($DamageAccounts as $s) { ?>
                                    <option value="<?php echo html_entity_decode($s['AccountID']); ?>" <?php if($damage_details->AccountID == $s['AccountID']){echo "Selected";} ?>><?php echo html_entity_decode($s['company'])." - ".html_entity_decode($s['AccountID']); ?></option>
                                <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 20px;">
                          <span></span><a href="#" class="btn btn-warning edit-new-order">View List</a>
                        </div>
                    </div>
                
            </div>
            </div>
        </div>
        <div class="panel-body mtop10">
            <div class="row col-md-12">
            <p class="bold p_style"><?php echo _l('Damage entry form'); ?></p>
            <hr class="hr_style"/>
            <div class="" id="example">
            </div>
             <?php echo form_hidden('pur_order_detail'); ?>
                <div class="col-md-4">
                </div>
                <div class="col-md-4">
                </div>
                <div class="col-md-4 ">
                </div> 
             
            </div>
        </div>
        <div class="panel-body mtop10">
            <div class="row">
            <div class="col-md-12 mtop15">
                <div id="vendor_data">
                </div>

                <div class="btn-bottom-toolbar text-right" style="width: 100%;">
                <?php
                if(isset($damage_details)){
                    if (has_permission_new('damage_entry', '', 'edit')) { 
                        $fy_new  = $fy + 1;
                        $first_date = '20'.$fy.'-04-01';
                        $lastdate_date = '20'.$fy_new.'-03-31';
                        $curr_date = date('Y-m-d');
                        $lgstaff = $this->session->userdata('staff_user_id');
                        $Damage_date = substr($damage_details->Transdate,0,10);
                
                        $Damage_date_new    = new DateTime($Damage_date);
                        $first_date_yr = new DateTime($first_date);
                        $last_date_yr = new DateTime($lastdate_date);
                        $curr_date_new = new DateTime($curr_date);
                
                        if($curr_date_new > $last_date_yr){
                            $lastdate = $lastdate_date;
                        }else{
                            $lastdate = date('Y-m-d');
                        }
                
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "damage_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                
                        if($day == 0){
                            $return = '';
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($Damage_date_new < $tillDate_new) {
                                $return = 'disabled';
                            }else{
                                $return = '';
                            }
                        }
                        if($return == "disabled"){
                        ?>
                            <a href="#" class="btn btn-info <?php echo $return;?>">Update</a>
                    <?php
                        }else{
                    ?>
                            <button type="button" class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                             Update</button>
                        <?php
                        }
                    }
                }else{
                        if (has_permission_new('damage_entry', '', 'create')) {
                    ?>
                            <button type="button"  class="btn-tr save_detail btn btn-info mleft10 estimate-form-submit transaction-submit">
                            <?php echo _l('submit'); ?>
                            </button>
                    <?php
                        }
                } 
            ?>
                </div>
            </div>
          </div>
          
           <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="estimate">Basic Amt</label>
                        <input type="text" readonly class="form-control" name="basic_amt" id="basic_amt"  aria-invalid="false" <?php if(isset($damage_details)){?>value="<?= ($damage_details->DamageAmt-$damage_details->cgstamt-$damage_details->sgstamt-$damage_details->igstamt) ?>" <?php } ?>>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="estimate">CGST Amt</label>
                        <input type="text" readonly class="form-control" name="cgst_amt" id="cgst_amt"  aria-invalid="false" value="<?= $damage_details->cgstamt ?>" >
                     </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="estimate">SGST Amt</label>
                       <input type="text" readonly class="form-control" name="sgst_amt" id="sgst_amt"  aria-invalid="false" value="<?= $damage_details->sgstamt ?>" >
                    </div>
                </div>
                <div class="col-md-2">
             <label for="estimate">IGST Amt</label>
                       <input type="text" readonly class="form-control" name="igst_amt" id="igst_amt"  aria-invalid="false" value="<?= $damage_details->igstamt ?>">
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="estimate">Total Dmg Amt</label>
                        <input type="text" readonly class="form-control" name="dmg_amt"  id="dmg_amt"  aria-invalid="false" value="<?= $damage_details->DamageAmt ?>" >
                    </div>
                </div>
                
            </div> 
        </div>
        </div>
        </div>
        </div>

			</div>
			<?php echo form_close(); ?>
			
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="transfer-modal">
   <div class="modal-dialog modal-lg" style=" max-width: 1230px;">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title"> Damage List</h4>
         </div>
         <div class="modal-body" style="padding:5px;">
             
            <div class="row">
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
                <div class="col-md-3">
                    <?php
                   echo render_date_input('from_date','From',$from_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <?php
                   echo render_date_input('to_date','To',$to_date);
                   ?>
                </div>
                <div class="col-md-3">
                    <br>
                    <button class="btn btn-info pull-left mleft5 search_data" id="search_data"><?php echo _l('rate_filter'); ?></button>
                </div>
                <div class="col-md-3">
                    <br>
                    <input type="text" id="myInput1" onkeyup="myFunction2()" placeholder="Search for names.." title="Type in a name" style="float: right;">
                </div>
                <div class="col-md-12">
                 
            <div class="table_damage_report">
             
              <table class="tree table table-striped table-bordered table_damage_report" id="table_damage_report" width="100%">
                  
                <thead>
                  <tr>
                             <th class="sortablePop">Dmamge ID</th>
                             <th class="sortablePop">Damage Date</th>
                             <th class="sortablePop">Account Name</th>
                             <th class="sortablePop">Damage Amt</th>
                             <th class="sortablePop">GST Amt</th>
                             <th class="sortablePop">Net Damage Amt</th>
                             <th class="sortablePop">UserID</th>
                          </tr>
                </thead>
                <tbody>
                </tbody>
              </table>   
            </div>
            <span id="searchh2" style="display:none;">
                                Loading.....
                            </span>
                    
                </div>
              </div>
              
         </div>
        
         
      </div>
   </div>
</div>
<style>
    .table_damage_report { overflow: auto;max-height: 60vh;width:100%;position:relative;top: 0px; }
.table_damage_report thead th { position: sticky; top: 0; z-index: 1; }
.table_damage_report tbody th { position: sticky; left: 0; }

/* Just common table stuff. Really. */
.table_damage_report table  { border-collapse: collapse; width: 100%; }
.table_damage_report th, td { padding: 3px 3px !important; white-space: nowrap;font-size:11px; line-height:1.42857143;vertical-align: middle;}
.table_damage_report th     { background: #50607b;color: #fff !important; }


#table_damage_report tr:hover {
    background-color: #ccc;
}

#table_damage_report td:hover {
    cursor: pointer;
}
</style>

<?php init_tail(); ?>

</body>
<style>
    table.dataTable tbody td {
    padding: 4px 4px !important;
    font-size: 11px;
}
</style>
</html>

<?php $this->load->view('admin/damage_entry/damage_entry_js'); ?>
<script type="text/javascript" language="javascript" >
$(document).ready(function(){
    $('.edit-new-order').on('click', function(){
        $('#transfer-modal').find('button[type="submit"]').prop('disabled', false);
        $('#transfer-modal').modal('show');
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
        load_data(from_date,to_date);
    });
  function load_data(from_date,to_date)
  {
    $.ajax({
      url:"<?php echo admin_url(); ?>Damage_entry/load_data_for_damage_list",
      dataType:"JSON",
      method:"POST",
      data:{from_date:from_date, to_date:to_date},
      beforeSend: function () {
        $('#searchh2').css('display','block');
        $('.table_damage_report tbody').css('display','none');
     },
      complete: function () {
        $('.table_damage_report tbody').css('display','');
        $('#searchh2').css('display','none');
     },
      success:function(data){
        //   console.log(data);return false;
        var html = '';
        for(var count = 0; count < data.length; count++)
        {  
            var url = "'<?php echo admin_url() ?>Damage_entry/AddEdit/"+data[count].DamageID+"'";
            html += '<tr onclick="location.href='+url+'">';
            html += '<td style="text-align:center;">'+data[count].DamageID+'</td>';
            var date = data[count].Transdate.substring(0, 10)
            var date_new = date.split("-").reverse().join("/");
            html += '<td  style="text-align:center;">'+date_new+'</td>';
            var AccoutName = data[count].company;
            var GSTAmt = data[count].cgstamt + data[count].sgstamt + data[count].igstamt;
            var SubTotal = parseFloat(GSTAmt) + parseFloat(data[count].DamageAmt);
            html += '<td  style="text-align:left;">'+ AccoutName +'</td>';
            html += '<td  style="text-align:right;">'+parseFloat(SubTotal).toFixed(2)+'</td>';
            html += '<td  style="text-align:right;">'+parseFloat(GSTAmt).toFixed(2)+'</td>';
            html += '<td style="text-align:right;">'+parseFloat(data[count].DamageAmt).toFixed(2)+'</td>';
            html += '<td style="text-align:left;">'+data[count].UserID+'</td>';
            html += '</tr>';
        }
        $('.table_damage_report tbody').html(html);
      
      }
    });
  }
  
    $('#search_data').on('click',function(){
        var from_date = $("#from_date").val();
	    var to_date = $("#to_date").val();
        load_data(from_date,to_date);
    });

});


	$(document).on("click", ".sortablePop", function () {
		var table = $("#table_damage_report tbody");
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

<script>
    function myFunction2() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput1");
  filter = input.value.toUpperCase();
  table = document.getElementById("table_damage_report");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
</script>
