<?php
namespace Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;
use Oym\Uap\Admin\Pages;

#region user_application
Class Uap_User_Application{
    Public $ID;
    Public $user_application_guid;
    Public $application_pdf_name;
    Public $registered_user_id;
    Public $application_date;
    Public $application_date_obj;
    Public $application_year_id;
    Public $current_app_user;
    Public $application_json;
    Public $hear_about_cac;
    Public $application_certification;
    Public $signature_certification;
    Public $typed_name;
    Public $signature_url;
    Public $signature;
    Public $status;

    Public $user_count;
    Public $is_crisis;
    Public $home_address;
    Public $mailing_address;

    Public $programs_arr;
    Public $programs_text;
    Public $hoh_obj;
    Public $household_obj;
    Public $housing_obj;
    Public $energy_obj;
    Public $users_arr;

    function __construct() {
        $this->ID = 0;
        $this->user_application_guid = 0;
        $this->application_pdf_name = '';
        $this->registered_user_id = 0;
        $this->application_date = '';
        $this->application_date_obj = '';
        $this->application_year_id = 0;
        $this->household_obj = '';
        $this->application_json = '';
        $this->hear_about_cac = '';
        $this->application_certification = '';
        $this->signature_certification = '';
        $this->typed_name = '';
        $this->signature_url = '';
        $this->signature = '';
        $this->status = '';
        $this->programs_text = '';
        $this->user_count = 0;
        $this->hoh_obj = '';
        $this->household_obj = '';
        $this->housing_obj = '';
        $this->energy_obj = '';
    }
}

Class Uap_User_Application_Json{
    Public $programs;

    function __construct() {
        $this->programs = [];
    }
}

Class Uap_User_Application_Program{
    Public $short_name;
    Public $full_name;

    function __construct($short_name = '', $full_name = '') {
        $this->short_name = $short_name;
        $this->full_name = $full_name;
    }
}

Class Uap_Household{
    Public $housing_owner_info;
    Public $housing_owner_info_other;
    Public $landlord_apartment_name;
    Public $landlord_phone;
    Public $landlord_email;
    Public $housing_type_info;
    Public $housing_type_info_other;
    Public $section_eight_mbq;
    Public $utility_allowance;
    Public $family_type;
    Public $family_type_other;
    Public $primary_household_language;
    Public $created;

    function __construct() {
        $this->housing_owner_info = '';
        $this->housing_owner_info_other = '';
        $this->landlord_apartment_name = '';
        $this->landlord_phone = '';
        $this->landlord_email = '';
        $this->housing_type_info = '';
        $this->housing_type_info_other = '';
        $this->section_eight_mbq = '';
        $this->utility_allowance = '';
        $this->family_type = '';
        $this->family_type_other = '';
        $this->primary_household_language = '';
        $this->created = '';
    }
}

Class Uap_Energy{
    Public $crisis;
    Public $electric_off;
    Public $eusp;
    Public $electric_company_name;
    Public $electric_company_bill_name;
    Public $electric_company_account;
    Public $heating_off;
    Public $meap;
    Public $main_heating_source;
    Public $heating_company_name;
    Public $heating_company_bill_name;
    Public $heating_company_account;
    Public $uspp;
    Public $ara;
    Public $gara;
    Public $dhcd;
    Public $created;

    function __construct() {

    }
}

Class Uap_Housing{
    Public $cac_assistance_last_thirty;
    Public $owe_landlord_currently;
    Public $contribute_to_owed_rent;
    Public $federal_state_assistance;
    Public $steady_income;
    Public $income_equal_rent;
    Public $crisis;
    Public $eviction_soon;
    Public $created;

    function __construct() {

    }
}

#endregion


#region users
Class Uap_User{
    Public $ID;
    Public $user_application_guid;
    Public $wp_user_id;
    Public $application_id;
    Public $household_rank;
    Public $name;
    Public $gender;
    Public $dob;
    Public $dob_obj;
    Public $age;
    Public $home_phone;
    Public $mobile_phone;
    Public $email;
    Public $relationship;
    Public $hoh;
    Public $completed;
    Public $created;
    Public $address_json;
    Public $demographics_json;
    Public $income_json;
    Public $address_obj;
    Public $demographics_obj;
    Public $income_obj;


    function __construct() {
        $this->ID = 0;
        $this->wp_user_id = 0;
        $this->application_id = 0;
        $this->household_rank = 0;
        $this->name = '';
        $this->gender = '';
        $this->dob = '';
        $this->dob_obj = '';
        $this->age = 0;
        $this->home_phone = '';
        $this->mobile_phone = '';
        $this->email = '';
        $this->relationship = '';
        $this->hoh = 0;
        $this->created = date('Y-m-d H:i:s');
        $this->address_json = '';
        $this->demographics_json = '';
        $this->income_json = '';
    }
}

