<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
  <div class="content">
      <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              <div class="inline-block new-contact-wrapper" data-title="<?php echo _l('customer_contact_person_only_one_allowed'); ?>"<?php if($disable_new_contacts){ ?> data-toggle="tooltip"<?php } ?>>
   <a href="#" onclick="beat(<?php echo $client->userid; ?>); return false;" class="btn btn-info new-contact mbot25<?php if($disable_new_contacts){echo ' disabled';} ?>">Add New Beat</a>
</div>
              
            </div>
        </div>
    </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="panel_s">
          <div class="panel-body">
              
              <table class="table dt-table scroll-responsive">
                    <thead>
                        <th>Id</th>
                        <th>Distributor</th>
                        <th>Beat Code</th>
                        <th>Beat Name</th>
                        <!--<th>Action</th>-->
                    </thead>
                    <tbody>
                     
                    <?php
                    foreach($allbeat as $beat) {
                    ?>
                        <tr>
                            <td><?php echo $beat["id"]; ?></td>
                            <td><?php echo get_Dist_Name($beat["beat_dist"]); ?></td>
                            <td><?php echo $beat["beat_code"]; ?></td>

                            <td><?php echo $beat["beat_name"]; ?></td>
                            
                            <!--<th><a href="<?php admin_url('vehicle')?>" class="btn btn-info">Edit</a></th>-->
                        </tr>
                     <?php } ?>
                  </tbody>
                  </table>
            <?php if(isset($consent_purposes)) { ?>
            <div class="row mbot15">
              <div class="col-md-3 contacts-filter-column">
               <div class="select-placeholder">
                <select name="custom_view" title="<?php echo _l('gdpr_consent'); ?>" id="custom_view" class="selectpicker" data-width="100%">
                 <option value=""></option>
                 <?php foreach($consent_purposes as $purpose) { ?>
                 <option value="consent_<?php echo $purpose['id']; ?>">
                  <?php echo $purpose['name']; ?>
                </option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
        
    </div>
  </div>
</div>
</div>
</div>
</div>
<?php init_tail(); ?>
<?php $this->load->view('admin/clients/client_js'); ?>
<div id="contact_data"></div>
<div id="consent_data"></div>
<script>
 $(function(){
  var optionsHeading = [];
  var allContactsServerParams = {
   "custom_view": "[name='custom_view']",
 }
 <?php if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){ ?>
  optionsHeading.push($('#th-consent').index());
  <?php } ?>
  _table_api = initDataTable('.table-all-contacts', window.location.href, optionsHeading, optionsHeading, allContactsServerParams, [0,'asc']);
  if(_table_api) {
   <?php if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){ ?>
    _table_api.on('draw', function () {
      var tableData = $('.table-all-contacts').find('tbody tr');
      $.each(tableData, function() {
        $(this).find('td:eq(2)').addClass('bg-light-gray');
      });
    });
    $('select[name="custom_view"]').on('change', function(){
      _table_api.ajax.reload()
      .columns.adjust()
      .responsive.recalc();
    });
    <?php } ?>
  }
});
</script>
</body>
</html>
