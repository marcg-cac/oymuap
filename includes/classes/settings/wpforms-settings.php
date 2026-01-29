<?php
namespace Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;
use Oym\Uap\Admin\Pages;

Class Uap_Wpforms_Form_Ids_Settings extends Utility_Includes\App_Settings {
    Public $registration;
    Public $start;
    Public $household;
    Public $demographics;
    Public $income;
    Public $energy;
    Public $housing;
    Public $completion;
    Public $add_household_member;
    Public $add_ece;
    Public $add_food;
    Public $add_energy;
    Public $add_housing;
    Public $add_weatherization;
    Public $form_ids;
    

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_formid_settings', option_name: 'oymuap_wpforms_formid_settings');

        $this->settings[] = new Utility_Includes\App_Setting(id: 'registration', title: 'Registration Form ID', property: 'registration', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'start', title: 'Start Form ID', property: 'start', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'household', title: 'Household Form ID', property: 'household', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'demographics', title: 'Demographics Form ID', property: 'demographics', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'income', title: 'Income Form ID', property: 'income', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'energy', title: 'Energy Form ID', property: 'energy', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing', title: 'Housing Form ID', property: 'housing', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'completion', title: 'Completion Form ID', property: 'completion', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_household_member', title: 'Add Household Member Form ID', property: 'add_household_member', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_ece', title: 'Add Ece Form ID', property: 'add_ece', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_food', title: 'Add Food Form ID', property: 'add_food', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_energy', title: 'Add Energy Form ID', property: 'add_energy', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_housing', title: 'Add Housing Form ID', property: 'add_housing', type: 'wpforms_dropdown', required: true);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'add_weatherization', title: 'Add Weatherization Form ID', property: 'add_weatherization', type: 'wpforms_dropdown', required: true);
        
		$this->set_property_values($this);  

        $this->form_ids[] = $this->registration;
        $this->form_ids[] = $this->start;
        $this->form_ids[] = $this->household;
        $this->form_ids[] = $this->demographics;
        $this->form_ids[] = $this->income;
        $this->form_ids[] = $this->energy;
        $this->form_ids[] = $this->housing;
        $this->form_ids[] = $this->completion;
        $this->form_ids[] = $this->add_household_member;
    }
}

Class Uap_Wpforms_Registration_Form_Settings extends Utility_Includes\App_Settings {
    Public $user_application_guid_dropdown;
    Public $form_id = 0;
    Public $user_application_guid;
    Public $name;
    Public $gender;
    Public $email;
    Public $home_address;
    Public $mailing_address;
    Public $home_phone;
    Public $mobile_phone;
    Public $dob;
    Public $age;
    Public $default_program;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_registration_form_settings', option_name: 'oymuap_wpforms_registration_form_settings');
        
        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->registration;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid_dropdown', title: 'User Application GUID Field ID', property: 'user_application_guid_dropdown', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'name', title: 'Name Field ID', property: 'name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'gender', title: 'Gender Field ID', property: 'gender', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'email', title: 'Email Field ID', property: 'email', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'home_address', title: 'Home Address Field ID', property: 'home_address', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'mailing_address', title: 'Mailing Address Field ID', property: 'mailing_address', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'home_phone', title: 'Home Phone Field ID', property: 'home_phone', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'mobile_phone', title: 'Mobile Phone Field ID', property: 'mobile_phone', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'dob', title: 'DOB Field ID', property: 'dob', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'age', title: 'Age Field ID', property: 'age', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'default_program', title: 'Default Program Field ID', property: 'default_program', type: 'wpforms_fields_dropdown', required: true, args: $args);
        
		$this->set_property_values($this);  
    }

 
}

