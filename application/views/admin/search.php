<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<ul class="dropdown-menu search-results animated fadeIn no-mtop display-block" id="top_search_dropdown">
    <?php
    $total = 0;
    foreach($result as $data){
       if(count($data['result']) > 0){
           $total++;
           ?>
           <li role="separator" class="divider"></li>
           <li class="dropdown-header" style="color: #333;"><?php echo '<b >'.$data['search_heading'].'</b>'; ?></li>
       <?php } ?>
       <?php foreach($data['result'] as $_result){
        $output = '';
        switch($data['type']){
            case 'clients':
            $output = '<a href="'.admin_url('clients/AddEditAccount/'.$_result['AccountID']).'"><span>'.$_result['company'] .'<br>('.$_result['AccountID'].')- '.$_result['StationName'].'</span></a>';
            break;
            case 'staff':
            $output = '<a href="'.admin_url('hr_profile/AddEditStaff/'.$_result['AccountID']).'"><span>'.$_result['firstname']. ' ' . $_result['lastname'] .'<br>('.$_result['AccountID'].')</span></a>';
            break;
            case 'Vendor':
            $output = '<a href="'.admin_url('purchase/AddEditVendor/'.$_result['AccountID']).'"><span>'.$_result['company'] .'<br>('.$_result['AccountID'].')</span></a>';
            break;
            case 'Head':
            $output = '<a href="'.admin_url('accounts_master/AddEditAccountHead/'.$_result['AccountID']).'"><span>'.$_result['company'] .'<br>('.$_result['AccountID'].')</span></a>';
            break;
        }
        ?>
        <li style="border-bottom: 1px solid #eee;"><?php echo hooks()->apply_filters('global_search_result_output', $output, ['result'=>$_result, 'type'=>$data['type']]); ?></li>
    <?php } ?>
<?php } ?>
<?php if($total == 0){ ?>
    <li class="padding-5 text-center search-no-results"><?php echo _l('not_results_found'); ?></li>
<?php } ?>
</ul>
