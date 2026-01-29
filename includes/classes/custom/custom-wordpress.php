<?php
use Oym\Uap\Includes;

Class Custom_Wordpress{
    Private $user_application_manager;
    Private $url_settings;

    function __construct() {
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();
        $this->url_settings = new Includes\Uap_Url_Settings();
        add_filter( 'body_class', array( $this, 'customize_body_classes' ), 10, 4 );       
        add_shortcode( 'oymuap_get_user_application_table', array( $this, 'get_user_application_table'));
        add_shortcode( 'oymuap_get_application_url', array( $this, 'get_application_url' ) );
        add_shortcode( 'oymuap_get_registered_user_name', array( $this, 'get_registered_user_name'));
    }
        
    public function get_registered_user_name(){
        $user = wp_get_current_user();
        return $user->display_name;      
    }
    
    public function get_user_application_table(){
        $wp_user = wp_get_current_user();
        $user_application_manager = new Includes\Uap_User_Application_Manager();
        $current_user_application = $user_application_manager->get_current_user_application_by_wp_user($wp_user);
        $current_user_application_exists = ($current_user_application == false) ? false : true;

        if ($current_user_application_exists){
            $current_user_application_completed = $current_user_application->status == "completed" ? true : false;
        }

        $html = '<table ><tbody><tr>';
        $html .= '<td id="uap-applications-status-row">Current</td><td>';
        if ($current_user_application_exists){
            if ($current_user_application_completed){
                $current_user_application_url = $this->build_application_url($current_user_application->user_application_guid);
                $html .= '<a href="' .  $current_user_application_url . '" target="_blank" rel="noopener">Download your CAC Universal Application <i class="fa fa-cloud-download"></i></a>';
                $html .= '<div class="uap-tablerow-text-small"><p>Please remember, submitting your application is only the first step.<br> 
                For each service you requested, you also need to download the supplemental packet of information and follow the instructions on each checklist.<br> 
                These packets instruct you to gather additional documents and complete other required forms.<br> 
                Your case cannot be processed until we receive these supplemental documents.</p></div>';
            } else {
                $continue_application_url = $this->url_settings->get_full_url('application_continuation');
                $html .= '<a href="' . $continue_application_url . '" target="_blank" rel="noopener">Complete your Application started on ' . $current_user_application->application_date_obj->format("m-d-Y h:i:s A") .  ' <i class="fa5s fa5-laptop"></i></a>';
                //$html .= '<a href="' . $continue_application_url . '" target="_blank" rel="noopener">Complete your Application started on ' . $current_user_application->application_date .  ' <i class="fa5s fa5-laptop"></i></a>';
            }
        } else {
            $html .= '<a href="' . $this->url_settings->get_full_url('application_start') . '" target="_blank" rel="noopener">Start a new Application<i class="fa5s fa5-laptop"></i></a>';
        }

        $html .= '</td></tr>';
        $html .= '</tbody></table>';

        return $html;
    }

    public function get_application_url() {  //used on the application download page
        $html = "<div class='oymuap-application-download'>No Application Found</div>";
        if (is_admin()){
            return;
        } else {
            if (isset($_GET["user_application_guid"])){
                $user_application_guid = $_GET["user_application_guid"];
                $application_url = $this->build_application_url($user_application_guid);
                $html = (! $application_url <> '') ? $html : "<div class='oymuap-application-download'><a href='" . $application_url . "'>Universal Application</a></div>";
            }
            return $html;
        }
	}

    public function build_application_url($user_application_guid) {
        $pdf_filename_url_path = '';
        $user_application = $this->user_application_manager->get_user_application($user_application_guid);

        if (! is_null($user_application)){
            if ($user_application->ID <> 0){
                $user_application_pdf = new User_Application_Pdf($user_application->user_application_guid);
                $user_application_pdf->create_pdf();
                $pdf_filename_url_path = OYMUAP_PDF_URL_PATH . $user_application->application_pdf_name;
            }
        }
        return $pdf_filename_url_path;
	}

    public function customize_body_classes($classes){
        if (! is_user_logged_in()){
            $classes[] = "logged_out";
            return $classes;
        }

        $classes[] = "logged_in";	// Give user the logged in ROLE

        $wp_user = wp_get_current_user();
        $user_application_manager = new Includes\Uap_User_Application_Manager();
        $current_user_application = $user_application_manager->get_current_user_application_by_wp_user($wp_user);
        $current_user_application_exists = ($current_user_application) ? true : false;

        $current_user_application_completed = ($current_user_application_exists && $current_user_application->status == "completed") ? true : false;

        //If the user's current_user_application_completed is completed - show the services
        if ($current_user_application_completed) {
            $classes[] = "override_services_visibility";
        }
    
        if($current_user_application_exists) {
                if (! empty($current_user_application->programs_arr)){
                    $programShortName = "";
                    if (in_array("energy", array_column($current_user_application->programs_arr, "short_name"))){     
                        $programShortName = "energy";
                        $programShortNameReverse = "energy_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    } 

                    if (in_array("food", array_column($current_user_application->programs_arr, "short_name"))){  
                        $programShortName = "food";
                        $programShortNameReverse = "food_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    } 

                    if (in_array("housing", array_column($current_user_application->programs_arr, "short_name"))){  
                        $programShortName = "housing";
                        $programShortNameReverse = "housing_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    } 

                    if (in_array("ece", array_column($current_user_application->programs_arr, "short_name"))){  
                        $programShortName = "ece";
                        $programShortNameReverse = "ece_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    }

                    if (in_array("snapp", array_column($current_user_application->programs_arr, "short_name"))){  
                        $programShortName = "snap";
                        $programShortNameReverse = "snap_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    }

                    if (in_array("weatherization", array_column($current_user_application->programs_arr, "short_name"))){  
                        $programShortName = "weatherization";
                        $programShortNameReverse = "weatherization_reverse";
                        $classes[] = "user-program-" . $programShortName;	
                        $classes[] = "user-program-" . $programShortNameReverse;	
                    }
                }
        } 
    
        return $classes;
    }

}
$custom_wordpress = new Custom_Wordpress();

