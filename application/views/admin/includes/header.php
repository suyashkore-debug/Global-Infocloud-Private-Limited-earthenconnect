<?php defined('BASEPATH') or exit('No direct script access allowed');
ob_start();
?>
<style>
    #table-company_select tr:hover {
        background-color: #ccc;
    }
    
    #table-company_select td:hover {
        cursor: pointer;
    }
</style>
<li id="top_search" class="dropdown" data-toggle="tooltip" data-placement="bottom" data-title="search by name...">
   <input type="search" id="search_input" class="form-control" placeholder="<?php echo _l('top_search_placeholder'); ?>">
   <div id="search_results">
   </div>
   <ul class="dropdown-menu search-results animated fadeIn no-mtop search-history" id="search-history">
   </ul>
</li>
<li id="top_search_button">
   <button class="btn"><i class="fa fa-search"></i></button>
</li>
<?php
$top_search_area = ob_get_contents();
ob_end_clean();
?>

<?php
if (!isset($quickActions) || !is_array($quickActions)) {
    $quickActions = [];
}
if (!isset($totalQuickActionsRemoved) || !is_numeric($totalQuickActionsRemoved)) {
    $totalQuickActionsRemoved = 0;
}

$staff_permission = get_staff_permission($current_user->staffid);
    /*echo "<pre>";
    print_r($current_user);
    print_r($staff_permission);
    die;*/
        if($this->session->userdata('root_company')){
            
        }else {
        ?>
        <div class="row">
            <div class="modal company_selection" id="company_selection" tabindex="-1" role="dialog" style="display: block;">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header" style="padding:0px;">
                      <h4 style="text-align:center;">Select Your Plant and Year</h4>
                   </div>
                   
                   <div class="modal-body" style="padding-bottom: 2px;padding-top: 0px;">
                       <?php //echo form_open('admin/dashboard/set_root_company1',array()); ?>
                    <div class="row">
                    <div class="table-company_select" style="overflow: auto;max-height: 40vh;width:100%;position:relative;top: 0px;border-collapse: collapse;">
                        <table class="tree table table-striped table-bordered table-company_select" id="table-company_select" width="100%" style="margin-top: 0px;">
                        <thead>
                            <!--<th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">Tag</th>-->
                            <th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">PlantID</th>
                            <th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">FY</th>
                            <th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">FirmName</th>
                            <th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">YearFrom</th>
                            <th style="padding: 1px 5px !important;position: sticky; top: 0; z-index: 1;background:#50607b;color:#fff;">YearTo</th>
                        </thead>  
                        
                        <?php
                                    $staff_details = get_staff($current_user->staffid);
                                    $staff_company = unserialize($staff_details->staff_comp);
                        
                        //echo "<pre>";
                        //print_r($staff_permission);
                        $i = 1;
                        if(empty($staff_permission)){
                            ?>
                            <tr>
                                <td colspan="6"><span>Access denied. Please contact to administrator...</span></td>
                            </tr>
                            <?php
                        }
                        foreach ($staff_permission as $key1 => $value1) {
                            $Url = admin_url().'dashboard/SetCompanySession/'.substr($value1['YEARFROM'],2,2)."-".$value1['PlantID'];
                        ?>
                           <tr onclick="window.open('<?php echo $Url;?>','_self')">
                           <!--<td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:center;position: sticky; left: 0;">
                               <input type="radio"  name="company_id" id="company_id"  value="<?php echo substr($value1['YEARFROM'],2,2)."-".$value1['PlantID'];?>">
                           </td> -->
                           <td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:center;position: sticky; left: 0;"><?php echo $value1['PlantID']; ?></td>
                            <td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:center;position: sticky; left: 0;"><?php echo substr($value1['YEARFROM'],2,2); ?></td>
                            <td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:left;position: sticky; left: 0;">
                                <span><?php echo $value1['FIRMNAME']; ?></span>
                            </td>
                            <td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:center;position: sticky; left: 0;">
                                <span><?php echo date("d/m/Y", strtotime(substr($value1['YEARFROM'],0,10))); ?></span>
                            </td>
                            <td style="padding: 1px 5px !important;border:1px solid !important;font-size:11px;text-align:center;position: sticky; left: 0;">
                                <span><?php echo date("d/m/Y", strtotime(substr($value1['YEARTO'],0,10))); ?></span>
                            </td>
                                
                        </tr>
                    <?php
                            $i++;
                        }
                    ?>
                        </table>
                    </div>
                        
                    </div>
                    <div class="modal-footer" style="padding:2px;">
                      <!--<input type="submit" class="btn btn-info save_company1" data-dismiss="modal" value="select">-->
                    </div>
                    <!--</form>-->
                    <?php //echo form_close(); ?>
                  </div>
                </div>
      </div>
        </div>
        </div>
        <?php } ?>
