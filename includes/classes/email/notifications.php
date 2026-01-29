<?php
namespace Oym\Uap\Includes;
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/email/email-templates.php'; // no dependencies
require_once OYMUTILITY_PLUGIN_DIR . 'includes/classes/shared/email/sendgrid-smtp-api.php';
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

Class Uap_Notifications{
    Public $date_now;
    Public $user_application_guid;
    Public $user_application_pdf;
    Public $user_application_manager;
    Public $user_application;
    Public $uap_debug_settings;
    Public $uap_email_settings;
    Public $uap_url_settings;
    Public $uap_file_settings;

    function __construct() {
        $this->date_now = new \DateTime("now");
        $this->user_application_manager = new Uap_User_Application_Manager();
        $this->uap_debug_settings = new Includes\Uap_Debug_Settings();
        $this->uap_email_settings = new Includes\Uap_Email_Settings();
        $this->uap_url_settings = new Includes\Uap_Url_Settings();
        $this->uap_file_settings = new Includes\Uap_File_Settings();
    }

    Public function uap_completion_notifications($user_application_guid){ 
        $this->user_application_guid = $user_application_guid;  
        $this->user_application = $this->user_application_manager->get_user_application($this->user_application_guid);    

        $this->user_application_pdf = new \User_Application_Pdf($this->user_application_guid);
        $this->user_application_pdf->create_pdf();
        $this->completion_user_notification();
        $this->completion_assistance_notification();
    }

    Public function completion_user_notification(){      
        $sendgrid_mailer = new Utility_Includes\Oym_Sendgrid_Mailer();
        $email_template = new Includes\Uap_Email_Template();
        $email_template->subject = "Thank you for your application for services provided by Community Action Council of Howard County";

        $family_portal_url = $this->uap_url_settings->get_full_url('family_portal');

        if ($this->uap_debug_settings->override_on == 'yes' && $this->uap_debug_settings->to_email_override != ''){
            error_log('user notification debug');
            foreach ($this->uap_debug_settings->to_emails as $email){
                $email_template->add_recipient($email->email, $email->name);
            }
        } else {
            $email_template->add_recipient($this->user_application->hoh_obj->email, $this->user_application->hoh_obj->name);
        }

        $email_template->content = <<<email_content
            <p>Thank you so much for the opportunity to support you and your family! You have applied for the following services.
            <br>

            {$this->user_application->programs_text}<br>

            <p><strong>IMPORTANT:</strong> You are not done yet! We need a little more information. Please remember, submitting your application is only the first step.</p>
            <p>For each service you requested, you need to review the attached supplemental packet of information and follow the instructions on each checklist. 
            These packets instruct you to gather additional documents and complete other required forms. 
            Your case cannot be processed until we receive these supplemental documents.</p>
            
            <p>Your application will be assigned to one of our team members. Please be patient, as it may take up to 3-weeks before your application and supporting documentation can be reviewed.</p>

            <p>You can get a copy of your application by logging into the <a href='{$family_portal_url}'>FAMILY PORTAL</a> .</p>

            <p>We appreciate your patience and understanding during this time.</p>

            <img src="https://cac-all-websites-shared-content.s3.amazonaws.com/wp-content/uploads/2024/06/30162843/Community-Action-Council-Logo_Transp-400-x-82.png" >

            <p>IMPORTANT: Do you have a utility shutoff notice, or are you facing immediate eviction and have received a stamped eviction notice from the sheriff? Please contact us at 410-313-6440 or clientassistance@cac-hc.org</p>
        email_content;

        $this->attach_supplemental_docs($this->user_application->programs_arr, $email_template);
        $sendgrid_mailer->send_email_with_attachment($email_template);
    }

    Public function completion_assistance_notification(){   
        $sendgrid_mailer = new Utility_Includes\Oym_Sendgrid_Mailer();   
        $email_template = new Includes\Uap_Email_Template();

        $user_application_download_path = $this->uap_url_settings->get_full_url('pdf_page') . "?user_application_guid=" . $this->user_application_guid;
        $family_portal_url = $this->uap_url_settings->get_full_url('family_portal');

        $is_crisis = ($this->user_application_manager->user_application->is_crisis) ? "CRISIS - " : "";
        $email_template->subject = $is_crisis . " Application from " . $this->user_application->hoh_obj->name . " for ". $this->user_application->programs_text . " services " . $this->date_now->format('Y-m-d H:i:s');

        if ($this->uap_debug_settings->override_on == 'yes' && $this->uap_debug_settings->assistance_to_email_override != ''){
            foreach ($this->uap_debug_settings->assistance_to_emails as $email){
                $email_template->add_recipient($email->email, $email->name);
            }
        } else {
            $email_template->add_recipient($this->uap_email_settings->assistance_to_email, $this->uap_email_settings->assistance_to_name);
        }

        $email_template->content = <<<email_content
        {$this->user_application->hoh_obj->name} has submitted this application for {$this->user_application->programs_text} and has been given access to the supplemental packet of documents and directions on next steps.  

        <br>Please contact this client within 24-48 hours to have an initial discussion and provide details and instructions on next steps. <br>


        <a href='{$user_application_download_path}'>Download</a>
        email_content;

        $sendgrid_mailer->send_email_with_attachment($email_template);
    }

    Public function uap_add_program_notifications($user_application_guid, $program){ 
        $this->user_application_guid = $user_application_guid;  
        $this->user_application = $this->user_application_manager->get_user_application($this->user_application_guid);
        $this->user_application_pdf = new \User_Application_Pdf($this->user_application_guid);
        $this->user_application_pdf->create_pdf();
        $this->add_program_user_notification($program);
        $this->add_program_assistance_notification($program);
    }

    Public function add_program_user_notification($program){  
        $family_portal_url = $this->uap_url_settings->get_full_url('family_portal');
        $full_program_name = "";
        $full_program_name .= ($program->short_name == 'ece') ? "Early Childhood Education" : "";
        $full_program_name .= ($program->short_name == 'food') ? "Food" : "";
        $full_program_name .= ($program->short_name == 'energy') ? "Energy" : "";
        $full_program_name .= ($program->short_name == 'housing') ? "Housing" : "";
        $programs = array($program);

        $sendgrid_mailer = new Utility_Includes\Oym_Sendgrid_Mailer();

        $email_template = new Includes\Uap_Email_Template();

        $email_template->subject = "Thank you for your application for services provided by Community Action Council of Howard County";

        if ($this->uap_debug_settings->override_on == 'yes' && $this->uap_debug_settings->assistance_to_email_override != ''){
            foreach ($this->uap_debug_settings->assistance_to_emails as $email){
                $email_template->add_recipient($email->email, $email->name);
            }
        } else {
            $email_template->add_recipient($this->user_application->hoh_obj->email, $this->user_application->hoh_obj->name);
        }


        $email_template->content = <<<email_content
        <p>Thank you so much for the opportunity to support you and your family! You have applied for {$full_program_name} Services.</p>

        <p><strong>IMPORTANT:</strong> You are not done yet! We need a little more information. Please remember, submitting your application is only the first step.</p>

        <P>You need to review the attached supplemental packet of information and follow the instructions on each checklist. 
        These packets instruct you to gather additional documents and complete other required forms. 
        Your case cannot be processed until we receive these supplemental documents. Your application will be assigned to one of our team members. 
        Please be patient, as it may take up to 3 weeks before your application and supporting documentation can be reviewed.</p>

        <p>You can get a copy of your application by logging into the <a href='{$family_portal_url}'>FAMILY PORTAL</a></p>

        <p>We appreciate your patience and understanding during this time.</p>

        <img src="https://cac-all-websites-shared-content.s3.amazonaws.com/wp-content/uploads/2024/06/30162843/Community-Action-Council-Logo_Transp-400-x-82.png" >

        <p>Have you applied for energy or housing? Do you have a utility shutoff notice, 
        or are you facing immediate eviction and have received a stamped eviction notice from the sheriff? 
        Please contact us at 410-313-6440 or clientassistance@cac-hc.org. </p>
        email_content;

        $this->attach_supplemental_docs($programs, $email_template);
        $sendgrid_mailer->send_email_with_attachment($email_template);
    }

    Public function add_program_assistance_notification($program){  
        $family_portal_url = $this->uap_url_settings->get_full_url('family_portal');
        $user_application_download_path = $this->uap_url_settings->get_full_url('pdf_page') . "?user_application_guid=" . $this->user_application_guid;

        $full_program_name = $program->full_name;

        $sendgrid_mailer = new Utility_Includes\Oym_Sendgrid_Mailer();
        $email_template = new Includes\Uap_Email_Template();

        $email_template->subject = "Add-on request from " . $this->user_application->hoh_obj->name . " ( DOB: " . $this->user_application->hoh_obj->dob_obj->format("m-d-Y") . " ) for " . $full_program_name . " Services " . $this->date_now->format("m-d-Y h:i:s A") ;

        
        if ($this->uap_debug_settings->override_on == 'yes' && $this->uap_debug_settings->assistance_to_email_override != ''){
            foreach ($this->uap_debug_settings->assistance_to_emails as $email){
                $email_template->add_recipient($email->email, $email->name);
            }
        } else {
            $email_template->add_recipient($this->uap_email_settings->assistance_to_email, $this->uap_email_settings->assistance_to_name);
        }


        $email_template->content = <<<email_content
        <p> {$this->user_application->hoh_obj->name}  has submitted this application for {$full_program_name} and has been 
        given access to the supplemental packet of documents and directions on next steps.</p>
        <p>DOB: {$this->user_application->hoh_obj->dob_obj->format("m-d-Y")} </p>
        <p>ADDRESS: {$this->user_application->home_address} </p>

        <p>Please contact this client within 24-48 hours to have an initial discussion and provide details and instructions on next steps.</p>

        <a href='{$user_application_download_path}'>Download</a>
        email_content;

        $sendgrid_mailer->send_email_with_attachment($email_template);
    }

    public function attach_supplemental_docs($programs, $email_template){
        foreach ($programs as $program){
            $supplement = '';
            if (strtolower($program->short_name) == 'ece'){
                $supplement = $this->uap_file_settings->ece_supplement;
            }
            if (strtolower($program->short_name) == 'energy'){
                $supplement = $this->uap_file_settings->energy_supplement;
            }
            if (strtolower($program->short_name)== 'food'){
                $supplement = $this->uap_file_settings->food_supplement;
            }
            if (strtolower($program->short_name) == 'housing'){
                $supplement = $this->uap_file_settings->housing_supplement;
            }
            //$email_template->add_attachment(OYMUAP_PDF_SUPPLEMENT_FILE_PATH .  $supplement , $supplement, "application/pdf");
            $email_template->add_attachment(OYMUAP_CONTENT_DIR . $this->uap_file_settings->supplement_file_path .  $supplement , $supplement, "application/pdf");
        }
    }


}