Class Uap_User_Demographics{
    Public $user_id;
    Public $user_application_guid;
    Public $application_id;
    Public $citizenship;
    Public $race;
    Public $other_race;
    Public $ethnicity;
    Public $primary_language;
    Public $other_language;
    Public $education_schooling;
    Public $job_status;
    Public $school_job_training;
    Public $school_job_training_name;
    Public $military_status;
    Public $marital_status;
    Public $disabled;
    Public $insurance_coverage;
    Public $created;

    function __construct() {
        $this->user_id = 0;
        $this->application_id = 0;
        $this->citizenship = '';
        $this->race = '';
        $this->other_race = '';
        $this->ethnicity = '';
        $this->primary_language = '';
        $this->other_language = '';
        $this->education_schooling = '';
        $this->job_status = '';
        $this->school_job_training = '';
        $this->school_job_training_name = '';
        $this->military_status = '';
        $this->marital_status = '';
        $this->disabled = 0;
        $this->insurance_coverage = '';
        $this->created = '';
    }
}

Class Uap_User_Income{
    Public $user_id;
    Public $user_application_guid;
    Public $application_id;
    Public $income_last_thirty;
    Public $employment_sources;
    Public $full_time_job;
    Public $part_time_job;
    Public $paid_in_cash;
    Public $self_employed;
    Public $short_term_disability;
    Public $long_term_disability;
    Public $veteran_benefits;
    Public $other_income_sources;
    Public $child_support_alimony;
    Public $pension;
    Public $social_security;
    Public $tanf;
    Public $unemployment;
    Public $workers_comp;
    Public $other_sources;
    Public $non_cash_benefits;
    Public $snap_expiration;
    Public $interested_in_snap;
    Public $created;


    function __construct() {
        $this->user_id = 0;
        $this->application_id = 0;
        $this->income_last_thirty = '';
        $this->employment_sources = '';
        $this->full_time_job = '';
        $this->part_time_job = '';
        $this->paid_in_cash = '';
        $this->self_employed = '';
        $this->short_term_disability = '';
        $this->long_term_disability = '';
        $this->veteran_benefits = '';
        $this->other_income_sources = '';
        $this->child_support_alimony = '';
        $this->pension = '';
        $this->social_security = '';
        $this->tanf = '';
        $this->unemployment = '';
        $this->workers_comp = '';
        $this->other_sources = '';
        $this->non_cash_benefits = '';
        $this->snap_expiration = '';
        $this->interested_in_snap = '';
        $this->created = '';
    }
}

#endregion


#region addresses

Class Uap_User_Addresses{
    Public $home;
    Public $mailing;

    function __construct() {
        $this->home = [];
        $this->mailing = [];
    }
}

Class Uap_User_Address{
    Public $home_address_street_1;
    Public $home_address_street_2;
    Public $home_address_city;
    Public $home_address_state;
    Public $home_address_zip;
    Public $mailing_address_street_1;
    Public $mailing_address_street_2;
    Public $mailing_address_city;
    Public $mailing_address_state;
    Public $mailing_address_zip;

    function __construct() {
        $this->home_address_street_1 = '';
        $this->home_address_street_2 = '';
        $this->home_address_city = '';
        $this->home_address_state = '';
        $this->home_address_zip = '';
        $this->mailing_address_street_1 = '';
        $this->mailing_address_street_2 = '';
        $this->mailing_address_city = '';
        $this->home_address_state = '';
        $this->mailing_address_zip = '';
    }
}

Class Uap_Address{
    Public $street_1;
    Public $street_2;
    Public $city;
    Public $state;
    Public $zip;

    function __construct() {
        $this->street_1 = '';
        $this->street_2 = '';
        $this->city = '';
        $this->state = '';
        $this->zip = '';
    }
}

#endregion


Class Uap_Email {
    public $email = '';
    public $name = '';

    function __construct($email, $name = '') {
        $this->email = $email;
        $this->name = $name;
    }
}