<div id="header">
   <!--<div class="hide-menu"><i class="fa fa-align-left"></i></div>
   <div id="logo">
      <?php get_company_logo(get_admin_uri().'/') ?>
   </div>-->
   <nav>
    <ul class="nav navbar-nav navbar-left">
        <li class="icon header-company-select" data-toggle="tooltip" title="<?php echo get_root_company_name($this->session->userdata('root_company')); ?>" data-placement="bottom">
      <a href="#" class="dropdown-toggle company_select" data-toggle="dropdown" aria-expanded="false">
        <?php echo get_root_company_name($this->session->userdata('root_company')); ?>
      </a>
      <?php $root_company = get_all_root_company();
            $staff_details = get_staff($current_user->staffid);
            $staff_company = unserialize($staff_details->staff_comp);
      ?>
      <ul class="dropdown-menu animated fadeIn">
        <?php
        //print_r($staff_permission);
            /*foreach ($root_company as $key => $value) {
                # code...
            if(in_array($value['id'], $staff_company)){*/
            foreach($staff_permission as $key2 => $value2) {
            ?>
            <li class="header-my-company_select"><a href="<?php echo admin_url('dashboard/change_company/').substr($value2['YEARFROM'],2,2)."-".$value2['PlantID']; ?>" style="font-size:10px;"><?php echo $value2['FIRMNAME']." ( ".substr($value2['YEARFROM'],2,2)." )"; ?></a></li>
            <?php } //} ?>
         
         
      </ul>
   </li>
        <?php
         hooks()->do_action('before_render_aside_menu');
         ?>
      <?php foreach($sidebar_menu as $key => $item){
         if(isset($item['collapse']) && count($item['children'] ?? []) === 0) {
           continue;
         }
         ?>
      <li class="icon header-company-select" data-toggle="tooltip" 
         <?php echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
         <a href="<?php echo count($item['children'] ?? []) > 0 ? '#' : $item['href']; ?>"
          aria-expanded="false" data-toggle="dropdown"
          <?php echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?>>
             <!--<i class="<?php echo $item['icon']; ?> menu-icon"></i>-->
             <span class="menu-text">
             <?php echo _l($item['name'],'', false); ?>
             </span>
             <!--<?php if(count($item['children'] ?? []) > 0){ ?>
             <span class="fa arrow"></span>
             <?php } ?>-->
         </a>
         <?php if(count($item['children'] ?? []) > 0){ ?>
         <ul class="dropdown-menu animated fadeIn" aria-expanded="false">
            <?php foreach($item['children'] as $submenu){
               ?>
            <li class="header-my-company_select"
              <?php echo _attributes_to_string(isset($submenu['li_attributes']) ? $submenu['li_attributes'] : []); ?>>
              <a href="<?php echo $submenu['href']; ?>"
               <?php echo _attributes_to_string(isset($submenu['href_attributes']) ? $submenu['href_attributes'] : []); ?>>
               <!--<?php if(!empty($submenu['icon'])){ ?>
               <i class="<?php echo $submenu['icon']; ?> menu-icon"></i>
               <?php } ?>-->
               <span class="sub-menu-text">
                  <?php echo _l($submenu['name'],'',false); ?>
               </span>
               </a>
            </li>
            <?php } ?>
         </ul>
         <?php } ?>
      </li>
      <?php hooks()->do_action('after_render_single_aside_menu', $item); ?>
      <?php } ?>
     <!-- 
      <?php if($this->app->show_setup_menu() == true && (is_staff_member() || is_admin())){ ?>
      <li<?php if(get_option('show_setup_menu_item_only_on_hover') == 1) { echo ' style="display:none;"'; } ?> id="setup-menu-item">
         <a href="#" class="open-customizer">
         <span class="menu-text">
            <?php echo _l('setting_bar_heading'); ?>
            <?php
                if ($modulesNeedsUpgrade = $this->app_modules->number_of_modules_that_require_database_upgrade()) {
                  echo '<span class="badge menu-badge bg-warning">' . $modulesNeedsUpgrade . '</span>';
                }
            ?>
         </span>
         </a>
         <?php } ?>
      </li>-->
      
      
     <!-- <?php if(is_staff_member()){ ?>
      <li class="icon header-newsfeed">
         <a href="#" class="open_newsfeed desktop" data-toggle="tooltip" title="<?php echo _l('whats_on_your_mind'); ?>" data-placement="bottom"><i class="fa fa-share fa-fw fa-lg" aria-hidden="true"></i></a>
      </li>
   <?php } ?>
   <li class="icon header-todo">
      <a href="<?php echo admin_url('todo'); ?>" data-toggle="tooltip" title="<?php echo _l('nav_todo_items'); ?>" data-placement="bottom"><i class="fa fa-check-square-o fa-fw fa-lg"></i>
         <span class="label bg-warning icon-total-indicator nav-total-todos<?php if($current_user->total_unfinished_todos == 0){echo ' hide';} ?>"><?php echo $current_user->total_unfinished_todos; ?></span>
      </a>
   </li>
   <li class="dropdown notifications-wrapper header-notifications" data-toggle="tooltip" title="<?php echo _l('nav_notifications'); ?>" data-placement="bottom">
      <?php $this->load->view('admin/includes/notifications'); ?>
   </li>-->
   <li class="dropdown notifications-wrapper header-notifications" data-toggle="tooltip" title="<?php echo _l('nav_notifications'); ?>" data-placement="bottom">
      <?php $this->load->view('admin/includes/notifications'); ?>
   </li>
    <li class="icon header-todo">
      <a href="<?php echo admin_url(); ?>" data-toggle="Home" title="Home" data-placement="bottom"><i class="fa fa-home fa-fw fa-lg"></i>
         
      </a>
    </li>
   <li class="icon header-todo" >
      <a href="#" aria-expanded="false" data-toggle="dropdown" title="" data-placement="bottom"><i class="fa fa-search fa-fw fa-lg"></i>
        </a>
        <ul class="dropdown-menu animated fadeIn">
            <?php
              if(!is_mobile()){
               echo $top_search_area;
            } ?>
        </ul>
   </li>
   
   <!--<li class="icon header-todo">
      <a href="#" data-toggle="tooltip" title="<?php echo _l('nav_logout'); ?>" data-placement="bottom"><i class="fa fa-power-off fa-fw fa-lg" onclick="logout(); return false;"></i>
        </a>
   </li>-->
   
   <li class="icon header-user-profile" data-toggle="tooltip" title="<?php echo get_staff_full_name(); ?>"
                    data-placement="bottom">
                    <a href="#" class="dropdown-toggle profile tw-block rtl:!tw-px-0.5 !tw-py-1" data-toggle="dropdown"
                        aria-expanded="false">
                        <?php echo staff_profile_image($current_user->staffid, ['img', 'img-responsive', 'staff-profile-image-small', 'tw-ring-1 tw-ring-offset-2 tw-ring-primary-500 tw-mx-1 tw-mt-2.5']); ?>
                    </a>
                    <ul class="dropdown-menu animated fadeIn">
                        <li class="header-my-profile"><a
                                href="#"><?php echo get_staff_full_name(); ?></a>
                        </li>
                        <li class="header-logout">
                            <a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a>
                        </li>
                        <!--<li class="header-my-profile"><a
                                href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
                        <li class="header-my-timesheets"><a
                                href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a>
                        </li>
                        <li class="header-edit-profile"><a
                                href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a>
                        </li>
                        <?php //if (!is_language_disabled()) { ?>
                        <li class="dropdown-submenu pull-left header-languages">
                            <a href="#" tabindex="-1"><?php echo _l('language'); ?></a>
                            <ul class="dropdown-menu dropdown-menu">
                                <li class="<?php echo $current_user->default_language == '' ? 'active' : ''; ?>">
                                    <a href="<?php echo admin_url('staff/change_language'); ?>">
                                        <?php echo _l('system_default_string'); ?>
                                    </a>
                                </li>
                                <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                <li
                                    class="<?php echo $current_user->default_language == $user_lang ? 'active' : ''; ?>">
                                    <a href="<?php echo admin_url('staff/change_language/' . $user_lang); ?>">
                                        <?php echo ucfirst($user_lang); ?>
                                    </a>
                                    <?php } ?>
                            </ul>
                        </li>-->
                        <?php //} ?>
                        
                    </ul>
                </li>
   <!--<li class="dashboard_user<?php if($totalQuickActionsRemoved == count($quickActions)){echo ' dashboard-user-no-qa';}?>">
         <?php //echo _l('welcome_top',$current_user->firstname); ?> <i class="fa fa-power-off" data-toggle="tooltip" data-title="<?php echo _l('nav_logout'); ?>" data-placement="bottom" onclick="logout(); return false;"></i>
      </li>-->
      
    </ul>
    <!--<ul class="nav metis-menu" id="side-menu">
      <li class="dashboard_user<?php if(isset($quickActions) && $totalQuickActionsRemoved == count($quickActions)){echo ' dashboard-user-no-qa';}?>">
         <?php echo _l('welcome_top',$current_user->firstname); ?> <i class="fa fa-power-off top-left-logout pull-right" data-toggle="tooltip" data-title="<?php echo _l('nav_logout'); ?>" data-placement="right" onclick="logout(); return false;"></i>
      </li>
      <?php if(isset($quickActions) && $totalQuickActionsRemoved != count($quickActions)){ ?>
      <li class="quick-links">
         <div class="dropdown dropdown-quick-links">
            <a href="#" class="dropdown-toggle" id="dropdownQuickLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            <i class="fa fa-gavel" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu" aria-labelledby="dropdownQuickLinks">
               <?php
                  foreach($quickActions as $key => $item){
                   $url = '';
                   if(isset($item['permission'])){
                     if(!has_permission($item['permission'],'','create')){
                      continue;
                    }
                  }
                  if(isset($item['custom_url'])){
                    $url = $item['url'];
                  } else {
                    $url = admin_url(''.$item['url']);
                  }
                  $href_attributes = '';
                  if(isset($item['href_attributes'])){
                    foreach ($item['href_attributes'] as $key => $val) {
                      $href_attributes .= $key . '=' . '"' . $val . '"';
                    }
                  }
                  ?>
               <li>
                  <a href="<?php echo $url; ?>" <?php echo $href_attributes; ?>>
                  <i class="fa fa-plus-square-o"></i>
                  <?php echo $item['name']; ?>
                  </a>
               </li>
               <?php } ?>
            </ul>
         </div>
      </li>
      <?php } ?>
      <?php
         hooks()->do_action('before_render_aside_menu');
         ?>
      <?php foreach($sidebar_menu as $key => $item){
         if(isset($item['collapse']) && count($item['children'] ?? []) === 0) {
           continue;
         }
         ?>
      <li class="menu-item-<?php echo $item['slug']; ?>"
         <?php echo _attributes_to_string(isset($item['li_attributes']) ? $item['li_attributes'] : []); ?>>
         <a href="<?php echo count($item['children'] ?? []) > 0 ? '#' : $item['href']; ?>"
          aria-expanded="false"
          <?php echo _attributes_to_string(isset($item['href_attributes']) ? $item['href_attributes'] : []); ?>>
             <i class="<?php echo $item['icon']; ?> menu-icon"></i>
             <span class="menu-text">
             <?php echo _l($item['name'],'', false); ?>
             </span>
             <?php if(count($item['children'] ?? []) > 0){ ?>
             <span class="fa arrow"></span>
             <?php } ?>
         </a>
         <?php if(count($item['children'] ?? []) > 0){ ?>
         <ul class="nav nav-second-level collapse" aria-expanded="false">
            <?php foreach($item['children'] as $submenu){
               ?>
            <li class="sub-menu-item-<?php echo $submenu['slug']; ?>"
              <?php echo _attributes_to_string(isset($submenu['li_attributes']) ? $submenu['li_attributes'] : []); ?>>
              <a href="<?php echo $submenu['href']; ?>"
               <?php echo _attributes_to_string(isset($submenu['href_attributes']) ? $submenu['href_attributes'] : []); ?>>
               <?php if(!empty($submenu['icon'])){ ?>
               <i class="<?php echo $submenu['icon']; ?> menu-icon"></i>
               <?php } ?>
               <span class="sub-menu-text">
                  <?php echo _l($submenu['name'],'',false); ?>
               </span>
               </a>
            </li>
            <?php } ?>
         </ul>
         <?php } ?>
      </li>
      <?php hooks()->do_action('after_render_single_aside_menu', $item); ?>
      <?php } ?>
      <?php if($this->app->show_setup_menu() == true && (is_staff_member() || is_admin())){ ?>
      <li<?php if(get_option('show_setup_menu_item_only_on_hover') == 1) { echo ' style="display:none;"'; } ?> id="setup-menu-item">
         <a href="#" class="open-customizer"><i class="fa fa-cog menu-icon"></i>
         <span class="menu-text">
            <?php echo _l('setting_bar_heading'); ?>
            <?php
                if ($modulesNeedsUpgrade = $this->app_modules->number_of_modules_that_require_database_upgrade()) {
                  echo '<span class="badge menu-badge bg-warning">' . $modulesNeedsUpgrade . '</span>';
                }
            ?>
         </span>
         </a>
         <?php } ?>
      </li>
      <?php //hooks()->do_action('after_render_aside_menu'); ?>
      <?php //$this->load->view('admin/projects/pinned'); ?>
   </ul>-->
