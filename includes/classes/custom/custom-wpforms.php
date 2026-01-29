<?php
use Oym\Uap\Includes;

Class Custom_Wpforms{
    Private $data;

    function __construct() {
        $this->data = new Includes\Data();	
        add_action( 'wpforms_process_complete', array( $this, 'uap_forms_submit' ), 10, 4);
        add_filter( 'wpforms_smart_tags', array( $this, 'uap_register_smarttag'), 10, 1);
        add_filter( 'wpforms_smart_tag_process', array( $this, 'uap_process_smarttag'), 10, 2);
    }

    public function uap_forms_submit( $fields, $entry, $form_data, $entry_id ) {
        $process_manager = new Includes\Process_Manager();
        $wpforms_form_ids_settings = new Includes\Uap_Wpforms_Form_Ids_Settings();
        $form_id = absint( $form_data[ 'id' ] );
        if ( !in_array($form_id, $wpforms_form_ids_settings->form_ids) ) {
            return;
        }

        if ( $form_id == $wpforms_form_ids_settings->registration){
            $process_manager->uap_registration_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->start){
            $process_manager->uap_start_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->household){
            $process_manager->uap_household_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->demographics){
            $process_manager->uap_demographics_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->income){
            $process_manager->uap_income_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->energy){
            $process_manager->uap_energy_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->housing){
            $process_manager->uap_housing_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->completion){
            $process_manager->uap_completion_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->add_household_member){
            $process_manager->uap_add_household_member_form_submit($entry_id, $fields);
        }

        if ( $form_id == $wpforms_form_ids_settings->add_ece){
            $program = new Includes\Uap_User_Application_Program("ece", "Early Childhood Education");
            $process_manager->uap_add_program_form_submit($entry_id, $fields, $program);
        }

        if ( $form_id == $wpforms_form_ids_settings->add_food){
            $program = new Includes\Uap_User_Application_Program("food", "Food");
            $process_manager->uap_add_program_form_submit($entry_id, $fields, $program);
        }

        /*
        if ( $form_id == $wpforms_form_ids_settings->add_energy){
            $program = new Includes\Uap_User_Application_Program("energy", "Energy");
            $process_manager->uap_add_program_form_submit($entry_id, $fields, $program);
        }

        if ( $form_id == $wpforms_form_ids_settings->add_housing){
            $program = new Includes\Uap_User_Application_Program("housing", "Housing");
            $process_manager->uap_add_program_form_submit($entry_id, $fields, $program);
        }
        */

        if ( $form_id == $wpforms_form_ids_settings->add_weatherization){
            $program = new Includes\Uap_User_Application_Program("weatherization", "Weatherization");
            $process_manager->uap_add_program_form_submit($entry_id, $fields, $program);
        }
    }

    public function uap_register_smarttag( $tags ) {
        // Key is the tag, item is the tag name.
        $tags['uap_get_current_user_id'] = 'UAP Get Current User ID';
        $tags['uap_get_current_user_name'] = 'UAP Get Current User Name';
        $tags['uap_get_current_user_age'] = 'UAP Get Current User Age';
        $tags['uap_get_programs'] = 'UAP Get Programs';
        $tags['uap_get_household_members_wlb'] = 'UAP Get Household Members with Linebreaks';
        $tags['uap_is_last_user'] = 'UAP Is Last User?';
        $tags['uap_get_user_application_progress'] = 'UAP - Get User Application Progress';
        $tags['uap_get_user_application_url'] = 'UAP Get User Application URL';
        $tags['uap_get_current_user_application_guid'] = 'UAP Get Current User Application GUID';
        $tags['uap_is_program_active program=""'] = 'Check if Program is Active'; //used all over 
        
        return $tags;
    }

    public function uap_process_smarttag($content, $tag ) {
        $user_application_manager = new Includes\Uap_User_Application_Manager();
        $current_user = wp_get_current_user();

        if (preg_match_all( "/\{uap_is_program_active program=\"(.+?)\"\}/", $content, $programs )){;
            if (is_user_logged_in()){
                if ( ! empty( $programs[1] ) ) {
                    foreach ( $programs[1] as $key => $program ) {
                        $settings = \get_option('oymuap_program_settings');
                        foreach ($settings as $k => $v) {
                            if (str_contains($k, $program)){
                                $value = $v == "yes" ? "Yes" : "No";
                            }
                        } 
                        $content = $value;
                    }
                }
            } else {
                $content = '';
            }
        }


        $passed_user_application_guid = isset($_GET["user_application_guid"]) ? $_GET["user_application_guid"] : "";

        if (empty($passed_user_application_guid)){
            $user_application = $user_application_manager->get_current_user_application_by_wp_user($current_user);
        } else {
            $user_application =  $user_application_manager->get_user_application($passed_user_application_guid);
        }
        
        if (empty($user_application)){
            return $content;
        }

        $user_application_guid = $user_application->user_application_guid;
        $current_app_user = $this->data->dbGet_current_user_byGuid($user_application_guid);
        $current_user_number = $this->data->dbGet_current_user_number_byGuid($user_application_guid);
        $total_user_number = $this->data->dbGet_users_count_byGuid($user_application_guid) ?: "Unknown";

        switch ($tag) {
            case "uap_get_current_user_id":
                $value = $current_app_user->ID;
                $content = str_replace( '{' . $tag . '}', $value, $content );
                break;

            case "uap_get_current_user_name":
                $value = $current_app_user->name;
                $value = (empty($value)) ? "Test User" : $value;
                $content = str_replace( '{' . $tag . '}', $value, $content );
                break;

            case "uap_get_current_user_age":
                $value = $current_app_user->age;
                $content = str_replace( '{' . $tag . '}', $value, $content ); 
                break;

            case "uap_get_programs":
                $programs_text = '';
                foreach ($user_application->programs_arr as $program){
                    $programs_text .= $program->full_name . ", ";
                }
                $content = str_replace( '{' . $tag . '}', $programs_text, $content );
                break;

            case "uap_get_household_members_wlb":
                $value = $user_application_manager->get_users_wlb_by_guid($user_application_guid);
                $content = str_replace( '{' . $tag . '}', $value, $content );
                break;
        
            case "uap_is_last_user":
                $is_last_user = "Yes";
                $is_last_user = ($current_user_number == $total_user_number ) ? 'Yes' : 'No';
                $content = str_replace( '{' . $tag . '}', $is_last_user, $content );
                break;
            
            case "uap_get_user_application_progress":
                $current_user_name = $current_app_user->name ?: "Test User";

                $html = '<div class="oymuap-progress-container"><span class="title">Application Information for<br>';
                $html .= $current_user_name . '<br>';
                $html .= $current_user_number . ' of ' . $total_user_number . ' total household users</span><br></div>';
                $content = str_replace( '{' . $tag . '}', $html, $content );
                break;

            case "uap_get_user_application_url":
                $pdf_filename_url_path = OYMUAP_PDF_URL_PATH . $user_application->application_pdf_name;
                $content = str_replace('{uap_get_user_application_url}', $pdf_filename_url_path, $content);
                break;

            case "uap_get_current_user_application_guid":            
                $content = str_replace('{uap_get_current_user_application_guid}', $user_application_guid, $content);
                break;
   
        }
    
        return $content;
    }

}
$custom_wpforms = new Custom_Wpforms();

