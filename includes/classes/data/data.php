<?php
namespace Oym\Uap\Includes;

Class Data{
    Public $current_wpdb;
    Public $user_application_table;
    Public $application_year_table;
    Public $user_table;

    function __construct() {
		global $wpdb;
		$this->current_wpdb = $wpdb;
        $this->user_application_table = $this->current_wpdb->prefix . "oymuap_user_application";
        $this->application_year_table = $this->current_wpdb->prefix . "oymuap_application_year";
        $this->user_table = $this->current_wpdb->prefix . "oymuap_user";
    }

    #region USER APPLICATIONS - GET

    public function dbGet_user_applications_byYear($selected_year_id){
        $sql =  " SELECT UA.registered_user_id, U.name, U.email, U.home_phone 
        FROM {$this->current_wpdb->prefix}oymuap_user_application UA
        INNER JOIN {$this->current_wpdb->prefix}oymuap_user U ON U.user_application_guid = UA.user_application_guid
        WHERE U.hoh = 1 AND UA.application_year_id = " . $selected_year_id;

        $user_applications = $this->current_wpdb->get_results($sql, OBJECT );
        return $user_applications;
    }

    public function dbGet_user_applications_byRange($start_date, $end_date){
        $sql =  " SELECT UA.ID, UA.registered_user_id, U.name, U.email, U.home_phone, UA.application_date, UA.user_application_guid 
        FROM {$this->current_wpdb->prefix}oymuap_user_application UA
        INNER JOIN {$this->current_wpdb->prefix}oymuap_user U ON U.user_application_guid = UA.user_application_guid
        WHERE U.hoh = 1 
        AND UA.application_date >= '{$start_date}' AND UA.application_date <= '{$end_date}' ORDER BY U.id";

        $user_applications = $this->current_wpdb->get_results($sql, OBJECT );
        return $user_applications;
    }

    public function dbGet_user_applications_byUser($selected_user_id){
        $sql =  " SELECT UA.id, UA.application_date, UA.application_year_id, U.name,  UA.user_application_guid  
        FROM {$this->current_wpdb->prefix}oymuap_user_application UA
        INNER JOIN {$this->current_wpdb->prefix}oymuap_user U ON U.ID = UA.registered_user_id
        WHERE UA.registered_user_id = " . $selected_user_id;

        $user_applications = $this->current_wpdb->get_results($sql, OBJECT );
        return $user_applications;
    }

    public function dbGet_current_user_application_guid_byWpUser($wp_user){
        $guid =  $this->current_wpdb->get_var(" SELECT UA.user_application_guid FROM {$this->current_wpdb->prefix}oymuap_user_application UA
        INNER JOIN {$this->current_wpdb->prefix}oymuap_user U ON U.ID = UA.registered_user_id
        INNER JOIN {$this->current_wpdb->prefix}oymuap_application_year UAY ON UAY.ID = UA.application_year_id
        WHERE  UAY.current_year = 1 AND U.wp_user_id = " . $wp_user->ID);

        return $guid;
    }


    public function dbGet_user_application_byGuid($user_application_guid) {
        $user_application =  $this->current_wpdb->get_row(" SELECT * FROM {$this->current_wpdb->prefix}oymuap_user_application
        WHERE user_application_guid = '" . $user_application_guid . "'");

        return $user_application;
    }


    #endregion

    #region USER APPLICATIONS - OTHER
    public function db_user_application_exists_byGuid($user_application_guid) {
        $user_application_count =  $this->current_wpdb->get_var(" SELECT COUNT(id) FROM {$this->current_wpdb->prefix}oymuap_user_application
        WHERE user_application_guid = '" . $user_application_guid . "'");

        $exists = ($user_application_count > 0) ? true : false;

        return $exists;
    }
    #endregion

    #region USER APPLICATIONS - EDIT

    public function dbCreate_user_application($user_application){
        $data = array(
            "user_application_guid" => $user_application->user_application_guid,
            "application_pdf_name" => $user_application->application_pdf_name,
            "registered_user_id" => $user_application->registered_user_id,
            "application_date" => $user_application->application_date,
            "application_year_id" => $user_application->application_year_id,
            "current_app_user" => $user_application->current_app_user,
            "application_json" => $user_application->application_json,
            "hear_about_cac" => $user_application->hear_about_cac,
            "application_certification" => $user_application->application_certification,
            "signature_certification" => $user_application->signature_certification,
            "typed_name" => $user_application->typed_name,
            "signature_url" => $user_application->signature_url,
            "status" => $user_application->status,
            "household_json" => $user_application->household_obj,
            "housing_json" => $user_application->housing_obj,
            "energy_json" => $user_application->energy_obj
        );

        $format = array('%s','%s','%d','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        $this->current_wpdb->insert($this->user_application_table,$data,$format);
        $insert_id = $this->current_wpdb->insert_id;
        return $insert_id;
    }

    public function dbUpdate_user_application($data, $where){
        $updated = $this->current_wpdb->update( $this->user_application_table, $data, $where ); 
        return $updated;
    }

    public function dbDelete_user_application_byGuid($user_application_guid){
        $where = array(
            'user_application_guid' => $user_application_guid
        );
        
        $user_deleted = $this->current_wpdb->delete( $this->user_table, $where ); 
        $user_application_deleted = $this->current_wpdb->delete( $this->user_application_table, $where ); 
        
    }

    #endregion

    #region USERS - GET

    public function dbGet_users_byGuid($user_application_guid) {
        $sql =  " SELECT * FROM {$this->current_wpdb->prefix}oymuap_user WHERE user_application_guid = '" . $user_application_guid . "'";
        $users = $this->current_wpdb->get_results($sql, OBJECT );
        return $users;
    }

    Public function dbGet_users_count_byGuid($user_application_guid){
        $users_count = $this->current_wpdb->get_var(" SELECT COUNT(*) FROM " . $this->user_table . " WHERE user_application_guid = '" . $user_application_guid . "'");
        return $users_count;
    }

    Public function dbGet_next_user_id_byGuid($user_application_guid){
        $next_user_id = $this->current_wpdb->get_var(" SELECT * FROM " . $this->user_table . " WHERE user_application_guid = '" . $user_application_guid . "' AND completed iS NULL ORDER BY ID");
        return $next_user_id;
    }

    Public function dbGet_current_user_byGuid($user_application_guid){
        $current_user = $this->current_wpdb->get_row(" SELECT * FROM " . $this->user_table . " WHERE ID = 
            (SELECT current_app_user FROM " . $this->user_application_table . " WHERE user_application_guid = '" . $user_application_guid . "')");
        return $current_user;
    }

    Public function dbGet_current_user_number_byGuid($user_application_guid){
        $user_number = $this->current_wpdb->get_var(" SELECT COUNT(*) FROM " . $this->user_table . " WHERE user_application_guid = '" . $user_application_guid . "' AND completed iS NOT NULL");
        $user_number = $user_number + 1;
        return $user_number;
    }

    #endregion

    #region USERS - WRITE
    
    public function dbUpdate_user($data, $where){
        $updated = $this->current_wpdb->update( $this->user_table, $data, $where ); 
        return $updated;
    }

    public function dbCreate_user($user){
        $data = array(
            "user_application_guid" => $user->user_application_guid,
            "wp_user_id" => $user->wp_user_id,
            "application_id" => $user->application_id,
            "household_rank" => $user->household_rank,
            "name" => $user->name,
            "gender" => $user->gender,
            "dob" => $user->dob,
            "age" => $user->age,
            "home_phone" => $user->home_phone,
            "mobile_phone" => $user->mobile_phone,
            "email" => $user->email,
            "relationship" => $user->relationship,
            "hoh" => $user->hoh,
            "completed" => $user->completed,
            "address_json" => $user->address_obj,
            "demographics_json" => $user->demographics_obj,
            "income_json" => $user->income_obj,
            "created" => $user->created
        );
   
        $format = array('%s','%d','%d','%d','%s','%s','%s','%d','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s');
        $this->current_wpdb->insert($this->user_table,$data,$format);
        $insert_id = $this->current_wpdb->insert_id;
        return $insert_id;
    }

    #endregion

    #region APPLICATION YEAR - GET

    public function dbGet_current_application_year(){
        $current_application_year =  $this->current_wpdb->get_row(" SELECT * FROM {$this->current_wpdb->prefix}oymuap_application_year
        WHERE current_year = 1");
        return $current_application_year;
    }

    public function dbGet_application_years(){
        $sql =  "SELECT * FROM {$this->current_wpdb->prefix}oymuap_application_year";
        $application_years = $this->current_wpdb->get_results($sql, OBJECT );
        return $application_years;
    }

    #endregion


}