</nav>
   <!--<nav>
      <div class="small-logo">
         <span class="text-primary">
            <?php get_company_logo(get_admin_uri().'/') ?>
         </span>
      </div>
      <div class="mobile-menu">
         <button type="button" class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed" data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
            <i class="fa fa-chevron-down"></i>
         </button>
         <ul class="mobile-icon-menu">
            <?php
               // To prevent not loading the timers twice
            if(is_mobile()){ ?>
               <li class="dropdown notifications-wrapper header-notifications">
                  <?php $this->load->view('admin/includes/notifications'); ?>
               </li>
               <li class="header-timers">
                  <a href="#" id="top-timers" class="dropdown-toggle top-timers" data-toggle="dropdown"><i class="fa fa-clock-o fa-fw fa-lg"></i>
                     <span class="label bg-success icon-total-indicator icon-started-timers<?php if ($totalTimers = count($startedTimers) == 0){ echo ' hide'; }?>"><?php echo count($startedTimers); ?></span>
                  </a>
                  <ul class="dropdown-menu animated fadeIn started-timers-top width300" id="started-timers-top">
                     <?php $this->load->view('admin/tasks/started_timers',array('startedTimers'=>$startedTimers)); ?>
                  </ul>
               </li>
            <?php } ?>
         </ul>
         <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;" role="navigation">
            <ul class="nav navbar-nav">
               <li class="header-my-profile"><a href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
               <li class="header-my-timesheets"><a href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a></li>
               <li class="header-edit-profile"><a href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a></li>
               <?php if(is_staff_member()){ ?>
                  <li class="header-newsfeed">
                   <a href="#" class="open_newsfeed mobile">
                     <?php echo _l('whats_on_your_mind'); ?>
                  </a>
               </li>
            <?php } ?>
            <li class="header-logout"><a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a></li>
         </ul>
      </div>
   </div>
   <ul class="nav navbar-nav navbar-right">
      <?php
      if(!is_mobile()){
       echo $top_search_area;
    } ?>
    <?php hooks()->do_action('after_render_top_search'); ?>
    <li class="icon header-user-profile" data-toggle="tooltip" title="<?php echo get_staff_full_name(); ?>" data-placement="bottom">
      <a href="#" class="dropdown-toggle profile" data-toggle="dropdown" aria-expanded="false">
         <?php echo staff_profile_image($current_user->staffid,array('img','img-responsive','staff-profile-image-small','pull-left')); ?>
      </a>
      <ul class="dropdown-menu animated fadeIn">
         <li class="header-my-profile"><a href="<?php echo admin_url('profile'); ?>"><?php echo _l('nav_my_profile'); ?></a></li>
         <li class="header-my-timesheets"><a href="<?php echo admin_url('staff/timesheets'); ?>"><?php echo _l('my_timesheets'); ?></a></li>
         <li class="header-edit-profile"><a href="<?php echo admin_url('staff/edit_profile'); ?>"><?php echo _l('nav_edit_profile'); ?></a></li>
         <?php if(!is_language_disabled()){ ?>
            <li class="dropdown-submenu pull-left header-languages">
               <a href="#" tabindex="-1"><?php echo _l('language'); ?></a>
               <ul class="dropdown-menu dropdown-menu">
                  <li class="<?php if($current_user->default_language == ""){echo 'active';} ?>"><a href="<?php echo admin_url('staff/change_language'); ?>"><?php echo _l('system_default_string'); ?></a></li>
                  <?php foreach($this->app->get_available_languages() as $user_lang) { ?>
                     <li<?php if($current_user->default_language == $user_lang){echo ' class="active"';} ?>>
                     <a href="<?php echo admin_url('staff/change_language/'.$user_lang); ?>"><?php echo ucfirst($user_lang); ?></a>
                  <?php } ?>
               </ul>
            </li>
         <?php } ?>
         
         <li class="header-logout">
            <a href="#" onclick="logout(); return false;"><?php echo _l('nav_logout'); ?></a>
         </li>
      </ul>
   </li>
   
   <li class="icon header-company-select" data-toggle="tooltip" title="<?php echo get_root_company_name($this->session->userdata('root_company')); ?>" data-placement="bottom">
      <a href="#" class="dropdown-toggle company_select" data-toggle="dropdown" aria-expanded="false">
        <?php echo get_root_company_name($this->session->userdata('root_company')); ?>
      </a>
      <?php $root_company = get_all_root_company();
            $staff_details = get_staff($current_user->staffid);
            $staff_company = unserialize($staff_details->staff_comp);
      ?>
      <ul class="dropdown-menu animated fadeIn">
        <?php
        //print_r($staff_permission);
            /*foreach ($root_company as $key => $value) {
                # code...
            if(in_array($value['id'], $staff_company)){*/
            foreach($staff_permission as $key2 => $value2) {
            ?>
            <li class="header-my-company_select"><a href="<?php echo admin_url('dashboard/change_company/').substr($value2['YEARFROM'],2,2)."-".$value2['PlantID']; ?>"><?php echo $value2['FIRMNAME']." ( ".substr($value2['YEARFROM'],2,2)." )"; ?></a></li>
            <?php } //} ?>
         
         
      </ul>
   </li>
   <?php //print_r($staff_details->staff_comp); ?>
   
   
   <?php if(is_staff_member()){ ?>
      <li class="icon header-newsfeed">
         <a href="#" class="open_newsfeed desktop" data-toggle="tooltip" title="<?php echo _l('whats_on_your_mind'); ?>" data-placement="bottom"><i class="fa fa-share fa-fw fa-lg" aria-hidden="true"></i></a>
      </li>
   <?php } ?>
   <li class="icon header-todo">
      <a href="<?php echo admin_url('todo'); ?>" data-toggle="tooltip" title="<?php echo _l('nav_todo_items'); ?>" data-placement="bottom"><i class="fa fa-check-square-o fa-fw fa-lg"></i>
         <span class="label bg-warning icon-total-indicator nav-total-todos<?php if($current_user->total_unfinished_todos == 0){echo ' hide';} ?>"><?php echo $current_user->total_unfinished_todos; ?></span>
      </a>
   </li>
   <li class="icon header-timers timer-button" data-placement="bottom" data-toggle="tooltip" data-title="<?php echo _l('my_timesheets'); ?>">
      <a href="#" id="top-timers" class="dropdown-toggle top-timers" data-toggle="dropdown">
         <i class="fa fa-clock-o fa-fw fa-lg" aria-hidden="true"></i>
         <span class="label bg-success icon-total-indicator icon-started-timers<?php if ($totalTimers = count($startedTimers) == 0){ echo ' hide'; }?>">
            <?php echo count($startedTimers); ?>
         </span>
      </a>
      <ul class="dropdown-menu animated fadeIn started-timers-top width350" id="started-timers-top">
         <?php $this->load->view('admin/tasks/started_timers',array('startedTimers'=>$startedTimers)); ?>
      </ul>
   </li>
   <li class="dropdown notifications-wrapper header-notifications" data-toggle="tooltip" title="<?php echo _l('nav_notifications'); ?>" data-placement="bottom">
      <?php $this->load->view('admin/includes/notifications'); ?>
   </li>
</ul>
</nav>-->

</div>
<div class="clearfix" style="
    margin: 26px 0px;
"></div>
<div id="mobile-search" class="<?php if(!is_mobile()){echo 'hide';} ?>">
   <ul>
      <?php
      if(is_mobile()){
       echo $top_search_area;
    } ?>
 </ul>
</div>
