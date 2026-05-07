<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Emails extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('emails_model');
    }

    /* List all email templates */
    public function index()
    {
        if (!has_permission('email_templates', '', 'view')) {
            access_denied('email_templates');
        }
        $langCheckings = get_option('email_templates_language_checks');
        if ($langCheckings == '') {
            $langCheckings = [];
        } else {
            $langCheckings = unserialize($langCheckings);
        }


        $this->db->where('language', 'english');
        $email_templates_english = $this->db->get(db_prefix() . 'emailtemplates')->result_array();
        foreach ($this->app->get_available_languages() as $avLanguage) {
            if ($avLanguage != 'english') {
                foreach ($email_templates_english as $template) {

                    // Result is cached and stored in database
                    // This page may perform 1000 queries per request
                    if (isset($langCheckings[$template['slug'] . '-' . $avLanguage])) {
                        continue;
                    }

                    $notExists = total_rows(db_prefix() . 'emailtemplates', [
                        'slug'     => $template['slug'],
                        'language' => $avLanguage,
                    ]) == 0;

                    $langCheckings[$template['slug'] . '-' . $avLanguage] = 1;

                    if ($notExists) {
                        $data              = [];
                        $data['slug']      = $template['slug'];
                        $data['type']      = $template['type'];
                        $data['language']  = $avLanguage;
                        $data['name']      = $template['name'] . ' [' . $avLanguage . ']';
                        $data['subject']   = $template['subject'];
                        $data['message']   = '';
                        $data['fromname']  = $template['fromname'];
                        $data['plaintext'] = $template['plaintext'];
                        $data['active']    = $template['active'];
                        $data['order']     = $template['order'];
                        $this->db->insert(db_prefix() . 'emailtemplates', $data);
                    }
                }
            }
        }

        update_option('email_templates_language_checks', serialize($langCheckings));

        $data['staff'] = $this->emails_model->get([
            'type'     => 'staff',
            'language' => 'english',
        ]);

        $data['credit_notes'] = $this->emails_model->get([
            'type'     => 'credit_note',
            'language' => 'english',
        ]);

        $data['tasks'] = $this->emails_model->get([
            'type'     => 'tasks',
            'language' => 'english',
        ]);
        $data['client'] = $this->emails_model->get([
            'type'     => 'client',
            'language' => 'english',
        ]);
        $data['tickets'] = $this->emails_model->get([
            'type'     => 'ticket',
            'language' => 'english',
        ]);
        $data['invoice'] = $this->emails_model->get([
            'type'     => 'invoice',
            'language' => 'english',
        ]);
        $data['estimate'] = $this->emails_model->get([
            'type'     => 'estimate',
            'language' => 'english',
        ]);
        $data['contracts'] = $this->emails_model->get([
            'type'     => 'contract',
            'language' => 'english',
        ]);
        $data['proposals'] = $this->emails_model->get([
            'type'     => 'proposals',
            'language' => 'english',
        ]);
        $data['projects'] = $this->emails_model->get([
            'type'     => 'project',
            'language' => 'english',
        ]);
        $data['leads'] = $this->emails_model->get([
            'type'     => 'leads',
            'language' => 'english',
        ]);

        $data['gdpr'] = $this->emails_model->get([
            'type'     => 'gdpr',
            'language' => 'english',
        ]);

        $data['subscriptions'] = $this->emails_model->get([
            'type'     => 'subscriptions',
            'language' => 'english',
        ]);

        $data['title'] = _l('email_templates');

        $data['hasPermissionEdit'] = has_permission('email_templates', '', 'edit');

        $this->load->view('admin/emails/email_templates', $data);
    }

    /* Edit email template */
    public function email_template($id)
    {
        if (!has_permission('email_templates', '', 'view')) {
            access_denied('email_templates');
        }
        if (!$id) {
            redirect(admin_url('emails'));
        }

        if ($this->input->post()) {
            if (!has_permission('email_templates', '', 'edit')) {
                access_denied('email_templates');
            }

            $data = $this->input->post();
            $tmp  = $this->input->post(null, false);

            foreach ($data['message'] as $key => $contents) {
                $data['message'][$key] = $tmp['message'][$key];
            }

            foreach ($data['subject'] as $key => $contents) {
                $data['subject'][$key] = $tmp['subject'][$key];
            }

            $data['fromname'] = $tmp['fromname'];

            $success = $this->emails_model->update($data, $id);

            if ($success) {
                set_alert('success', _l('updated_successfully', _l('email_template')));
            }

            redirect(admin_url('emails/email_template/' . $id));
        }

        // English is not included here
        $data['available_languages'] = $this->app->get_available_languages();

        if (($key = array_search('english', $data['available_languages'])) !== false) {
            unset($data['available_languages'][$key]);
        }

        $data['available_merge_fields'] = $this->app_merge_fields->all();

        $data['template'] = $this->emails_model->get_email_template_by_id($id);
        $title            = $data['template']->name;
        $data['title']    = $title;
        $this->load->view('admin/emails/template', $data);
    }

    public function enable_by_type($type)
    {
        if (has_permission('email_templates', '', 'edit')) {
            $this->emails_model->mark_as_by_type($type, 1);
        }
        redirect(admin_url('emails'));
    }

    public function disable_by_type($type)
    {
        if (has_permission('email_templates', '', 'edit')) {
            $this->emails_model->mark_as_by_type($type, 0);
        }
        redirect(admin_url('emails'));
    }

    public function enable($id)
    {
        if (has_permission('email_templates', '', 'edit')) {
            $template = $this->emails_model->get_email_template_by_id($id);
            $this->emails_model->mark_as($template->slug, 1);
        }
        redirect(admin_url('emails'));
    }

    public function disable($id)
    {
        if (has_permission('email_templates', '', 'edit')) {
            $template = $this->emails_model->get_email_template_by_id($id);
            $this->emails_model->mark_as($template->slug, 0);
        }

        redirect(admin_url('emails'));
    }

    /* Since version 1.0.1 - test your smtp settings */
    public function sent_smtp_test_email()
    {
        if ($this->input->post()) {
            $this->load->config('email');
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = get_option('email_header') . 'This is test SMTP email. <br />If you received this message that means that your SMTP settings is set correctly.' . get_option('email_footer');
            $template->fromname = get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = 'SMTP Setup Testing';

            $template = parse_email_template($template);

            hooks()->do_action('before_send_test_smtp_email');
            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {

                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';

                    return $err;
                });

                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($this->input->post('test_email'));

            $systemBCC = get_option('bcc_emails');

            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }

            $this->email->subject($template->subject);
            $this->email->message($template->message);

            if ($this->email->send(true)) {
                set_alert('success', 'Seems like your SMTP settings is set correctly. Check your email now.');
                hooks()->do_action('smtp_test_email_success');
            } else {

                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));

                hooks()->do_action('smtp_test_email_failed');
            }
        }
    }
    
    /* custom code start */
