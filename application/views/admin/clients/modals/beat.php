<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Modal Contact -->
<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open(admin_url('clients/form_beat/'),array('id'=>'contact-form','autocomplete'=>'off')); ?>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $title; ?><br /><small class="color-white" id=""><?php echo get_company_name($customer_id,true); ?></small></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                       
                        <?php //print_r($customer);?>
                       
                       
                       
                <?php 
                     //$customer_default_country = get_option('customer_default_country');
                     $selected =( isset($client) ? $client->country : $customer_default_country);
                     echo render_select( 'beat_dist',$customer,array( 'userid',array( 'company')), 'Distributor',$selected,array('data-none-selected-text'=>_l('dropdown_non_selected_tex'),'id'=>'beat_dist'));
                     ?>
                     
                     <?php $value=( isset($contact) ? $contact->firstname : ''); ?>
                     <?php $attrs = (isset($client_contacts) ? array() : array('id'=>'')); ?>
                        <?php echo render_input( 'distcity', 'Distributor City',$value,'text'); ?>
            
                        <!-- // For email exist check -->
                        <?php echo form_hidden('contactid',$contactid); ?>
                        <?php $value=( isset($contact) ? $contact->firstname : ''); ?>
                        <?php echo render_input( 'beat_code', 'Beat Code',$value); ?>
                        <?php $value=( isset($contact) ? $contact->lastname : ''); ?>
                        <?php echo render_input( 'beat_name', 'Beat Name',$value); ?>
                        
                
                
                
                <div class="clearfix"></div>
               
            </div>
        </div>
        <?php hooks()->do_action('after_contact_modal_content_loaded'); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
        <button type="submit" class="btn btn-info" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" data-form="#contact-form"><?php echo _l('submit'); ?></button>
    </div>
    <?php echo form_close(); ?>
</div>
</div>
</div>
<?php if(!isset($contact)){ ?>
    <script>
        $(function(){
            // Guess auto email notifications based on the default contact permissios
            var permInputs = $('input[name="permissions[]"]');
            $.each(permInputs,function(i,input){
                input = $(input);
                if(input.prop('checked') === true){
                    $('#contact_email_notifications [data-perm-id="'+input.val()+'"]').prop('checked',true);
                }
            });
        });
        
        $(document).on('change','#beat_dist', function() { 
        var id = $(this).val();
        var url = "<?php echo base_url(); ?>admin/clients/dist_fetch_detail/";
        jQuery.ajax({
            type: 'POST',
            url:url,
            data: {id: id},
            dataType:'json',
            success: function(data) {
               
                //$(".district").html(data);
                /*$("#diststate").val("madhav");*/
                 $("#distcity").val(data['city']);
                
                
            }
        });
        
        
    }); 
    </script>
<?php } ?>
