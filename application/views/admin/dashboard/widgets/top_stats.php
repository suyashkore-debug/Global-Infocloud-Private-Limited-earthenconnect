<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="widget relative" id="widget-<?php echo create_widget_id(); ?>" data-name="<?php echo _l('quick_stats'); ?>">
      <div class="widget-dragger"></div>
      <div class="row">
      <?php
         $initial_column = 'col-lg-2';
         if(!is_staff_member() && ((!has_permission('invoices','','view') && !has_permission('invoices','','view_own') && (get_option('allow_staff_view_invoices_assigned') == 0
           || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()))))) {
            $initial_column = 'col-lg-6';
         } else if(!is_staff_member() || (!has_permission('invoices','','view') && !has_permission('invoices','','view_own') && (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()) || (get_option('allow_staff_view_invoices_assigned') == 0 && (!has_permission('invoices','','view') && !has_permission('invoices','','view_own'))))) {
            $initial_column = 'col-lg-4';
         }
      ?>
         <?php if(has_permission('invoices','','view') || has_permission('invoices','','view_own') || (get_option('allow_staff_view_invoices_assigned') == '1' && staff_has_assigned_invoices())){ ?>
         <div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
               <?php
                  $total_invoices = total_rows(db_prefix().'invoices','status NOT IN (5,6)'.(!has_permission('invoices','','view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $total_invoices_awaiting_payment = total_rows(db_prefix().'invoices','status NOT IN (2,5,6)'.(!has_permission('invoices','','view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $percent_total_invoices_awaiting_payment = ($total_invoices > 0 ? number_format(($total_invoices_awaiting_payment * 100) / $total_invoices,2) : 0);
                  $percent_total_customer = (count($total_customer) > 0 ? number_format((count($active_customer) * 100) / count($total_customer),2) : 0);
				  
				  
                  ?>
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-balance-scale"></i> <?php echo _l('Customers'); ?>
                  <span class="pull-right"><?php echo count($active_customer); ?> / <?php echo count($total_customer); ?></span>
               </p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_total_customer; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_total_customer; ?>">
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
         <?php if(is_staff_member()){ ?>
         <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
               <?php
                  $where = '';
                  if(!is_admin()){
                    $where .= '(addedfrom = '.get_staff_user_id().' OR assigned = '.get_staff_user_id().')';
                  }
                  // Junk leads are excluded from total
                  $total_leads = total_rows(db_prefix().'leads',($where == '' ? 'junk=0' : $where .= ' AND junk =0'));
                  if($where == ''){
                   $where .= 'status=1';
                  } else {
                   $where .= ' AND status =1';
                  }
                  $total_leads_converted = total_rows(db_prefix().'leads',$where);
                  $percent_total_leads_converted = ($total_leads > 0 ? number_format(($total_leads_converted * 100) / $total_leads,2) : 0);
                  $percent_total_staff = (count($total_staff) > 0 ? number_format((count($active_staff) * 100) / count($total_staff),2) : 0);
                  ?>
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-tty"></i> <?php echo _l('Staff'); ?>
                  <span class="pull-right"><?php echo count($active_staff); ?> / <?php echo count($total_staff); ?></span>
               </p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_total_staff; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_total_staff; ?>">
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
         <div class="quick-stats-projects col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
               <?php
                  $_where = '';
                  $project_status = get_project_status_by_id(2);
                  if(!has_permission('projects','','view')){
                    $_where = 'id IN (SELECT project_id FROM '.db_prefix().'project_members WHERE staff_id='.get_staff_user_id().')';
                  }
                  $total_projects = total_rows(db_prefix().'projects',$_where);
                  $where = ($_where == '' ? '' : $_where.' AND ').'status = 2';
                  $total_projects_in_progress = total_rows(db_prefix().'projects',$where);
                  $percent_in_progress_projects = ($total_projects > 0 ? number_format(($total_projects_in_progress * 100) / $total_projects,2) : 0);
                  $percent_vendors = (count($total_vendor) > 0 ? number_format((count($active_vendor) * 100) / count($total_vendor),2) : 0);
                  ?>
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-cubes"></i> <?php echo _l('Vendor'); ?><span class="pull-right"><?php echo count($active_vendor); ?> / <?php echo count($total_vendor); ?></span></p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar no-percent-text not-dynamic" style="background:<?php echo $project_status['color']; ?>" role="progressbar" aria-valuenow="<?php echo $percent_vendors; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_vendors; ?>">
                  </div>
               </div>
            </div>
         </div>
         <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
               <?php
                  $_where = '';
                  if (!has_permission('tasks', '', 'view')) {
                    $_where = db_prefix().'tasks.id IN (SELECT taskid FROM '.db_prefix().'task_assigned WHERE staffid = ' . get_staff_user_id() . ')';
                  }
                  $total_tasks = total_rows(db_prefix().'tasks',$_where);
                  $where = ($_where == '' ? '' : $_where.' AND ').'status != '.Tasks_model::STATUS_COMPLETE;
                  $total_not_finished_tasks = total_rows(db_prefix().'tasks',$where);
                  $percent_not_finished_tasks = ($total_tasks > 0 ? number_format(($total_not_finished_tasks * 100) / $total_tasks,2) : 0);
                  $percent_ledgerAcc = (count($total_ledgerAcc) > 0 ? number_format((count($active_ledgerAcc) * 100) / count($total_ledgerAcc),2) : 0);
                  ?>
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-tasks"></i> <?php echo _l('Ledger Accounts'); ?> <span class="pull-right">
                  <?php echo count($active_ledgerAcc); ?> / <?php echo count($total_ledgerAcc); ?>
                  </span>
               </p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-default no-percent-text not-dynamic" role="progressbar" aria-valuenow="<?php echo $percent_ledgerAcc; ?>" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="<?php echo $percent_ledgerAcc; ?>">
                  </div>
               </div>
            </div>
         </div>
         <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
              
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-tasks"></i> <?php echo _l('On Time Delivery'); ?> <span class="pull-right">
                  <?php echo $LateOntimeDeliveries['OnTimeDelivery']; ?>
                  </span>
               </p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-success no-percent-text not-dynamic" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="100">
                  </div>
               </div>
            </div>
         </div>
         <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper">
              
               <p class="text-uppercase mtop5"><i class="hidden-sm fa fa-tasks"></i> <?php echo _l('Late Delivery'); ?> <span class="pull-right">
                  <?php echo $LateOntimeDeliveries['LateDelivery']; ?>
                  </span>
               </p>
               <div class="clearfix"></div>
               <div class="progress no-margin progress-bar-mini">
                  <div class="progress-bar progress-bar-danger no-percent-text not-dynamic" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 0%" data-percent="100">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