Class Uap_Wpforms_Start_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $program_energy_formula;
    Public $program_food_formula;
    Public $program_housing_formula;
    Public $program_ece_formula;
    Public $program_snap_formula;
    Public $total_household_users;
    Public $household_members;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_start_form_settings', option_name: 'oymuap_wpforms_start_form_settings');
        
        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->start;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'program_energy_formula', title: 'Energy Program Formula Field ID', property: 'program_energy_formula', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'program_food_formula', title: 'Food Program Formula Field ID', property: 'program_food_formula', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'program_housing_formula', title: 'Housing Program Formula Field ID', property: 'program_housing_formula', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'program_ece_formula', title: 'ECE Program Formula Field ID', property: 'program_ece_formula', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'program_snap_formula', title: 'SNAP Program Formula Field ID', property: 'program_snap_formula', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'total_household_users', title: 'Total Household Users Field ID', property: 'total_household_users', type: 'wpforms_fields_dropdown', required: true, args: $args);

		$this->set_property_values($this);  
        
        $this->household_members = 
        array(
            array(
                'household_rank' => 2,
                'name' => 3,
                'gender' => 18,
                'dob' => 4,
                'age' => 17,
                'mobile_phone' => 19,
                'relationship' => 145
            ),
            array(
                'household_rank' => 3,
                'name' => 24,
                'gender' => 26,
                'dob' => 27,
                'age' => 28,
                'mobile_phone' => 30,
                'relationship' => 146
            ),
            array(
                'household_rank' => 4,
                'name' => 34,
                'gender' => 35,
                'dob' => 36,
                'age' => 37,
                'mobile_phone' => 39,
                'relationship' => 147
            ),
            array(
                'household_rank' => 5,
                'name' => 41,
                'gender' => 42,
                'dob' => 43,
                'age' => 44,
                'mobile_phone' => 46,
                'relationship' => 148
            ),
            array(
                'household_rank' => 6,
                'name' => 95,
                'gender' => 96,
                'dob' => 97,
                'age' => 98,
                'mobile_phone' => 99,
                'relationship' => 149
            ),
            array(
                'household_rank' => 7,
                'name' => 101,
                'gender' => 102,
                'dob' => 103,
                'age' => 104,
                'mobile_phone' => 105,
                'relationship' => 150
            ),
            array(
                'household_rank' => 8,
                'name' => 107,
                'gender' => 108,
                'dob' => 109,
                'age' => 110,
                'mobile_phone' => 111,
                'relationship' => 151
            ),
            array(
                'household_rank' => 9,
                'name' => 113,
                'gender' => 114,
                'dob' => 115,
                'age' => 116,
                'mobile_phone' => 117,
                'relationship' => 152
            ),
        );
    }

}

Class Uap_Wpforms_Household_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
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

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_household_form_settings', option_name: 'oymuap_wpforms_household_form_settings');
        
        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->household;
        $args = array(
            "form_id"=>$this->form_id,
        );
        
        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing_owner_info', title: 'Housing Owner Info Field ID', property: 'housing_owner_info', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing_owner_info_other', title: 'Housing Owner Info Other Field ID', property: 'housing_owner_info_other', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'landlord_apartment_name', title: 'Landlord Apartment Name Field ID', property: 'landlord_apartment_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'landlord_phone', title: 'Landlord Phone Field ID', property: 'landlord_phone', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'landlord_email', title: 'Landlord Email Field ID', property: 'landlord_email', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing_type_info', title: 'Housing Type Info Field ID', property: 'housing_type_info', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing_type_info_other', title: 'Housing Type Info Other Field ID', property: 'housing_type_info_other', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'section_eight_mbq', title: 'Section Eight Mbq Field ID', property: 'section_eight_mbq', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'utility_allowance', title: 'Utility Allowance Field ID', property: 'utility_allowance', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'family_type', title: 'Family Type Field ID', property: 'family_type', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'family_type_other', title: 'Family Type Other Field ID', property: 'family_type_other', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'primary_household_language', title: 'Primary Household Language Field ID', property: 'primary_household_language', type: 'wpforms_fields_dropdown', required: true, args: $args);


		$this->set_property_values($this);   
    }


}

Class Uap_Wpforms_Demographic_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $is_last_user;
    Public $age;
    Public $current_user_id;
    Public $citizenship;
    Public $race;
    Public $other_race;
    Public $ethnicity;
    Public $primary_language;
    Public $other_language;
    Public $completed_education;
    Public $job_status;
    Public $school_or_training;
    Public $school_or_training_name;
    Public $military_status;
    Public $marital_status;
    Public $is_disabled;
    Public $health_insurance;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_demographic_form_settings', option_name: 'oymuap_wpforms_demographic_form_settings');
        
        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->demographics;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'is_last_user', title: 'Is Last User Field ID', property: 'is_last_user', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'age', title: 'Age Field ID', property: 'age', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'current_user_id', title: 'Current User Id Field ID', property: 'current_user_id', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'citizenship', title: 'Citizenship Field ID', property: 'citizenship', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'race', title: 'Race Field ID', property: 'race', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'other_race', title: 'Other Race Field ID', property: 'other_race', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'ethnicity', title: 'Ethnicity Field ID', property: 'ethnicity', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'primary_language', title: 'Primary Language Field ID', property: 'primary_language', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'other_language', title: 'Other Language Field ID', property: 'other_language', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'completed_education', title: 'Completed Education Field ID', property: 'completed_education', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'job_status', title: 'Job Status Field ID', property: 'job_status', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'school_or_training', title: 'School Or Training Field ID', property: 'school_or_training', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'school_or_training_name', title: 'School Or Training Name Field ID', property: 'school_or_training_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'military_status', title: 'Military Status Field ID', property: 'military_status', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'marital_status', title: 'Marital Status Field ID', property: 'marital_status', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'is_disabled', title: 'Is Disabled Field ID', property: 'is_disabled', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'health_insurance', title: 'Health Insurance Field ID', property: 'health_insurance', type: 'wpforms_fields_dropdown', required: true, args: $args);


		$this->set_property_values($this);   
    }


}

