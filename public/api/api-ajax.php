<?php
namespace Oym\Uap\Public\Api;
use Oym\Uap\Includes;
Class Api_Ajax{
    Private $user_application_manager;

    function __construct() {
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();

        add_action( 'wp_ajax_get_user_applications_by_range_json', array($this, 'get_user_applications_by_range_json'));
        add_action( 'wp_ajax_get_user_applications_by_user_json', array($this, 'get_user_applications_by_user_json'));
        add_action( 'wp_ajax_get_applications', array($this, 'get_applications'));
        add_action( 'wp_ajax_delete_user_application_by_guid', array($this, 'delete_user_application_by_guid'));
    }

    public function check_security(){
        if ( ! check_ajax_referer( 'oymuap-security-nonce', 'security', false ) ) {
            wp_send_json_error( 'Invalid security token sent.' );
            wp_die();
        }
        return;
    }

    #region get

    public function get_user_applications_by_range_json(){
        $this->check_security();
        $start_date =  isset($_POST['start_date']) ?  $_POST['start_date'] : null;
        $end_date =  isset($_POST['end_date']) ?  $_POST['end_date'] : null;

        $current_datetime_start_obj = ($start_date === null) ?date_time_set(new \DateTime('NOW'), 0, 0) : date_time_set(new \DateTime($start_date), 0, 0);
        $current_datetime_end_obj = ($end_date === null) ?date_time_set(new \DateTime('NOW'), 0, 0) : date_time_set(new \DateTime($end_date), 0, 0);
        $current_datetime_end_obj->modify('+1 day');

        $results = $this->user_application_manager->get_user_applications_by_range($current_datetime_start_obj, $current_datetime_end_obj);
        wp_send_json($results);

    }

    public function get_user_applications_by_user_json(){
        $this->check_security();
        $selected_user_id =  $_POST['selected_user_id'];
        $results = $this->user_application_manager->get_user_applications_by_user($selected_user_id);
        wp_send_json($results);;
    }

    #endregion

    public function delete_user_application_by_guid(){
        $this->check_security();
        $user_application_guid =  $_POST['user_application_guid'];
        $results = $this->user_application_manager->delete_user_application_by_guid($user_application_guid);
        wp_send_json($results);
    }
}
$oymuap_api_ajax = new Api_Ajax();