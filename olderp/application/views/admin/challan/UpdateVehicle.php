<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-9">
        <div class="panel_s">
          <div class="panel-body">
                
                  <?php //echo form_open('admin/accounts_master/',array('id'=>'accounting_head')); ?>
                
                <div class="row">
                    
                    <div class="col-md-3">
                        <div class="form-group">
    						<label for="block_ac" class="control-label">Select Challan</label>
    						<select class="selectpicker" data-width="100%" data-action-box="true" tabindex="-98" name="ChallanID" data-live-search="true" id="ChallanID">
    						    <option value="">Select Challan</option>
    						    <?php
    						        foreach($ChallanList as $chl){
    						    ?>
    						            <option value="<?php echo $chl["ChallanID"]; ?>"><?php echo $chl["ChallanID"]; ?></option>
    						    <?php } ?>
    						</select>
						</div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="VehicleNo">Vehicle No</label>
                            <input type="text" class="form-control" name="VehicleNo" id="VehicleNo" value="" disabled>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="NewVehicleNo">New Vehicle No</label>
                            <input type="text" class="form-control" name="NewVehicleNo" id="NewVehicleNo" value="">
                        </div>
                    </div>

                </div>
               
                <div class="row">
                    
                    <div class="col-md-12">
                        <?php if (has_permission('change_vehicle', '', 'edit')) {
                        ?>
                        <button type="button" class="btn btn-info saveBtn" onclick="this.disabled = true;" style="margin-right: 25px;">Update</button>
                        <?php
                        }?>
                    </div>
                    
                </div>
                
          </div>
        </div>
        
      </div>
      
    </div>
  </div>
</div>

<?php init_tail(); ?>


<script>
    $(document).ready(function(){
        
        $('#ChallanID').on('change',function(){ 
            ChallanID = $(this).val();
            //alert(ChallanID);
            $.ajax({
                url:"<?php echo admin_url(); ?>Challan/GetVehicleByChallan",
                dataType:"JSON",
                method:"POST",
                data:{ChallanID:ChallanID},
                beforeSend: function () {
                $('.searchh2').css('display','block');
                $('.searchh2').css('color','blue');
                },
                complete: function () {
                $('.searchh2').css('display','none');
                },
                success:function(data){
                    $('#VehicleNo').val(data.VehicleID);
                }
            });
        });
        
        // Update Exiting Item
        $('.saveBtn').on('click',function(){ 
            NewVehicleNo = $('#NewVehicleNo').val();
            ChallanID = $('#ChallanID').val();
            VehicleNo = $('#VehicleNo').val();
            
            if(ChallanID == ""){
                alert('please delect Challan');
                $('.saveBtn').removeAttr('disabled');
                $('#ChallanID').focus();
            }else if(NewVehicleNo == ''){
                alert('please enter New Vehicle Number');
                $('.saveBtn').removeAttr('disabled');
                $('#NewVehicleNo').focus();
            }else{
                $.ajax({
                    url:"<?php echo admin_url(); ?>Challan/UpdateVehicle",
                    dataType:"JSON",
                    method:"POST",
                    data:{NewVehicleNo:NewVehicleNo,VehicleNo:VehicleNo,ChallanID:ChallanID
                    },
                    beforeSend: function () {
                    $('.searchh3').css('display','block');
                    $('.searchh3').css('color','blue');
                    },
                    complete: function () {
                    $('.searchh3').css('display','none');
                    },
                    success:function(data){
                       if(data == true){
                           alert_float('success', 'Record updated successfully...');
                           $('#NewVehicleNo').val('');
                            $('#VehicleNo').val('');
                           $('select[name=ChallanID]').val('');
                           $('.selectpicker').selectpicker('refresh');
                           $('.saveBtn').removeAttr('disabled');
                       }else{
                           $('.saveBtn').removeAttr('disabled');
                           alert_float('warning', 'Data not updated...');
                       }
                    }
                });
            }
            
        });
    });
</script>

</body>
</html>