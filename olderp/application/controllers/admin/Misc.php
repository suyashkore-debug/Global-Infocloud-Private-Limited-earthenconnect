<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Misc extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('misc_model');
    }

    public function fetch_address_info_gmaps()
    {
        include_once(APPPATH . 'third_party/JD_Geocoder_Request.php');

        $data    = $this->input->post();
        $address = '';

        $address .= $data['address'];
        if (!empty($data['city'])) {
            $address .= ', ' . $data['city'];
        }

        if (!empty($data['country'])) {
            $address .= ', ' . $data['country'];
        }

        $apiKey = get_option('google_api_key');
        if (empty($apiKey)) {
            echo json_encode([
                'response' => [
                    'status'        => 'MISSING_API_KEY',
                    'error_message' => 'Add Google API Key in Setup->Settings->Google',
                ],
            ]);
            die;
        }

        $georequest = new JD_Geocoder_Request($apiKey);
        $georequest->forwardSearch($address);
        echo json_encode($georequest);
    }

    public function get_currency($id)
    {
        echo json_encode(get_currency($id));
    }

    public function get_taxes_dropdown_template()
    {
        $name    = $this->input->post('name');
        $taxname = $this->input->post('taxname');
        echo $this->misc_model->get_taxes_dropdown_template($name, $taxname);
    }

    public function dismiss_cron_setup_message()
    {
        update_option('hide_cron_is_required_message', 1);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function dismiss_timesheets_notice_admins()
    {
        update_option('show_timesheets_overview_all_members_notice_admins', 0);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function dismiss_cloudflare_notice()
    {
        update_option('show_cloudflare_notice', 0);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function dismiss_php_version_notice()
    {
        update_option('show_php_version_notice', 0);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function clear_system_popup()
    {
        $this->session->unset_userdata('system-popup');
    }

    public function tinymce_file_browser()
    {
        $data['connector']   = admin_url() . '/utilities/media_connector';
        $data['mediaLocale'] = get_media_locale();
        $this->app_css->add('app-css', base_url($this->app_css->core_file('assets/css', 'style.css')) . '?v=' . $this->app_css->core_version(), 'editor-media');
        $this->load->view('admin/includes/elfinder_tinymce', $data);
    }

    public function get_relation_data()
    {
        if ($this->input->post()) {
            $type = $this->input->post('type');
            $data = get_relation_data($type);
            if ($this->input->post('rel_id')) {
                $rel_id = $this->input->post('rel_id');
            } else {
                $rel_id = '';
            }

            $relOptions = init_relation_options($data, $type, $rel_id);
            echo json_encode($relOptions);
            die;
        }
    }

    public function delete_sale_activity($id)
    {
        if (is_admin()) {
            $this->db->where('id', $id);
            $this->db->delete(db_prefix() . 'sales_activity');
        }
    }

    public function upload_sales_file()
    {
        handle_sales_attachments($this->input->post('rel_id'), $this->input->post('type'));
    }

    public function add_sales_external_attachment()
    {
        if ($this->input->post()) {
            $file = $this->input->post('files');
            $this->misc_model->add_attachment_to_database($this->input->post('rel_id'), $this->input->post('type'), $file, $this->input->post('external'));
        }
    }

    public function toggle_file_visibility($id)
    {
        $this->db->where('id', $id);
        $row = $this->db->get(db_prefix() . 'files')->row();
        if ($row->visible_to_customer == 1) {
            $v = 0;
        } else {
            $v = 1;
        }

        $this->db->where('id', $id);
        $this->db->update(db_prefix() . 'files', [
            'visible_to_customer' => $v,
        ]);
        echo $v;
    }

    public function format_date()
    {
        if ($this->input->post()) {
            $date = $this->input->post('date');
            $date = strtotime(current(explode('(', $date)));
            echo _d(date('Y-m-d', $date));
        }
    }

    public function send_file()
    {
        if ($this->input->post('send_file_email')) {
            if ($this->input->post('file_path')) {
                $this->load->model('emails_model');
                $this->emails_model->add_attachment([
                    'attachment' => $this->input->post('file_path'),
                    'filename'   => $this->input->post('file_name'),
                    'type'       => $this->input->post('filetype'),
                    'read'       => true,
                ]);
                $message = $this->input->post('send_file_message');
                $message = nl2br($message);
                $success = $this->emails_model->send_simple_email($this->input->post('send_file_email'), $this->input->post('send_file_subject'), $message);
                if ($success) {
                    set_alert('success', _l('custom_file_success_send', $this->input->post('send_file_email')));
                } else {
                    set_alert('warning', _l('custom_file_fail_send'));
                }
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function update_ei_items_order($type)
    {
        $data = $this->input->post();
        foreach ($data['data'] as $order) {
            $this->db->where('id', $order[0]);
            $this->db->update(db_prefix() . 'itemable', [
                'item_order' => $order[1],
            ]);
        }
    }

    /* Since version 1.0.2 add client reminder */
    public function add_reminder($rel_id_id, $rel_type)
    {
        $message    = '';
        $alert_type = 'warning';
        if ($this->input->post()) {
            $success = $this->misc_model->add_reminder($this->input->post(), $rel_id_id);
            if ($success) {
                $alert_type = 'success';
                $message    = _l('reminder_added_successfully');
            }
        }
        echo json_encode([
            'alert_type' => $alert_type,
            'message'    => $message,
        ]);
    }

    public function get_reminders($id, $rel_type)
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('reminders', [
                'id'       => $id,
                'rel_type' => $rel_type,
            ]);
        }
    }

    public function my_reminders()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('staff_reminders');
        }
    }

    public function reminders()
    {
        $this->load->model('staff_model');
        $data['members']   = $this->staff_model->get('', ['active' => 1]);
        $data['title']     = _l('reminders');
        $data['bodyclass'] = 'all-reminders';
        $this->load->view('admin/utilities/all_reminders', $data);
    }

    public function reminders_table()
    {
        if ($this->input->is_ajax_request()) {
            $this->app->get_table_data('all_reminders');
        }
    }

    /* Since version 1.0.2 delete client reminder */
    public function delete_reminder($rel_id, $id, $rel_type)
    {
        if (!$id && !$rel_id) {
            die('No reminder found');
        }
        $success    = $this->misc_model->delete_reminder($id);
        $alert_type = 'warning';
        $message    = _l('reminder_failed_to_delete');
        if ($success) {
            $alert_type = 'success';
            $message    = _l('reminder_deleted');
        }
        echo json_encode([
            'alert_type' => $alert_type,
            'message'    => $message,
        ]);
    }

    public function get_reminder($id)
    {
        $reminder = $this->misc_model->get_reminders($id);
        if ($reminder) {
            if ($reminder->creator == get_staff_user_id() || is_admin()) {
                $reminder->date        = _dt($reminder->date);
                $reminder->description = clear_textarea_breaks($reminder->description);
                echo json_encode($reminder);
            }
        }
    }

    public function edit_reminder($id)
    {
        $reminder = $this->misc_model->get_reminders($id);
        if ($reminder && ($reminder->creator == get_staff_user_id() || is_admin()) && $reminder->isnotified == 0) {
            $success = $this->misc_model->edit_reminder($this->input->post(), $id);
            echo json_encode([
                    'alert_type' => 'success',
                    'message'    => ($success ? _l('updated_successfully', _l('reminder')) : ''),
                ]);
        }
    }

    public function run_cron_manually()
    {
        if (is_admin()) {
            $this->load->model('cron_model');
            $this->cron_model->run(true);
            redirect(admin_url('settings?group=cronjob'));
        }
    }

    /* Since Version 1.0.1 - General search */
    public function search()
    {
        $q = $this->input->post('q');
        $recentSearches = array_reverse(get_staff_recent_search_history());
        $recentSearches[] = $q;
        $recentSearches = update_staff_recent_search_history($recentSearches);
        $recentSearches = '';
        $data['result'] = $this->misc_model->perform_search($q);
        echo json_encode([
            'results' => $this->load->view('admin/search', $data, true),
            //'history' => $recentSearches,
        ]);
    }

    public function remove_recent_search($index)
    {
        $recentSearches = get_staff_recent_search_history();
        unset($recentSearches[$index]);
        update_staff_recent_search_history(array_reverse($recentSearches));
    }

    public function add_note($rel_id, $rel_type)
    {
        if ($this->input->post()) {
            $success = $this->misc_model->add_note($this->input->post(), $rel_type, $rel_id);
            if ($success) {
                set_alert('success', _l('added_successfully', _l('note')));
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function edit_note($id)
    {
        if ($this->input->post()) {
            $success = $this->misc_model->edit_note($this->input->post(), $id);
            echo json_encode([
                'success' => $success,
                'message' => _l('note_updated_successfully'),
            ]);
        }
    }

    public function delete_note($id)
    {
        $success = $this->misc_model->delete_note($id);

        if (!$this->input->is_ajax_request()) {
            if ($success) {
                set_alert('success', _l('deleted', _l('note')));
            }
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            echo json_encode(['success' => $success]);
        }
    }

    /* Remove customizer open from database */
    public function set_setup_menu_closed()
    {
        if ($this->input->is_ajax_request()) {
            $this->session->set_userdata([
                'setup-menu-open' => '',
            ]);
        }
    }

    /* Set session that user clicked on setup_menu menu link to stay open */
    public function set_setup_menu_open()
    {
        if ($this->input->is_ajax_request()) {
            $this->session->set_userdata([
                'setup-menu-open' => true,
            ]);
        }
    }

    /* User dismiss announcement */
    public function dismiss_announcement($id)
    {
        $this->misc_model->dismiss_announcement($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    /* Set notifications to read */
    public function set_notifications_read()
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode([
                'success' => $this->misc_model->set_notifications_read(),
            ]);
        }
    }

    public function set_notification_read_inline($id)
    {
        $this->misc_model->set_notification_read_inline($id);
    }

    public function set_desktop_notification_read($id)
    {
        $this->misc_model->set_desktop_notification_read($id);
    }

    public function mark_all_notifications_as_read_inline()
    {
        $this->misc_model->mark_all_notifications_as_read_inline();
    }

    public function notifications_check()
    {
        $notificationsIds = [];
        if (get_option('desktop_notifications') == '1') {
            $notifications = $this->misc_model->get_user_notifications();

            $notificationsPluck = array_filter($notifications, function ($n) {
                return $n['isread'] == 0;
            });

            $notificationsIds = array_pluck($notificationsPluck, 'id');
        }

        echo json_encode([
        'html'             => $this->load->view('admin/includes/notifications', [], true),
        'notificationsIds' => $notificationsIds,
        ]);
    }

    /* Check if staff email exists / ajax */
    public function staff_email_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $member_id = $this->input->post('memberid');
                if ($member_id != '') {
                    $this->db->where('staffid', $member_id);
                    $_current_email = $this->db->get(db_prefix() . 'staff')->row();
                    if ($_current_email->email == $this->input->post('email')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('email', $this->input->post('email'));
                $total_rows = $this->db->count_all_results(db_prefix() . 'staff');
                if ($total_rows > 0) {
                    echo json_encode('emailID already exit...');
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    
    /* Check if staff Mobile exists / ajax */
    public function staff_mobile_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $member_id = $this->input->post('memberid');
                if ($member_id != '') {
                    $this->db->where('staffid', $member_id);
                    $_current_mobile = $this->db->get(db_prefix() . 'staff')->row();
                    if ($_current_mobile->phonenumber == $this->input->post('phonenumber')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('phonenumber', $this->input->post('phonenumber'));
                $total_rows = $this->db->count_all_results(db_prefix() . 'staff');
                if ($total_rows > 0) {
                    echo json_encode('mobile number already exit...');
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    
    /* Check if staff AccountID exists / ajax */
    public function accountID_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $member_id = $this->input->post('memberid');
                $selected_company = $this->session->userdata('root_company');
                if ($member_id != '') {
                    $this->db->where('staffid', $member_id);
                    $_current_AccountID = $this->db->get(db_prefix() . 'staff')->row();
                    
                    $this->db->where('AccountID', $this->input->post('AccountID'));
                    $_current_AccountID2 = $this->db->get(db_prefix() . 'clients')->row();
                    if(!empty($_current_AccountID)){
                        if ($_current_AccountID->AccountID == $this->input->post('AccountID')) {
                        echo json_encode(true);
                        die();
                        }
                    }
                    if(!empty($_current_AccountID2)){
                        if ($_current_AccountID2->AccountID == $member_id) {
                        echo json_encode(true);
                        die();
                        }
                    }
                    
                    
                }
                
                $this->db->where('AccountID', $this->input->post('AccountID'));
                //$this->db->where('PlantID', $selected_company);
                $total_rows = $this->db->count_all_results(db_prefix() . 'staff');
                $this->db->where('AccountID', $this->input->post('AccountID'));
                //$this->db->where('PlantID', $selected_company);
                $total_rows2 = $this->db->count_all_results(db_prefix() . 'clients');
                if ($total_rows > 0 || $total_rows2 > 0 ) {
                    echo json_encode("AccountID already exit...");
                } else {
                    echo json_encode(true);
                }
           
                die();
            }
        }
    }
    
    /* Check Sale return Date / ajax */
    /* Not In Use */
    public function last_sale_return_date2()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $SaleRtnDate = to_sql_date($this->input->post('sale_return_date'));
                $oldSaleRtnDate = to_sql_date($this->input->post('oldSaleRtnDate'));
                $ExSaleRtnID = $this->input->post('ex_sale_return_id');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                
                $this->db->select('*');
                $this->db->where('PlantID', $selected_company);
                $this->db->where('FY', $fy);
                $this->db->from(db_prefix() . 'salesreturn');
                $this->db->order_by('SalesRtnID', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
                $result = $this->db->get()->row();
                if(empty($result)){
                    echo json_encode(true);
                }else{
                    if($ExSaleRtnID !== ''){
                    
                    $this->db->select('*');
                    $this->db->where('PlantID', $selected_company);
                    $this->db->where('FY', $fy);
                    $this->db->where('SalesRtnID <', $ExSaleRtnID);
                    $this->db->from(db_prefix() . 'salesreturn');
                    $this->db->order_by('SalesRtnID', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
                    $result2 = $this->db->get()->row();
                    $lastdate = substr($result2->Transdate,0,10);
                    }else{
                        $lastdate = substr($result->Transdate,0,10);
                    }
                    
                    $date_now = new DateTime($SaleRtnDate);
                    $date2    = new DateTime($lastdate);
                    
                    if ($date_now < $date2) {
                        echo json_encode("last SaleRtnDate is "._d($lastdate)." please select greater than or equal to date..");
                    }else{
                        echo json_encode(true);
                    }
                }
                die();
            }
        }
    }
    
    /* Check Sale Return Date / ajax */
    public function last_sale_return_date()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                $SRtn_date = to_sql_date($this->input->post('sale_return_date'));
                $oldSRtnDate = to_sql_date($this->input->post('oldSaleRtnDate'));
                $SRtnID = $this->input->post('ex_sale_return_id');
                
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                
                $SRtn_date_new    = new DateTime($SRtn_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                if($last_date_yr < $SRtn_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $SRtn_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblsalesreturn WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblsalesreturn.SalesRtnID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        $lastdate = substr($result_data->TransDate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "sale_return");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('SalesRtnID <', $SRtnID);
                        $this->db->from(db_prefix() . 'salesreturn');
                        $this->db->order_by('SalesRtnID', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_SRtn = $first_date;
                            }else{
                                $lastdate_SRtn = substr($result_data->Transdate,0,10);
                            }
                        }else{
                             $lastdate_SRtn = substr($result3->Transdate,0,10);
                        }
                        if($SRtnID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($SRtn_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this SRtn.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_SRtn);
                                if ($SRtn_date_new < $tillDate_new2) {
                                    echo json_encode($lastdate_SRtn);
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                           
                            if ($SRtn_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this SRtn.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_SRtn);
                                if ($SRtn_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to add this SRtn.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check journal Date / ajax */
    public function checkjournal_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $journal_date = to_sql_date($this->input->post('journal_date'));
                $VoucheriD = $this->input->post('VoucheriD');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $journal_date_new    = new DateTime($journal_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $journal_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $journal_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblaccountledger WHERE PlantID = '.$selected_company.' AND PassedFrom LIKE "JOURNAL" AND FY LIKE "'.$fy.'" ORDER BY abs(tblaccountledger.VoucherID) DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "accounting_journal_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        if($VoucheriD !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($journal_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to edit this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($journal_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to Add this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Payments Date / ajax */
    public function checkpayment_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $payment_date = to_sql_date($this->input->post('payment_date'));
                $VoucheriD = $this->input->post('VoucheriD');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $payment_date_new    = new DateTime($payment_date);
                
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $payment_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $payment_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                $lgstaff = $this->session->userdata('staff_user_id');
                
                /*$sql = 'SELECT * FROM tblaccountledger WHERE PlantID = '.$selected_company.' AND PassedFrom LIKE "PAYMENTS" AND FY LIKE "'.$fy.'" ORDER BY abs(tblaccountledger.VoucherID) DESC ';
                $result_data = $this->db->query($sql)->row();*/
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "accounting_payment_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        if($VoucheriD !== ''){
                            if($day == 0){
                                echo json_encode(true);
                            }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($payment_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to edit this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                    }else{
                        if($day == 0){
                                echo json_encode(true);
                            }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($payment_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to edit this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Receipts Date / ajax */
    public function checkreceipt_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $receipt_date = to_sql_date($this->input->post('receipt_date'));
                $VoucheriD = $this->input->post('VoucheriD');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $receipt_date_new    = new DateTime($receipt_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $receipt_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $receipt_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                $lgstaff = $this->session->userdata('staff_user_id');
                
               /* $sql = 'SELECT * FROM tblaccountledger WHERE PlantID = '.$selected_company.' AND PassedFrom LIKE "RECEIPTS" AND FY LIKE "'.$fy.'" ORDER BY abs(tblaccountledger.VoucherID) DESC ';
                $result_data = $this->db->query($sql)->row();*/
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "accounting_receipt_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        if($VoucheriD !== ''){
                            if($day == 0){
                                echo json_encode(true);
                            }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($receipt_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to edit this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                    }else{
                            if($day == 0){
                                echo json_encode(true);
                            }else{
                                $days = '- '.$day.' days';
                                $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                                $tillDate_new = new DateTime($tillDate);
                                if ($receipt_date_new < $tillDate_new) {
                                    echo json_encode('You are not allowed to edit this voucher.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Contra Date / ajax */
    public function checkcontra_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $contra_date = to_sql_date($this->input->post('contra_date'));
                $VoucheriD = $this->input->post('VoucheriD');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $contra_date_new    = new DateTime($contra_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                if($last_date_yr < $contra_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $contra_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblaccountledger WHERE PlantID = '.$selected_company.' AND PassedFrom LIKE "CONTRA" AND FY LIKE "'.$fy.'" ORDER BY abs(tblaccountledger.VoucherID) DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "accounting_contra_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        if($VoucheriD !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            $contra_date_new    = new DateTime($contra_date);
                            $first_date_yr = new DateTime($first_date);
                            
                            if ($contra_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this voucher.');
                                //echo json_encode("We have permition to update record from "._d($tillDate)." to "._d($lastdate)." ");
                            }else{
                                echo json_encode(true);
                            }
                        }
                    }else{
                       if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            $contra_date_new    = new DateTime($contra_date);
                            $first_date_yr = new DateTime($first_date);
                            
                            if ($contra_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to Add this voucher.');
                                //echo json_encode("We have permition to update record from "._d($tillDate)." to "._d($lastdate)." ");
                            }else{
                                echo json_encode(true);
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Purchase Date / ajax */
    public function checkpurch_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $order_date = to_sql_date($this->input->post('order_date'));
                $PurchID = $this->input->post('PurchID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                
                $order_date_new    = new DateTime($order_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($first_date_yr > $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblpurchasemaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblpurchasemaster.PurchID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "purchase-order");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('PurchID <', $PurchID);
                        $this->db->from(db_prefix() . 'purchasemaster');
                        $this->db->order_by('PurchID', 'DESC');
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_Purch = $first_date;
                            }else{
                                $lastdate_Purch = substr($result_data->Transdate,0,10);
                            }
                        }else{
                             $lastdate_Purch = substr($result3->Transdate,0,10);
                        }
                        
                        if($PurchID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this Purchase.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_Purch);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit this Purchase.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                           
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this Purchase.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_Purch);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to add this Purchase.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                        //echo json_encode(true);
                    }
                }
                die();
            }
        }
    }
    
    /* Check Damage Date / ajax */
    public function checkdamage_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $order_date = to_sql_date($this->input->post('order_date'));
                $DamageID = $this->input->post('DamageID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                
                $order_date_new    = new DateTime($order_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($first_date_yr > $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tbldamagemaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tbldamagemaster.DamageID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "damage_entry");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('DamageID <', $DamageID);
                        $this->db->from(db_prefix() . 'damagemaster');
                        $this->db->order_by('DamageID', 'DESC');
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_Purch = $first_date;
                            }else{
                                $lastdate_Purch = substr($result_data->Transdate,0,10);
                            }
                        }else{
                             $lastdate_Purch = substr($result3->Transdate,0,10);
                        }
                        
                        if($DamageID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this Damage entry.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_Purch);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit this Damage entry.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                           
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this Damage entry.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_Purch);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to add this Damage entry.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check PurchaseRtn Date / ajax */
    public function checkpurchRtn_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                
                $purch_rtn_date = to_sql_date($this->input->post('purch_rtn_date'));
                $old_purRtnDate = $this->input->post('old_purRtnDate');
                $PurRtnID = $this->input->post('PurRtnID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $purch_rtn_date_new    = new DateTime($purch_rtn_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                if($last_date_yr < $purch_rtn_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $purch_rtn_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblpurchasereturn WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblpurchasereturn.PurchRtnID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "purchase-return");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('PurchRtnID <', $PurRtnID);
                        $this->db->from(db_prefix() . 'purchasereturn');
                        $this->db->order_by('PurchRtnID', 'DESC');
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_purchRtn = $first_date;
                            }else{
                                $lastdate_purchRtn = substr($result_data->Transdate,0,10);
                            }
                            }else{
                                $lastdate_purchRtn = substr($result3->Transdate,0,10);
                            }
                        if($PurRtnID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($purch_rtn_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this PurchRtn.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_purchRtn);
                                if ($purch_rtn_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit PurchRtn.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($purch_rtn_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this PurchRtn.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_purchRtn);
                                if ($purch_rtn_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to add this PurchRtn.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Order Date / ajax */
    public function checkorder_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                
                $order_date = to_sql_date($this->input->post('order_date'));
                $OrderID = $this->input->post('OrderID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $order_date_new    = new DateTime($order_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $order_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblordermaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" AND  OrderStatus = "O" ORDER BY tblordermaster.OrderID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        $lastdate_order = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "orders");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        if($OrderID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this voucher.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_order);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to create order.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($order_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to create order.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_order);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to create order.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                        //echo json_encode(true);
                    }
                }
                die();
            }
        }
    }
    
    /* Check Challan Date / ajax */
    public function checkchallan_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                
                $date = to_sql_date($this->input->post('date'));
                $ChallanID = $this->input->post('ChallanID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $date_new    = new DateTime($date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblchallanmaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblchallanmaster.ChallanID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        $lastdate_challan = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "challan");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        if($ChallanID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this Challan.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_challan);
                                if ($order_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit this Challan.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to create this Challan.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_challan);
                                if ($date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to create this Challan.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                        //echo json_encode(true);
                    }
                }
                die();
            }
        }
    }
    
    /* Check Vehicle Return Date / ajax */
    public function checkvehicle_rtn_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $from_date = to_sql_date($this->input->post('from_date'));
                $vehRtnID = $this->input->post('vehRtnID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $from_date_new    = new DateTime($from_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                if($last_date_yr < $from_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $from_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblvehiclereturn WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblvehiclereturn.ReturnID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{  
                    
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "vehicle_return");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('ReturnID <', $vehRtnID);
                        $this->db->from(db_prefix() . 'vehiclereturn');
                        $this->db->order_by('ReturnID', 'DESC'); // 'created_at' is the column name of the date on which the record has stored in the database.
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_vehRtn = $first_date;
                            }else{
                                $lastdate_vehRtn = substr($result_data->Transdate,0,10);
                            }
                        }else{
                             $lastdate_vehRtn = substr($result3->Transdate,0,10);
                        }
                        
                        if($vehRtnID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                           
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($from_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this edit VehRtn.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_vehRtn);
                                if ($from_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit VehRtn.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($from_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to create this VehRtn.');
                            }else{
                                    $tillDate_new2 = new DateTime($lastdate_vehRtn);
                                    if ($from_date_new < $tillDate_new2) {
                                        echo json_encode('You are not allowed to create VehRtn.');
                                    }else{
                                        echo json_encode(true);
                                    }
                            }
                        }
                        //echo json_encode(true);
                    }
                }
                die();
            }
        }
    }
    
    /* Check CDNote Date / ajax */
    public function checkCDNote_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $credit_note_date = to_sql_date($this->input->post('credit_note_date'));
                $CDNoteID = $this->input->post('CDNoteID');
                $Type = $this->input->post('type_select');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $credit_note_date_new    = new DateTime($credit_note_date);
                $last_date_yr = new DateTime($lastdate_date);
                $first_date_yr = new DateTime($first_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $credit_note_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $credit_note_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                if($Type=="credit"){
                   $sql = 'SELECT * FROM tblcdnote WHERE plantid = '.$selected_company.' AND FY LIKE "'.$fy.'" AND BT = "C" ORDER BY tblcdnote.Billno DESC ';
                }else{
                    $sql = 'SELECT * FROM tblcdnote WHERE plantid = '.$selected_company.' AND FY LIKE "'.$fy.'" AND BT = "D" ORDER BY tblcdnote.Billno DESC ';
                }
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                       // $lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "cd_notes");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                            $this->db->select('*');
                            $this->db->where('plantid', $selected_company);
                            $this->db->where('FY', $fy);
                            $this->db->where('Billno <', $CDNoteID);
                            if($Type=="credit"){
                                $this->db->where('BT', "C");
                            }else{
                                $this->db->where('BT', "D");
                            }
                            $this->db->from(db_prefix() . 'cdnote');
                            $this->db->order_by('Billno', 'DESC');
                            $result3 = $this->db->get()->row();
                            if(empty($result3)){
                                if(empty($result_data)){
                                    $lastdate_CDNote = $first_date;
                                }else{
                                    $lastdate_CDNote = substr($result_data->Transdate,0,10);
                                }
                            }else{
                                 $lastdate_CDNote = substr($result3->Transdate,0,10);
                            }
                        if($CDNoteID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($credit_note_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this CDNote.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_CDNote);
                                if ($credit_note_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit CDNote.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($credit_note_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this CDNote.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_CDNote);
                                if ($credit_note_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to Add CDNote.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* CheckStock Adj Date / ajax */
    public function checkstock_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $Adj_date = to_sql_date($this->input->post('Adj_date'));
                $AdjID = $this->input->post('AdjID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                $Adj_date_new    = new DateTime($Adj_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                if($last_date_yr < $Adj_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $Adj_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblstockadjmaster WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblstockadjmaster.AdjID DESC ';
                $result_data = $this->db->query($sql)->row();
        
                if(is_admin()){
                    echo json_encode(true);
                }else{
                        //$lastdate = substr($result_data->Transdate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "stock_adjustment");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('AdjID <', $AdjID);
                        $this->db->from(db_prefix() . 'stockadjmaster');
                        $this->db->order_by('AdjID', 'DESC');
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            if(empty($result_data)){
                                $lastdate_AdjDate = $first_date;
                            }else{
                                $lastdate_AdjDate = substr($result_data->Transdate,0,10);
                            }
                        }else{
                             $lastdate_AdjDate = substr($result3->Transdate,0,10);
                        }
                        
                        if($AdjID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($Adj_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to edit this StockAdj.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_AdjDate);
                                if ($Adj_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to edit StockAdj.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            
                            if ($Adj_date_new < $tillDate_new) {
                                echo json_encode('You are not allowed to add this StockAdj.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_AdjDate);
                                if ($Adj_date_new < $tillDate_new2) {
                                    echo json_encode('You are not allowed to add StockAdj.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Production Date / ajax */
    public function checkprd_val()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                $Prd_date = to_sql_date($this->input->post('Prd_date'));
                $PRDID = $this->input->post('PRDID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                
                $Prd_date_new    = new DateTime($Prd_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                
                if($last_date_yr < $Prd_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($first_date_yr > $Prd_date_new){
                    echo json_encode('please select data in between finacial year');
                    die;
                }
                if($curr_date_new > $last_date_yr){
                    $lastdate = $lastdate_date;
                }else{
                    $lastdate = date('Y-m-d');
                }
                
                $lgstaff = $this->session->userdata('staff_user_id');
                
                $sql = 'SELECT * FROM tblproduction WHERE PlantID = '.$selected_company.' AND FY LIKE "'.$fy.'" ORDER BY tblproduction.pro_order_id DESC ';
                $result_data = $this->db->query($sql)->row();
                
                //$lastdate = substr($result_data->TransDate,0,10);
                        $this->db->select('*');
                        $this->db->where('plant_id', $selected_company);
                        $this->db->where('year', $fy);
                        $this->db->where('staff_id', $lgstaff);
                        $this->db->LIKE('feature', "production");
                        $this->db->LIKE('capability', "view");
                        $this->db->from(db_prefix() . 'staff_permissions');
                        $result2 = $this->db->get()->row();
                        $day = $result2->days;
                        
                        $this->db->select('*');
                        $this->db->where('PlantID', $selected_company);
                        $this->db->where('FY', $fy);
                        $this->db->where('pro_order_id <', $PRDID);
                        $this->db->from(db_prefix() . 'production');
                        $this->db->order_by('pro_order_id', 'DESC');
                        $result3 = $this->db->get()->row();
                        if(empty($result3)){
                            $lastdate_PRD = $first_date;
                        }else{
                            $lastdate_PRD = substr($result3->TransDate,0,10);
                        }
                if(is_admin()){
                    $tillDate_new2 = new DateTime($lastdate_PRD);
                    if ($Prd_date_new < $tillDate_new2) {
                        echo json_encode(true);
                        //echo json_encode('You are not allowed to edit this Production.');
                        //echo json_encode(true);
                    }else{
                        echo json_encode(true);
                    }
                }else{
                        
                        if($PRDID !== ''){
                        
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                            if ($Prd_date_new < $tillDate_new) {
                                echo json_encode(true);
                                //echo json_encode('You are not allowed to edit this Production.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_PRD);
                                if ($Prd_date_new < $tillDate_new2) {
                                    //echo json_encode('You are not allowed to edit this Production.');
                                    echo json_encode(true);
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }else{
                        if($day == 0){
                            echo json_encode(true);
                        }else{
                            
                            $days = '- '.$day.' days';
                            $tillDate = date('Y-m-d', strtotime($lastdate. $days));
                            $tillDate_new = new DateTime($tillDate);
                           
                            if ($Prd_date_new < $tillDate_new) {
                                echo json_encode(true);
                                //echo json_encode('You are not allowed to add this Production.');
                            }else{
                                $tillDate_new2 = new DateTime($lastdate_PRD);
                                if ($Prd_date_new < $tillDate_new2) {
                                    echo json_encode(true);
                                    //echo json_encode('You are not allowed to add this Production.');
                                }else{
                                    echo json_encode(true);
                                }
                            }
                        }
                    }
                }
                die();
            }
        }
    }
    
    /* Check Production Date / ajax */
    public function CheckPRDDate()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                
                $Prd_date = to_sql_date($this->input->post('Prd_date'));
                $PRDID = $this->input->post('PRDID');
                $selected_company = $this->session->userdata('root_company');
                $fy = $this->session->userdata('finacial_year');
                $PlantID = $this->input->post('PlantID');
                $fy_new  = $fy + 1;
                $first_date = '20'.$fy.'-04-01';
                $lastdate_date = '20'.$fy_new.'-03-31';
                $curr_date = date('Y-m-d');
                
                $Prd_date_new    = new DateTime($Prd_date);
                $first_date_yr = new DateTime($first_date);
                $last_date_yr = new DateTime($lastdate_date);
                $curr_date_new = new DateTime($curr_date);
                    
                    
                if($PlantID == '3'){
                    
                    // Check Last three days old oreder
                    $prev_date = date('Y-m-d', strtotime($date .' -3 day'));
                    /*echo json_encode($prev_date);
                        die;*/
                    $PRDStatus = array("In-Progress","pending");
                    $this->db->select('*');
                    $this->db->where(db_prefix() . 'production.PlantID', $selected_company);
                    $this->db->where(db_prefix() . 'production.FY', $fy);
                    $this->db->where(db_prefix() . 'production.TransDate <', $prev_date);
                    $this->db->where_in(db_prefix() . 'production.production_status', $PRDStatus);
                    $this->db->from(db_prefix() . 'production');
                    $this->db->order_by(db_prefix() . 'production.pro_order_id', 'DESC');
                    $PRDOrder = $this->db->get()->row();
                    /*echo json_encode($PRDOrder);
                        die; */       
                    
                    
                    
                    if($last_date_yr < $Prd_date_new){
                        echo json_encode('please select data in between finacial year');
                        die;
                    }
                    if($first_date_yr > $Prd_date_new){
                        echo json_encode('please select data in between finacial year');
                        die;
                    }
                    $lgstaff = $this->session->userdata('staff_user_id');
                            $this->db->select('*');
                            $this->db->where('plant_id', $selected_company);
                            $this->db->where('year', $fy);
                            $this->db->where('staff_id', $lgstaff);
                            $this->db->LIKE('feature', "production");
                            $this->db->LIKE('capability', "view");
                            $this->db->from(db_prefix() . 'staff_permissions');
                            $result2 = $this->db->get()->row();
                            $day = $result2->days;
                        
                        if(is_admin()){
                            if($curr_date_new <= $Prd_date_new){
                                if(empty($PRDOrder)){
                                    echo json_encode(true);
                                }else{
                                    echo json_encode("Please complete previous pending / in-progress orders before creating new PRD order.");
                                }
                                
                            }else{
                                echo json_encode("You can't create back dated PRD order.");
                            }
                        }else{
                            if($curr_date_new <= $Prd_date_new){
                                if(empty($PRDOrder)){
                                    echo json_encode(true);
                                }else{
                                    echo json_encode("Please complete previous pending / in-progress orders before creating new PRD order.");
                                }
                            }else{
                                echo json_encode("You can't create back dated PRD order.");
                            }
                        }   
                    die();
                }else if($PlantID == '1'){
                    
                    // Check Last production oreder
                    
                    $PRDStatus = array("In-Progress","pending");
                    $this->db->select('*');
                    $this->db->where(db_prefix() . 'production.PlantID', $selected_company);
                    $this->db->where(db_prefix() . 'production.FY', $fy);
                    //$this->db->where_in(db_prefix() . 'production.production_status', $PRDStatus);
                    $this->db->from(db_prefix() . 'production');
                    $this->db->order_by(db_prefix() . 'production.TransDate', 'DESC');
                    $PRDOrder = $this->db->get()->row();
                    
                    $LastPRDORDDate = substr($PRDOrder->TransDate,0,10);
                    $OneDayBefore = date('Y-m-d', strtotime($LastPRDORDDate .' -1 day'));
                    $LastPRDDateNew    = new DateTime($OneDayBefore);
                    
                    if($last_date_yr < $Prd_date_new){
                        echo json_encode('please select data in between finacial year');
                        die;
                    }
                    if($first_date_yr > $Prd_date_new){
                        echo json_encode('please select data in between finacial year');
                        die;
                    }
                    if(!empty($PRDOrder)){
                        if($LastPRDDateNew >= $Prd_date_new){
                            echo json_encode('please select grater than last PRD order date');
                            die;
                        }else{
                            echo json_encode(true);
                        }
                    }else{
                        echo json_encode(true);
                    }
                    
                    die;
                }
                
            }
        }
    }
    /* Check if staff ItemID exists / ajax */
    public function ItemID_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $itemid = $this->input->post('itemid');
                if ($itemid = '') {
                    echo json_encode(true);
                    die();
                }else{
                    $this->db->where('item_code', $this->input->post('ItemID'));
                    $total_rows = $this->db->count_all_results(db_prefix() . 'items');
                    if ($total_rows > 0 ) {
                        echo json_encode("ItemID already exit...");
                    } else {
                        echo json_encode(true);
                    }
                    die();
                }
                
                
            }
        }
    }
    
   
    
    /* Check if Account Mobile exists / ajax */
    public function accountmobile_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                
                $userid = $this->input->post('userid');
                if ($userid != '') {
                    $this->db->LIKE('AccountID', $userid);
                    $_current_mobile = $this->db->get(db_prefix() . 'contacts')->row();
                    
                    if ($_current_mobile->phonenumber == $this->input->post('mobile_no')) {
                        echo json_encode(true);
                        die();
                    }
                }
                
                $this->db->where('phonenumber', $this->input->post('mobile_no'));
                $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');
                if ($total_rows > 0) {
                    echo json_encode("mobile number already exit..");
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }
    
    /* Check if staff Username exists / ajax */
    public function staff_username_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $member_id = $this->input->post('memberid');
                if ($member_id != '') {
                    $this->db->where('staffid', $member_id);
                    $_current_mobile = $this->db->get(db_prefix() . 'staff')->row();
                    if ($_current_mobile->username == $this->input->post('username')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('username', $this->input->post('username'));
                $total_rows = $this->db->count_all_results(db_prefix() . 'staff');
                if ($total_rows > 0) {
                    echo json_encode('username already exit...');
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }

    /* Check if client email exists/  ajax */
    public function contact_email_exists()
    {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post()) {
                // First we need to check if the email is the same
                $userid = $this->input->post('userid');
                if ($userid != '') {
                    $this->db->where('id', $userid);
                    $_current_email = $this->db->get(db_prefix() . 'contacts')->row();
                    if ($_current_email->email == $this->input->post('email')) {
                        echo json_encode(true);
                        die();
                    }
                }
                $this->db->where('email', $this->input->post('email'));
                $total_rows = $this->db->count_all_results(db_prefix() . 'contacts');
                if ($total_rows > 0) {
                    echo json_encode('emailID already exit...');
                } else {
                    echo json_encode(true);
                }
                die();
            }
        }
    }

    /* Goes blank page but with messagae access danied / message set from session flashdata */
    public function access_denied()
    {
        $this->load->view('admin/blank_page');
    }

    /* Goes to blank page with message page not found / message set from session flashdata */
    public function not_found()
    {
        $this->load->view('admin/blank_page');
    }

    public function change_maximum_number_of_digits_to_decimal_fields($digits)
    {
        if (is_admin()) {
            hooks()->do_action('before_change_maximum_number_of_digits_to_decimal_fields');
            $tables = $this->db->query("SELECT *
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='" . APP_DB_NAME . "'")->result_array();
            foreach ($tables as $table_data) {
                $table  = $table_data['TABLE_NAME'];
                $fields = $this->db->list_fields($table);

                foreach ($fields as $field) {
                    $field_info = $this->db->query('SHOW FIELDS
                        FROM ' . $table . " where Field ='" . $field . "'")->result_array();
                    $field_type = strtolower($field_info[0]['Type']);
                    if (strpos($field_type, 'decimal') !== false) {
                        $field_null = strtoupper($field_info[0]['Null']);
                        if ($field_null == 'YES') {
                            $field_is_null = 'NULL';
                        } else {
                            $field_is_null = 'NOT NULL';
                        }
                        $total_decimals = strafter($field_info[0]['Type'], ',');
                        $total_decimals = strbefore($total_decimals, ')');

                        if ($field_info[0]['Default'] == null) {
                            $field_default_value = '';
                        } else {
                            $field_default_value = ' DEFAULT 0.' . str_repeat(0, $total_decimals);
                        }

                        $this->db->query("ALTER TABLE $table CHANGE $field $field DECIMAL($digits,$total_decimals) $field_is_null$field_default_value;");
                    }
                }
            }
        } else {
            echo 'You need to be logged in as administrator to perform this action.';
        }
    }

    public function change_decimal_places($total_decimals)
    {
        $notChangableFields = ['estimated_hours'];

        if (is_admin()) {
            hooks()->do_action('before_change_decimal_places');

            $tables = $this->db->query("SELECT *
                FROM INFORMATION_SCHEMA.TABLES
                WHERE TABLE_TYPE = 'BASE TABLE' AND TABLE_SCHEMA='" . APP_DB_NAME . "'")->result_array();

            foreach ($tables as $table_data) {
                $table  = $table_data['TABLE_NAME'];
                $fields = $this->db->list_fields($table);

                foreach ($fields as $field) {
                    if (!in_array($field, $notChangableFields)) {
                        $field_info = $this->db->query('SHOW FIELDS
                            FROM ' . $table . " where Field ='" . $field . "'")->result_array();
                        $field_type = strtolower($field_info[0]['Type']);
                        if (strpos($field_type, 'decimal') !== false) {
                            $field_null = strtoupper($field_info[0]['Null']);
                            if ($field_null == 'YES') {
                                $field_is_null = 'NULL';
                            } else {
                                $field_is_null = 'NOT NULL';
                            }
                            if ($field_info[0]['Default'] == null) {
                                $field_default_value = '';
                            } else {
                                $field_default_value = ' DEFAULT 0.' . str_repeat(0, $total_decimals);
                            }
                            $this->db->query("ALTER TABLE $table CHANGE $field $field DECIMAL(15,$total_decimals) $field_is_null$field_default_value;");
                        }
                    }
                }
            }
            echo '<p><strong>Table columns with decimal places updated successfully.</strong></p>';
        } else {
            echo 'You need to be logged in as administrator to perform this action.';
        }
    }

    public function convert_tables_to_innodb_engine()
    {
        if (is_admin()) {
            $databaseName = APP_DB_NAME;
            $tables       = $this->db->query("SELECT TABLE_NAME,
                             ENGINE
                            FROM information_schema.TABLES
                            WHERE TABLE_SCHEMA = '$databaseName' and ENGINE = 'myISAM'")->result_array();

            foreach ($tables as $table) {
                $tableName = $table['TABLE_NAME'];
                $this->db->query("ALTER TABLE $tableName ENGINE=InnoDB;");
            }
            echo 'Table engines successfully changed to InnoDB';
        } else {
            echo 'You need to be logged in as administrator to perform this action.';
        }
    }

    /**
     * The upgrade script for 232 does not perform the queries below for backward compatibility
     * Mostly it changes the varchar maximum length because of InnoDB index
     */
    public function upgrade_232_database()
    {
        $charset = $this->db->char_set;
        $collat  = $this->db->dbcollat;

        if (!is_admin()) {
            die('You must be logged in as administrator to perform this action');
        }

        if (get_option('_232_upgrade_db_queries_performed') === '1') {
            die('This action is already processed');
        }

        $this->db->query('ALTER TABLE `' . db_prefix() . 'contacts` CHANGE `lastname` `lastname` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'contacts` CHANGE `firstname` `firstname` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'clients` CHANGE `company` `company` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'customers_groups` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'options` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'invoicepaymentrecords` CHANGE `paymentmethod` `paymentmethod` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'leads` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'leads` CHANGE `company` `company` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'projects` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');

        $this->db->query('ALTER TABLE `' . db_prefix() . 'contacts` CHANGE `title` `title` VARCHAR(100) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');

        $this->db->query('ALTER TABLE `' . db_prefix() . 'web_to_lead` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'vault` CHANGE `username` `username` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'vault` CHANGE `server_address` `server_address` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tracked_mails` CHANGE `subject` `subject` MEDIUMTEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets_predefined_replies` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets_pipe_log` CHANGE `email_to` `email_to` VARCHAR(100) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets_pipe_log` CHANGE `email` `email` VARCHAR(100) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets_pipe_log` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'subscriptions` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'staff` CHANGE `media_path_slug` `media_path_slug` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'proposals` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'proposals` CHANGE `proposal_to` `proposal_to` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'projectdiscussions` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'projectdiscussioncomments` CHANGE `fullname` `fullname` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'project_files` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'project_activity` CHANGE `description_key` `description_key` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . " NOT NULL COMMENT 'Language file key';");
        $this->db->query('ALTER TABLE `' . db_prefix() . 'notifications` CHANGE `additional_data` `additional_data` TEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'lead_activity_log` CHANGE `additional_data` `additional_data` TEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'knowledge_base_groups` CHANGE `group_slug` `group_slug` TEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'knowledge_base_groups` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');

        $this->db->query('ALTER TABLE `' . db_prefix() . 'files` CHANGE `file_name` `file_name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'expenses_categories` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'expenses` CHANGE `expense_name` `expense_name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'contracts` CHANGE `subject` `subject` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'contacts` CHANGE `profile_image` `profile_image` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'staff` CHANGE `profile_image` `profile_image` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');

        $this->db->query('ALTER TABLE `' . db_prefix() . 'clients` CHANGE `longitude` `longitude` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'clients` CHANGE `latitude` `latitude` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'announcements` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'projectdiscussioncomments` CHANGE `file_name` `file_name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'gdpr_requests` CHANGE `request_type` `request_type` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'user_meta` CHANGE `meta_key` `meta_key` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'tickets_pipe_log` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'mail_queue` CHANGE `email` `email` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'mail_queue` CHANGE `cc` `cc` TEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'mail_queue` CHANGE `bcc` `bcc` TEXT CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'project_files` CHANGE `file_name` `file_name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'ticket_attachments` CHANGE `file_name` `file_name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'milestones` CHANGE `name` `name` VARCHAR(191) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NOT NULL;');
        $this->db->query('ALTER TABLE `' . db_prefix() . 'leads` CHANGE `email` `email` VARCHAR(100) CHARACTER SET ' . $charset . ' COLLATE ' . $collat . ' NULL DEFAULT NULL;');
        add_option('_232_upgrade_db_queries_performed', '1', 0);
    }
}
