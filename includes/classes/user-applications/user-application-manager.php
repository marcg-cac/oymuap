<?php
namespace Oym\Uap\Includes;
use Oym\Uap\Includes;

Class Uap_User_Application_Manager{
    Private $data;
    Public $application_id;
    Public $user_application_guid;
    Public $home_address;
    Public $mailing_address;
    Public $url_settings;
    

    #region OBJECTS
    Public $user_application;
    Public $users;
    Public $user_count;
    Public $hoh;
    Public $programs;
    Public $is_crisis;

    Private $empty_demographics_json;
    Private $empty_income_json;
    #endregion

    function __construct() {
        $this->data =  new Includes\Data();	
        $this->url_settings = new Includes\Uap_Url_Settings();
        $this->empty_demographics_json = json_encode(new Includes\Uap_User_Demographics());
        $this->empty_income_json = json_encode(new Includes\Uap_User_Income());
    }

    #region GET user_applications
    public function get_current_user_application_by_wp_user($wp_user){
        $current_user_application_guid = $this->data->dbGet_current_user_application_guid_byWpUser($wp_user);
        $current_user_application = (! empty($current_user_application_guid)) ? $this->get_user_application($current_user_application_guid) : false; 
        return $current_user_application;
    }
  
    public function get_user_application($user_application_guid){
    
        $this->user_application = new Includes\Uap_User_Application();
        
        $user_application = $this->data->dbGet_user_application_byGuid($user_application_guid);

        if (! is_null($user_application)){
            $this->user_application->ID = $user_application->ID;
            $this->user_application->user_application_guid = $user_application->user_application_guid;
            $this->user_application->application_pdf_name = $user_application->application_pdf_name;
            $this->user_application->registered_user_id = $user_application->registered_user_id;
            $this->user_application->application_date = $user_application->application_date;
            $this->user_application->application_date_obj = new \DateTime($user_application->application_date);
            $this->user_application->application_year_id = $user_application->application_year_id;
            $this->user_application->current_app_user = $user_application->current_app_user;
            $this->user_application->hear_about_cac = $user_application->hear_about_cac;
            $this->user_application->application_certification = $user_application->application_certification;
            $this->user_application->signature_certification = $user_application->signature_certification;
            $this->user_application->typed_name = $user_application->typed_name;
            $this->user_application->signature_url = $user_application->signature_url;
            $this->user_application->signature = $user_application->signature;
            $this->user_application->status = $user_application->status;
            
            $this->user_application->application_json = $user_application->application_json;
            $this->user_application->programs_arr = ! empty($user_application->application_json) ? json_decode($user_application->application_json)->programs : [];
            $this->user_application->household_obj =  json_decode((! empty($user_application->household_json) ? $user_application->household_json : ""), false);
    
            $this->user_application->energy_obj =  json_decode((! empty($user_application->energy_json) ? $user_application->energy_json : ""), false);
            $this->user_application->housing_obj =  json_decode((! empty($user_application->housing_json) ? $user_application->housing_json : ""), false);
    
            $this->user_application->programs_text = $this->get_programs_text();
    
            $this->user_application->users_arr = $this->create_existing_users($user_application_guid);
            $this->user_application->user_count = count($this->user_application->users_arr);
            $this->user_application->is_crisis =  $this->get_crisis();
            $this->user_application->hoh_obj = $this->get_hoh();
            $this->user_application->home_address = $this->get_hoh_home_address();
            $this->user_application->mailing_address = $this->get_hoh_mailing_address();
        }

        return $this->user_application;

    }

    public function get_user_applications_by_year($selected_year_id){
        $user_applications = $this->data->dbGet_user_applications_byYear($selected_year_id);
        $json_array = array();
        if (!empty($user_applications)){
            foreach ($user_applications as $user_application) {
                array_push($json_array, array('id' => $user_application->registered_user_id, 'name' => $user_application->name, 'email' => $user_application->email, 
                'phone' => $user_application->home_phone, 'user_id' => $user_application->registered_user_id));	
            }
        }
        return $json_array;
    }

    public function get_user_applications_by_range($start_date_obj, $end_date_obj){
        $user_applications = $this->data->dbGet_user_applications_byRange($start_date_obj->format('Y-m-d H:i:s'), $end_date_obj->format('Y-m-d H:i:s'));
        $json_array = array();
        if (!empty($user_applications)){
            foreach ($user_applications as $user_application) {
                array_push($json_array, array('user_application_id' => $user_application->ID, 'name' => $user_application->name, 'email' => $user_application->email, 
                'phone' => $user_application->home_phone, 'application_date' => $user_application->application_date, 
                'user_id' => $user_application->registered_user_id, 'user_application_guid' => $user_application->user_application_guid));	
            }
        }
        return $json_array;
    }

    public function get_user_applications_by_user($selected_user_id){
        $user_applications = $this->data->dbGet_user_applications_byUser($selected_user_id);
        $json_array = array();
        if (!empty($user_applications)){
            foreach ($user_applications as $user_application) {
                array_push($json_array, array('id' => $user_application->id, 'application_date' => $user_application->application_date, 'user_application_guid' => $user_application->user_application_guid));	
            }
        }
        wp_send_json($json_array);
    }

    public function get_application_continuation_url($user_application){
        $redirect_url = '';
        switch ($user_application->status) {
            case "registered":
                $redirect_url = $this->url_settings->get_full_url('application_start');
                break;

            case "start_completed":
                $redirect_url = $this->url_settings->get_full_url('application_household');
                break;

            case "household_completed":
                $redirect_url = $this->url_settings->get_full_url('application_demographics');
                break;

            case "current_user_demographics_completed":
                $redirect_url = $this->url_settings->get_full_url('application_income');
                break;
    
            case "current_user_completed":
                $redirect_url = $this->url_settings->get_full_url('application_demographics');
                break;

            case "users_completed":
                if (in_array("energy", array_column($user_application->programs_arr, "short_name"))){
                    $redirect_url = $this->url_settings->get_full_url('application_energy');
                } elseif (in_array("housing", array_column($user_application->programs_arr, "short_name"))){
                    $redirect_url = $this->url_settings->get_full_url('application_housing');
                }
                break;

            case "energy_completed":
                if (in_array("housing", array_column($user_application->programs_arr, "short_name"))){
                    $redirect_url = $this->url_settings->get_full_url('application_housing');
                } else {
                    $redirect_url = $this->url_settings->get_full_url('application_completion');
                }
                break;

            case "housing_completed":
                $redirect_url = $this->url_settings->get_full_url('application_completion');
                break;       
        }
        $redirect_url .= "/?user_application_guid=" . $user_application->user_application_guid ;
        return $redirect_url;
    
    }

    #endregion

    #region DELETE user_application
    public function delete_user_application_by_guid($user_application_guid){
        return $this->data->dbDelete_user_application_byGuid($user_application_guid);
    }
    #endregion

    public function create_existing_users($user_application_guid){
        $users_arr = [];
        $users = $this->data->dbGet_users_byGuid($user_application_guid);
        if (!empty($users)){
            foreach ($users as $user) {
                $user_obj = new Includes\Uap_User();
                $user_obj->ID = $user->ID;
                $user_obj->user_application_guid = $user->user_application_guid;
                $user_obj->wp_user_id = $user->wp_user_id;
                $user_obj->application_id = $user->application_id;
                $user_obj->household_rank = $user->household_rank;
                $user_obj->name = $user->name;
                $user_obj->gender = $user->gender;
                $user_obj->dob = $user->dob;
                $user_obj->dob_obj = new \DateTime($user->dob);
                $user_obj->age = $user->age;
                $user_obj->home_phone = $user->home_phone;
                $user_obj->mobile_phone = $user->mobile_phone;
                $user_obj->email = $user->email;
                $user_obj->relationship = $user->relationship;
                $user_obj->hoh = $user->hoh;
                $user_obj->completed = $user->completed;
                $user_obj->created = $user->created;
                $user_obj->address_obj = json_decode((! empty($user->address_json) ? $user->address_json : ""), false);
                $user_obj->demographics_obj = json_decode((! empty($user->demographics_json) ? $user->demographics_json : $this->empty_demographics_json), false);
                $user_obj->income_obj = json_decode((! empty($user->income_json) ? $user->income_json : $this->empty_income_json), false);
                $users_arr[] = $user_obj;
            }
        }
        return $users_arr;
    }

    #region UTILITY
    public function get_programs_text(){
        $programs_text = '';
        $programs_text .= (in_array("energy", array_column($this->user_application->programs_arr, "short_name"))) ? 'Energy, ' : '';
        $programs_text .= (in_array("food", array_column($this->user_application->programs_arr, "short_name"))) ? 'Food, ' : '';
        $programs_text .= (in_array("housing", array_column($this->user_application->programs_arr, "short_name"))) ? 'Housing, ' : '';
        $programs_text .= (in_array("ece", array_column($this->user_application->programs_arr, "short_name"))) ? 'ECE, ' : '';
        $programs_text .= (in_array("snap", array_column($this->user_application->programs_arr, "short_name"))) ? 'SNAP, ' : '';
        $programs_text = rtrim($programs_text, ", ");

        return $programs_text;
    }

    public function get_crisis(){
        $is_energy_crisis = false;
        $is_housing_crisis = false;
        
        if (! empty($this->user_application->energy_obj)){
            $is_energy_crisis = ($this->user_application->energy_obj->crisis == "Yes") ? true : false;
        }
        if (! empty($this->user_application->housing_obj)){
            $is_housing_crisis = ($this->user_application->housing_obj->crisis == "Yes") ? true : false;
        }
        $is_crisis = ($is_energy_crisis || $is_housing_crisis) ? true : false;
        
        return $is_crisis;
    }

    public function get_user_count(){
        return count($this->users);
    }

    public function get_hoh(){
        if (!empty($this->user_application->users_arr)){
            foreach ($this->user_application->users_arr as $user) {
                if ($user->hoh == "1"){
                    return $user;
                }
            }
        }
    }
    
    public function get_hoh_home_address(){
        $hoh_addresses = $this->user_application->hoh_obj->address_obj;
        $home_address = "N/A";
        if (!empty($hoh_addresses->home)){
            $home_address = $hoh_addresses->home->street_1 . '<br>';
            if ($hoh_addresses->home->street_2 <> ''){
                $home_address .= $hoh_addresses->home->street_2 . '<br>';
            }
            $home_address .= $hoh_addresses->home->city . ', ';
            $home_address .= $hoh_addresses->home->state . ' ';
            $home_address .= $hoh_addresses->home->zip;
        }
        return $home_address;
    }

    public function get_hoh_mailing_address(){
        $hoh_addresses = $this->user_application->hoh_obj->address_obj;
        $mailing_address = "N/A";
        if (!empty($hoh_addresses->mailing)){
            $mailing_address = $hoh_addresses->mailing->street_1 . '<br>';
            if ($hoh_addresses->mailing->street_2 <> ''){
                $mailing_address .= $hoh_addresses->mailing->street_2 . '<br>';
            }
            $mailing_address .= $hoh_addresses->mailing->city . ', ';
            $mailing_address .= $hoh_addresses->mailing->state . ' ';
            $mailing_address .= $hoh_addresses->mailing->zip;
        }
        return $mailing_address;
    }

    Public function get_users_wlb_by_guid($user_application_guid){
        $users = $this->data->dbGet_users_byGuid($user_application_guid);
        
        $users_wlb = '';
        if (!empty($users)){
            foreach ($users as $user) {
                $users_wlb .= $user->name . "<br>";
            }
        }
        return $users_wlb;
    }
    #endregion
}


?>