<!--<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>-->

<script>
    jQuery(document).ready(function() {
        
   jQuery("#company_assign_form").validate({
      rules: {
         all_staff: 'required',
         company_select: 'required',
         distributor_select: 'required',
         transfer_to: 'required',
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