public function send_mail_for_lead()
    {
        $from = $_POST['email_from'];
        $to = $_POST['email_to'];
        $sub = $_POST['Subject'];
        $bcc = $_POST['bcc'];
        //$Message = $_POST['message'];
        $lead_id = $_POST['hidden_feild']; 
        $cc = $_POST['cc'];
        $secondery_user = $_POST['hiddencc'];
        $secondery_user_array = explode(',', $cc);
        $attachments = $this->emails_model->handle_lead_email_attachments($lead_id);
        if ($this->input->post()) {
            $this->load->config('email');
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = get_option('email_header') . $_POST['message'] . get_option('email_footer');
            $template->fromname = 'Global Infocloud Pvt. Ltd. | CRM';//get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = $sub;
            $template = parse_email_template($template);
            //print_r($template);

            do_action('before_send_test_smtp_email');
            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';
                    return $err;
                });
                $this->email->set_smtp_debug(3);
            }
            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));
            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($to);
            $systemCC='';
            $systemBCC='';
            $systemBCC = $_POST['bcc'];
            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }
            
            $cc_email = array();
            //$lenght = count($secondery_user_array)-1;
            if($cc != '') {
                        for ($i=0; $i<count($secondery_user_array); $i++) {
                                //$this->email->cc($secondery_user_array[$i]);
                            if($secondery_user_array[$i] != ''){
                                array_push($cc_email, $secondery_user_array[$i]);
                            }
                        }
                        $this->email->cc($cc_email);
                }
            $this->email->subject($template->subject);
            $this->email->message($template->message);
            //print_r($template);die;
            //echo $this->email->send(true); die;
            foreach($attachments as $file_path)
    {
        $this->email->attach('uploads/leads/'.$lead_id.'/'.$file_path['file_name']);
        //echo site_url('uploads/projects/'.$project_id.'/'.$file_path['file_name']);
    }
