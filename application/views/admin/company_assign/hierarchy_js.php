<script>
    $('#all_staff').on('change', function() {
				var id = $(this).val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/company_assign/select_staff";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                           

                               $("#company_select").children().remove();
                               $('#company_select').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#company_select').append('<option value="' + value.id + '">' + value.company_name + '</option>');
                                });
                               
                                $("#company_select").selectpicker("refresh");
                             
                            
                        }
                    });
			});
	$('#company_select').on('change', function() {
				var id = $(this).val();
				var all_staff = $("#all_staff").val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/company_assign/get_distributor_by_company";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id,all_staff: all_staff},
                        dataType:'json',
                        success: function(data) {
                           
                        $("#distributor_select").children().remove();
                               $('#distributor_select').append('<option value="">Non Selected</option>');
                               if(data == false){
                                   
                               }else{
                                   $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#distributor_select').append('<option value="' + value.AccountID + '">' + value.company + '</option>');
                                });
                               }
                                
                               
                                $("#distributor_select").selectpicker("refresh");
                             
                            
                        }
                    });
			});
	$('#company_select').on('change', function() {
				var id = $(this).val();
				
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/company_assign/get_staff_by_company";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                           
                        $("#transfer_to").children().remove();
                               $('#transfer_to').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#transfer_to').append('<option value="' + value.staffid + '">' + value.firstname + ' ' + value.lastname + '</option>');
                                });
                               
                                $("#transfer_to").selectpicker("refresh");
                             
                            
                        }
                    });
			});
			
	
			
	$('#from_staff').on('change', function() {
				var id = $(this).val();
				var job_id = $("#job_position").val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/hierarchy/transfer_staff";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id, job_id: job_id},
                        dataType:'json',
                        success: function(data) {
                           
                        $("#to_staff").children().remove();
                               $('#to_staff').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#to_staff').append('<option value="' + value.staffid + '">' + value.firstname + value.lastname + '</option>');
                                });
                               
                                $("#to_staff").selectpicker("refresh");
                             
                            
                        }
                    });
			});
    $('#to_staff').on('change', function() {
				var id = $(this).val();
				//var job_id = $("#job_position").val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/hierarchy/to_staff";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                         
                        var report_to = data.team_manage;
                        $("#tostaff_report_to").val(report_to);
                        /*$("#to_staff").children().remove();
                               $('#to_staff').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#to_staff').append('<option value="' + value.staffid + '">' + value.firstname + value.lastname + '</option>');
                                });
                               
                                $("#to_staff").selectpicker("refresh");
                             
                            */
                        }
                    });
			});
    
</script>