Class Uap_Wpforms_Income_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $income_last_thirty;
    Public $employment_sources;
    Public $full_time_job;
    Public $part_time_job;
    Public $paid_in_cash;
    Public $self_employed;
    Public $other_sources;
    Public $short_term_disability;
    Public $long_term_disability;
    Public $veteran_benefits;
    Public $child_support_alimony;
    Public $pension;
    Public $social_security;
    Public $tanf;
    Public $unemployment;
    Public $workers_comp;
    Public $non_cash_benefits;
    Public $snap_expiration;
    Public $interested_in_snap;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_income_form_settings', option_name: 'oymuap_wpforms_income_form_settings');
        
        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->income;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'income_last_thirty', title: 'Income Last Thirty Field ID', property: 'income_last_thirty', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'employment_sources', title: 'Employment Sources Field ID', property: 'employment_sources', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'full_time_job', title: 'Full Time Job Field ID', property: 'full_time_job', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'part_time_job', title: 'Part Time Job Field ID', property: 'part_time_job', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'paid_in_cash', title: 'Paid In Cash Field ID', property: 'paid_in_cash', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'self_employed', title: 'Self Employed Field ID', property: 'self_employed', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'other_sources', title: 'Other Sources Field ID', property: 'other_sources', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'short_term_disability', title: 'Short Term Disability Field ID', property: 'short_term_disability', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'long_term_disability', title: 'Long Term Disability Field ID', property: 'long_term_disability', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'veteran_benefits', title: 'Veteran Benefits Field ID', property: 'veteran_benefits', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'child_support_alimony', title: 'Child Support Alimony Field ID', property: 'child_support_alimony', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'pension', title: 'Pension Field ID', property: 'pension', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'social_security', title: 'Social Security Field ID', property: 'social_security', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'tanf', title: 'Tanf Field ID', property: 'tanf', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'unemployment', title: 'Unemployment Field ID', property: 'unemployment', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'workers_comp', title: 'Workers Comp Field ID', property: 'workers_comp', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'non_cash_benefits', title: 'Non Cash Benefits Field ID', property: 'non_cash_benefits', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'snap_expiration', title: 'Snap Expiration Field ID', property: 'snap_expiration', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'interested_in_snap', title: 'Interested In Snap Field ID', property: 'interested_in_snap', type: 'wpforms_fields_dropdown', required: true, args: $args);


		$this->set_property_values($this);   
    }

}

Class Uap_Wpforms_Energy_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
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
    Public $is_add_on;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_energy_form_settings', option_name: 'oymuap_wpforms_energy_form_settings');

        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->energy;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'crisis', title: 'Crisis Field ID', property: 'crisis', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'electric_off', title: 'Electric Off Field ID', property: 'electric_off', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'eusp', title: 'Eusp Field ID', property: 'eusp', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'electric_company_name', title: 'Electric Company Name Field ID', property: 'electric_company_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'electric_company_bill_name', title: 'Electric Company Bill Name Field ID', property: 'electric_company_bill_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'electric_company_account', title: 'Electric Company Account Field ID', property: 'electric_company_account', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'heating_off', title: 'Heating Off Field ID', property: 'heating_off', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'meap', title: 'Meap Field ID', property: 'meap', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'main_heating_source', title: 'Main Heating Source Field ID', property: 'main_heating_source', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'heating_company_name', title: 'Heating Company Name Field ID', property: 'heating_company_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'heating_company_bill_name', title: 'Heating Company Bill Name Field ID', property: 'heating_company_bill_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'heating_company_account', title: 'Heating Company Account Field ID', property: 'heating_company_account', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'uspp', title: 'Uspp Field ID', property: 'uspp', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'ara', title: 'Ara Field ID', property: 'ara', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'gara', title: 'Gara Field ID', property: 'gara', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'dhcd', title: 'Dhcd Field ID', property: 'dhcd', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'is_add_on', title: 'Is Add On Field ID', property: 'is_add_on', type: 'wpforms_fields_dropdown', required: true, args: $args);


		$this->set_property_values($this);   
    }

}