//$this->clear_attachments();
            if ($this->email->send(true)) {
                set_alert('success', 'Email sent successfully.');
                do_action('smtp_test_email_success');
               redirect(admin_url('leads'));
            } else {
               // set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));
                do_action('smtp_test_email_failed');
                redirect(admin_url('leads'));
            }
        }
    }
    
    /* Send Company Profile Vie Email */
    public function company_profile_mail()
    {
        $from = $_POST['email_from'];
        $to = $_POST['email_to'];
        $sub = $_POST['Subject'];
        $bcc = $_POST['bcc'];
        $Message = $_POST['message'];
        $mobno = $_POST['mobno']; 
        $cc = $_POST['cc'];
        $lead_id = $_POST['lead_id'];
        $secondery_user_array = explode(',', $cc);
        
        if ($this->input->post()) {
            $this->load->config('email');
            $email = '';
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = get_option('email_header') . $_POST['message'];// . get_option('email_footer');
            $template->fromname = 'Earthen Connect INC.';//get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = $sub;

            $template = parse_email_template($template);
            //print_r($template);

            do_action('before_send_test_smtp_email');
            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';
                    return $err;
                });
                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($to);

            $systemCC='';
            $systemCC= $_POST['cc'];
          
            $systemBCC='';
            $systemBCC = $_POST['bcc'];

            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }

            $cc='';
            $cc = $_POST['cc'];
            $cc_email = array();
            //$lenght = count($secondery_user_array)-1;
            if($cc != '') {
                for ($i=0; $i<count($secondery_user_array); $i++) { 
                    if($secondery_user_array[$i] != ''){
                        array_push($cc_email, $secondery_user_array[$i]);
                    }
                }
                $this->email->cc($cc_email);
            } 
            $this->email->subject($template->subject);
            $this->email->message($template->message);
   
            $this->email->attach('uploads/EC_Profile_JAN_24.pdf');
   
            if ($this->email->send(true)) {
                $this->load->model('leads_model');
                $this->leads_model->log_lead_activity($lead_id, 'Company Profile Sent');
                set_alert('success', 'Email sent successfully.');
                /*
                $compurl="<a href='https://globalinfocloud.com/'>www.globalinfocloud.com</a>";
                $msg= "Greetings from GIC !
                
                Thank you for your enquiry. We have sent our company profile to your registered email-id for your perusal. Kindly go through the email. We will connect you shortly.
                
                Best,
                Business Development Team
                Global Infocloud Pvt. Ltd.
                
                www.globalinfocloud.com";
                $msg1 = urlencode($msg);*/
            /* Send SMS To client */
                /*$url = "http://bhashsms.com/api/sendmsg.php?user=infocloud&pass=info99dtjE&sender=GCLOUD&phone=$mobno&text=$msg1&priority=ndnd&stype=normal";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);*/

                //$this->leads_model->log_lead_activity($lead_id, 'Company Profile Send','',serialize([$to]));

                do_action('smtp_test_email_success');
                redirect(admin_url('leads'));
            } else {
                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));
                do_action('smtp_test_email_failed');
                redirect(admin_url('leads'));
            }
        }
    }
    
    /* Send Company Profile Vie Email */
    public function SendProductBrochure()
    {
        $from = $_POST['email_from'];
        $to = $_POST['email_to'];
        $sub = $_POST['Subject'];
        $bcc = $_POST['bcc'];
        $Message = $_POST['message'];
        $mobno = $_POST['mobno']; 
        $cc = $_POST['cc'];
        $lead_id = $_POST['lead_id'];
        $ind_for = $_POST['ind_for'];
        $secondery_user_array = explode(',', $cc);
        
        if ($this->input->post()) {
            $this->load->config('email');
            $email = '';
            // Simulate fake template to be parsed
            $template           = new StdClass();
            $template->message  = get_option('email_header') . $_POST['message'];// . get_option('email_footer');
            $template->fromname = 'Global Infocloud Pvt. Ltd.';//get_option('companyname') != '' ? get_option('companyname') : 'TEST';
            $template->subject  = $sub;

            $template = parse_email_template($template);
            //print_r($template);

            do_action('before_send_test_smtp_email');
            $this->email->initialize();
            if (get_option('mail_engine') == 'phpmailer') {
                $this->email->set_debug_output(function ($err) {
                    if (!isset($GLOBALS['debug'])) {
                        $GLOBALS['debug'] = '';
                    }
                    $GLOBALS['debug'] .= $err . '<br />';
                    return $err;
                });
                $this->email->set_smtp_debug(3);
            }

            $this->email->set_newline(config_item('newline'));
            $this->email->set_crlf(config_item('crlf'));

            $this->email->from(get_option('smtp_email'), $template->fromname);
            $this->email->to($to);

            $systemCC='';
            $systemCC= $_POST['cc'];
          
            $systemBCC='';
            $systemBCC = $_POST['bcc'];

            if ($systemBCC != '') {
                $this->email->bcc($systemBCC);
            }

            $cc='';
            $cc = $_POST['cc'];
            $cc_email = array();
            //$lenght = count($secondery_user_array)-1;
            if($cc != '') {
                for ($i=0; $i<count($secondery_user_array); $i++) { 
                    if($secondery_user_array[$i] != ''){
                        array_push($cc_email, $secondery_user_array[$i]);
                    }
                }
                $this->email->cc($cc_email);
            } 
            $this->email->subject($template->subject);
            $this->email->message($template->message);
            
            /* Attachement BY Industry type */
            $Brochure_url = '';
            if($ind_for == "Hospitals  ERP"){
                $this->email->attach('uploads/product_brochures/GIC_HMS.pdf');
            }else if($ind_for == "School / Institute"){
                $this->email->attach('uploads/product_brochures/GIC-School-Managment-Solution.pdf');
            }else if($ind_for == "Manufacturing"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else if($ind_for == "Dal Mills"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else if($ind_for == "Roller Mills"){
                $this->email->attach('uploads/product_brochures/ERP_for_Rolling_Flour_Mills.pdf');
            }else if($ind_for == "Warehousing"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else if($ind_for == "Recording Studio"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else if($ind_for == "Online Shopping"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else if($ind_for == "Agricultural Products"){
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }else{
                $this->email->attach('uploads/GIC-Company-Profile_6_23.pdf');
            }
   
            if ($this->email->send(true)) {
                $this->load->model('leads_model');
                $this->leads_model->log_lead_activity($lead_id, 'Product Brochure Sent');
                set_alert('success', 'Product Brochure sent successfully.');
                
                $compurl="<a href='https://globalinfocloud.com/'>www.globalinfocloud.com</a>";
                $msg= "Greetings from GIC !
                
                Thank you for your enquiry. We have sent our company profile to your registered email-id for your perusal. Kindly go through the email. We will connect you shortly.
                
                Best,
                Business Development Team
                Global Infocloud Pvt. Ltd.
                
                www.globalinfocloud.com";
                $msg1 = urlencode($msg);
            /* Send SMS To client */
                $url = "http://bhashsms.com/api/sendmsg.php?user=infocloud&pass=info99dtjE&sender=GCLOUD&phone=$mobno&text=$msg1&priority=ndnd&stype=normal";

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $curl_scraped_page = curl_exec($ch);
                curl_close($ch);

                //$this->leads_model->log_lead_activity($lead_id, 'Company Profile Send','',serialize([$to]));

                do_action('smtp_test_email_success');
                redirect(admin_url('leads'));
            } else {
                set_debug_alert('<h1>Your SMTP settings are not set correctly here is the debug log.</h1><br />' . $this->email->print_debugger() . (isset($GLOBALS['debug']) ? $GLOBALS['debug'] : ''));
                do_action('smtp_test_email_failed');
                redirect(admin_url('leads'));
            }
        }
    }

/* custom code end */
}
