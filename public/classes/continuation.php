<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
require_once($path.'wp-load.php');
use Oym\Uap\Includes;

Class Uap_Continuation{
    Public $user_application_manager;
    Public $continue_application_url;

    function __construct() {
        $this->continue_application_url = "/";
      //  add_action('init',array( $this, 'start' ));
        $this->start();
    }

    public function start(){
        if (is_user_logged_in()){
            $this->get_current_application();
        }
        $this->redirect();
    }

    public function get_current_application(){
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();
        $wp_user = wp_get_current_user();
        $current_user_application = $this->user_application_manager->get_current_user_application_by_wp_user($wp_user);

        if (! empty($current_user_application)){
            $this->continue_application_url = $this->user_application_manager->get_application_continuation_url($current_user_application);
        }
        return;
    }

    public function redirect(){
        header('Location: ' . $this->continue_application_url);
        exit;
    }
}

$oymuap_continuation = new Uap_Continuation();


