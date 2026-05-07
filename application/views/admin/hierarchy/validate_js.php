<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>-->

<script>
    jQuery(document).ready(function() {
        
   jQuery("#hierachy_form").validate({
      rules: {
         job_position: 'required',
         from_staff: 'required',
         to_staff: 'required',
         select_staff: 'required',
         /*challan_driver: {
				required: {
					depends: function(element) {
						return (jQuery('select[name="challan_vehicle"]').val() == "9") ? false : true
					}
				}
			},
		vahicle_number: {
				required: {
					depends: function(element) {
						return (jQuery('select[name="challan_vehicle"]').val() == "9") ? true : false
					}
				}
			},*/
         /*u_email: {
            required: true,
            email: true,//add an email rule that will ensure the value entered is valid email id.
            maxlength: 255,
         },*/
      }
   });
});
</script>