Class Uap_Wpforms_Housing_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $cac_assistance_last_thirty;
    Public $owe_landlord_currently;
    Public $contribute_to_owed_rent;
    Public $federal_state_assistance;
    Public $steady_income;
    Public $income_equal_rent;
    Public $crisis;
    Public $eviction_soon;
    Public $is_add_on;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_housing_form_settings', option_name: 'oymuap_wpforms_housing_form_settings');

        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->housing;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'cac_assistance_last_thirty', title: 'Cac Assistance Last Thirty Field ID', property: 'cac_assistance_last_thirty', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'owe_landlord_currently', title: 'Owe Landlord Currently Field ID', property: 'owe_landlord_currently', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'contribute_to_owed_rent', title: 'Contribute To Owed Rent Field ID', property: 'contribute_to_owed_rent', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'federal_state_assistance', title: 'Federal State Assistance Field ID', property: 'federal_state_assistance', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'steady_income', title: 'Steady Income Field ID', property: 'steady_income', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'income_equal_rent', title: 'Income Equal Rent Field ID', property: 'income_equal_rent', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'crisis', title: 'Crisis Field ID', property: 'crisis', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'eviction_soon', title: 'Eviction Soon Field ID', property: 'eviction_soon', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'is_add_on', title: 'Is Add On Field ID', property: 'is_add_on', type: 'wpforms_fields_dropdown', required: true, args: $args);

		$this->set_property_values($this);   
    }

}

Class Uap_Wpforms_Completion_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $hear_about_cac;
    Public $application_certification;
    Public $signature_certification;
    Public $typed_name;
    Public $signature;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_completion_form_settings', option_name: 'oymuap_wpforms_completion_form_settings');

        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->completion;
        $args = array(
            "form_id"=>$this->form_id,
        );

        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'hear_about_cac', title: 'Hear About Cac Field ID', property: 'hear_about_cac', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'application_certification', title: 'Application Certification Field ID', property: 'application_certification', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'signature_certification', title: 'Signature Certification Field ID', property: 'signature_certification', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'typed_name', title: 'Typed Name Field ID', property: 'typed_name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'signature', title: 'Signature Field ID', property: 'signature', type: 'wpforms_fields_dropdown', required: true, args: $args);

		$this->set_property_values($this);   
    }
}

Class Uap_Wpforms_Add_Household_Member_Form_Settings extends Utility_Includes\App_Settings {
    Public $form_id = 0;
    Public $user_application_guid;
    Public $name;
    Public $gender;
    Public $dob;
    Public $age;
    Public $mobile_phone;

    function __construct() {
        parent::__construct(option_group: 'oymuap_wpforms_add_household_member_form_settings', option_name: 'oymuap_wpforms_add_household_member_form_settings');

        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
		$this->form_id = $uap_wpforms_form_ids_settings->add_household_member;
        $args = array(
            "form_id"=>$this->form_id,
        );
    
        $this->settings[] = new Utility_Includes\App_Setting(id: 'user_application_guid', title: 'User Application GUID Field ID', property: 'user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'name', title: 'Name Field ID', property: 'name', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'gender', title: 'Gender Field ID', property: 'gender', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'dob', title: 'Dob Field ID', property: 'dob', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'age', title: 'Age Field ID', property: 'age', type: 'wpforms_fields_dropdown', required: true, args: $args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'mobile_phone', title: 'Mobile Phone Field ID', property: 'mobile_phone', type: 'wpforms_fields_dropdown', required: true, args: $args);

		$this->set_property_values($this);   
    }

}

Class Uap_Program_Add_Settings extends Utility_Includes\App_Settings {
    Public $ece_user_application_guid;
    Public $food_user_application_guid;
    Public $energy_user_application_guid;
    Public $housing_user_application_guid;
    Public $weatherization_user_application_guid;

    function __construct() {
        parent::__construct(option_group: 'oymuap_program_add_settings', option_name: 'oymuap_program_add_settings');

        $uap_wpforms_form_ids_settings = new Uap_Wpforms_Form_Ids_Settings(); 
	
        $ece_args = array(
            "form_id"=>$uap_wpforms_form_ids_settings->add_ece,
        );
        $food_args = array(
            "form_id"=>$uap_wpforms_form_ids_settings->add_food,
        );
        $energy_args = array(
            "form_id"=>$uap_wpforms_form_ids_settings->add_energy,
        );
        $housing_args = array(
            "form_id"=>$uap_wpforms_form_ids_settings->add_housing,
        );
        $weatherization_args = array(
            "form_id"=>$uap_wpforms_form_ids_settings->add_weatherization,
        );


        $this->settings[] = new Utility_Includes\App_Setting(id: 'ece_user_application_guid', title: 'ECE User Application GUID Field ID', property: 'ece_user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $ece_args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'food_user_application_guid', title: 'Food User Application GUID Field ID', property: 'food_user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $food_args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'energy_user_application_guid', title: 'Energy User Application GUID Field ID', property: 'energy_user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $energy_args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'housing_user_application_guid', title: 'Housing Application GUID Field ID', property: 'housing_user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $housing_args);
        $this->settings[] = new Utility_Includes\App_Setting(id: 'weatherization_user_application_guid', title: 'Weatherization User Application GUID Field ID', property: 'weatherization_user_application_guid', type: 'wpforms_fields_dropdown', required: true, args: $weatherization_args);


		$this->set_property_values($this);   
    }

}

