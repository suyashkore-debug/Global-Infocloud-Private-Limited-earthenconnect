<script>
    $('#job_position').on('change', function() {
				var id = $(this).val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/hierarchy/select_staff";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                           

                               $("#from_staff").children().remove();
                               $('#from_staff').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#from_staff').append('<option value="' + value.staffid + '">' + value.firstname + value.lastname + '</option>');
                                });
                               
                                $("#from_staff").selectpicker("refresh");
                             
                            
                        }
                    });
			});
	$('#from_staff').on('change', function() {
				var id = $(this).val();
				//alert(id);
				var url = "<?php echo base_url(); ?>admin/hierarchy/reported_by_staff";
                    jQuery.ajax({
                        type: 'POST',
                        url:url,
                        data: {id: id},
                        dataType:'json',
                        success: function(data) {
                           
                        $("#select_staff").children().remove();
                               $('#select_staff').append('<option value="">Non Selected</option>');
                                $.each(data, function (index, value) {
                                // APPEND OR INSERT DATA TO SELECT ELEMENT.
                                $('#select_staff').append('<option value="' + value.staffid + '">' + value.firstname + value.lastname + '</option>');
                                });
                               
                                $("#select_staff").selectpicker("refresh");
                             
                            
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