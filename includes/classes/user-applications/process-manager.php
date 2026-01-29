<?php
namespace Oym\Uap\Includes;
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/user-applications/user-application-pdf.php';
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/email/notifications.php';
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

Class Process_Manager{
    Private $data;
    Private $oym_utils;
    Private $oym_wp_user_manager;
    Private $oym_date_manager;
    Public  $current_user;
    Private $registered_user_id;
    Private $user_application_manager;
    Private $application_id;
    Private $user_application_guid;
    Private $user_application_pdf;

    Private $registration_form_settings;
    Private $start_form_settings;
    Private $household_form_settings;
    Private $demographic_form_settings;
    Private $income_form_settings;
    Private $energy_form_settings;
    Private $housing_form_settings;
    Private $completion_form_settings;
    Private $add_household_member_form_settings;
    
    

    function __construct() {
        $this->oym_wp_user_manager = new Utility_Includes\Oym_Wp_User_Manager();
        $this->oym_date_manager = new Utility_Includes\Date_Manager();
        $this->oym_utils = new Utility_Includes\Oym_Utility_Functions();
        $this->data = new Includes\Data();

        $this->registration_form_settings = new Includes\Uap_Wpforms_Registration_Form_Settings(); 
        $this->start_form_settings = new Includes\Uap_Wpforms_Start_Form_Settings(); 
        $this->household_form_settings = new Includes\Uap_Wpforms_Household_Form_Settings(); 
        $this->demographic_form_settings = new Includes\Uap_Wpforms_Demographic_Form_Settings(); 
        $this->income_form_settings = new Includes\Uap_Wpforms_Income_Form_Settings(); 
        $this->energy_form_settings = new Includes\Uap_Wpforms_Energy_Form_Settings(); 
        $this->housing_form_settings = new Includes\Uap_Wpforms_Housing_Form_Settings(); 
        $this->completion_form_settings = new Includes\Uap_Wpforms_Completion_Form_Settings(); 
        $this->add_household_member_form_settings = new Includes\Uap_Wpforms_Add_Household_Member_Form_Settings(); 
        
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();

        add_action( 'plugins_loaded', array( $this, 'init' ) );
    }

    Public function init(){
        $this->current_user = \wp_get_current_user();
    }

    #region form submit functions
    Public function uap_registration_form_submit($entry_id, $fields){
        $this->user_application_guid = $fields[$this->registration_form_settings->user_application_guid][ 'value' ];
        $user_email = $fields[$this->registration_form_settings->email][ 'value' ];
        $this->oym_wp_user_manager->log_user_in($user_email);

        $this->current_user = \wp_get_current_user();
        $this->registered_user_id = $this->create_new_registered_user($entry_id, $fields); // create the registered user

        //START create the user application and update 
        $this->application_id = $this->create_user_application($entry_id, $fields);

        $data = array(
                'application_id' => $this->application_id,
        );
        $where = array('ID' => $this->registered_user_id);
        $this->data->dbUpdate_user($data, $where);

        //END 
        
        $this->set_current_user($this->registered_user_id);
        $this->update_user_application_status("registered", $this->user_application_guid);
  
    }

    Public function uap_start_form_submit($entry_id, $fields){
        $this->user_application_guid = $fields[$this->start_form_settings->user_application_guid][ 'value' ];
        $this->store_user_application_guid($this->user_application_guid);

        $this->add_additional_users($entry_id, $fields);
        $this->add_programs_to_application($entry_id, $fields);
        $this->update_user_application_status("start_completed", $this->user_application_guid);
    }

    Public function uap_household_form_submit($entry_id, $fields){
        $this->user_application_guid = $fields[$this->household_form_settings->user_application_guid][ 'value' ];
        $this->store_user_application_guid($this->user_application_guid);
        $this->add_household_to_application($entry_id, $fields);
        $this->update_user_application_status("household_completed", $this->user_application_guid);
    }

    Public function uap_demographics_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->demographic_form_settings->user_application_guid][ 'value' ]);

        $age = $fields[$this->demographic_form_settings->age][ 'value' ];
        $this->add_demographics_to_user($entry_id, $fields);

        if ($age < 16){
           //$this->set_current_user_as_complete($this->user_application_guid);
           $next_user_id = $this->set_current_user_as_complete($this->user_application_guid);
           
            $status = ($next_user_id == 0) ? "users_completed" : "current_user_completed";
            $this->update_user_application_status($status, $this->user_application_guid);

            //$this->update_user_application_status("current_user_completed", $this->user_application_guid);
        } else {
            $this->update_user_application_status("current_user_demographics_completed", $this->user_application_guid);
        }
    }

    Public function uap_income_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->income_form_settings->user_application_guid][ 'value' ]);
        $insert_return = $this->add_income_to_user($entry_id, $fields);
        $next_user_id = $this->set_current_user_as_complete($this->user_application_guid);

        $status = ($next_user_id == 0) ? "users_completed" : "current_user_completed";
        $this->update_user_application_status($status, $this->user_application_guid);
    }

    Public function uap_energy_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->energy_form_settings->user_application_guid][ 'value' ]);
        $is_add_on = $fields[$this->energy_form_settings->is_add_on][ 'value' ];
        $this->add_energy_to_application($entry_id, $fields);
        if ($is_add_on){
            $program = new Uap_User_Application_Program("energy", "Energy");
            $this->uap_add_program_form_submit($entry_id, $fields, $program);
        } else {
            $this->update_user_application_status("energy_completed", $this->user_application_guid);
        }
    }

    Public function uap_housing_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->housing_form_settings->user_application_guid][ 'value' ]);
        $is_add_on = $fields[$this->housing_form_settings->is_add_on][ 'value' ];
        $this->add_housing_to_application($entry_id, $fields);
        if ($is_add_on){
            $program = new Uap_User_Application_Program("housing", "Housing");
            $this->uap_add_program_form_submit($entry_id, $fields, $program);
        } else {
            $this->update_user_application_status("housing_completed", $this->user_application_guid);
        }
    }

    

    Public function uap_completion_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->completion_form_settings->user_application_guid][ 'value' ]);
        $this->add_completion_to_application($entry_id, $fields);
        
        $user_application = $this->user_application_manager->get_user_application($this->user_application_guid);

        $this->user_application_pdf = new \User_Application_Pdf($this->user_application_guid);
        $this->user_application_pdf->create_pdf();

        $uap_notification = new Uap_Notifications();
        $uap_notification->uap_completion_notifications($this->user_application_guid);
        $this->update_user_application_status("completed", $this->user_application_guid);
    }

    Public function uap_add_household_member_form_submit($entry_id, $fields){
        $this->store_user_application_guid($fields[$this->add_household_member_form_settings->user_application_guid][ 'value' ]);
        $data = array(
            'application_id' => $this->application_id,
            'name' => $fields[$this->add_household_member_form_settings->name][ 'value' ],
            'gender' => $fields[$this->add_household_member_form_settings->gender][ 'value' ],
            'dob' => $fields[$this->add_household_member_form_settings->dob][ 'value' ],
            'age' => $fields[$this->add_household_member_form_settings->age][ 'value' ],
            'home_phone' => '',
            'mobile_phone' => $fields[$this->add_household_member_form_settings->mobile_phone][ 'value' ],
            'email' => '',
            'hoh' => 0,
            'created' => $this->oym_date_manager->create_date(),
        );
    }

    Public function uap_add_program_form_submit($entry_id, $fields, $program){
        if (in_array($program->short_name, array('ece', 'food', 'energy', 'housing', 'snap', 'weatherization'))){
            $wp_user = wp_get_current_user();
            $user_application_manager = new Includes\Uap_User_Application_Manager();
            $current_user_application = $user_application_manager->get_current_user_application_by_wp_user($wp_user);
            $application_json_obj = json_decode($current_user_application->application_json);
            $this->store_user_application_guid($current_user_application->user_application_guid);

            if (! in_array($program->short_name, array_column($current_user_application->programs_arr, "short_name"))){       
                $application_json_obj->programs[] = $program;
                $this->update_programs_in_application($application_json_obj);

                $this->user_application_pdf = new \User_Application_Pdf($this->user_application_guid);
                $this->user_application_pdf->create_pdf();
                
                if ($program->short_name <> "weatherization"){
                    $uap_notification = new Uap_Notifications();
                    $uap_notification->uap_add_program_notifications($this->user_application_guid, $program);
                }
            }
        }
    }  

    #endregion

    #region EDIT

    Public function create_new_registered_user($entry_id, $fields){
        $user = new Includes\Uap_User();
        $home_address = $fields[$this->registration_form_settings->home_address][ 'value' ];
        $mailing_address = $fields[$this->registration_form_settings->mailing_address][ 'value' ];
        $user_addresses = $this->create_user_addresses($home_address, $mailing_address);
 
        $user->name = $fields[$this->registration_form_settings->name][ 'value' ];
        $user->gender = $fields[$this->registration_form_settings->gender][ 'value' ];
        $user->dob = $this->oym_date_manager->create_date($fields[$this->registration_form_settings->dob][ 'value' ]);
        $user->age = $fields[$this->registration_form_settings->age][ 'value' ];
        $user->home_phone = $fields[$this->registration_form_settings->home_phone][ 'value' ];
        $user->mobile_phone = $fields[$this->registration_form_settings->mobile_phone][ 'value' ];
        $user->email = $fields[$this->registration_form_settings->email][ 'value' ];
        $user->address_obj = json_encode($user_addresses);

        return $this->create_registered_user($user);
    }

    Public function create_registered_user($user){
        $this->current_user = wp_get_current_user();

        $user->user_application_guid = $this->user_application_guid;
        $user->wp_user_id = $this->current_user->ID;
        $user->application_id = 0;
        $user->household_rank = 1;
        $user->hoh = 1;
        $user->created = $this->oym_date_manager->create_date();

       return $this->data->dbCreate_user($user);
    }

    Private function update_registered_user_meta($user){
        $date_no_time = date_create($user->dob);
        update_user_meta($this->current_user->ID, 'oymuap_current_user_application_guid', $user->user_application_guid, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_name', $user->name, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_gender', $user->gender, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_dob', date_format($date_no_time,"Y-m-d"), '');
        update_user_meta($this->current_user->ID, 'oymuap_user_age', $user->age, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_home_phone', $user->home_phone, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_mobile_phone', $user->mobile_phone, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_email', $user->email, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_home_address', $home_address, '');
        update_user_meta($this->current_user->ID, 'oymuap_user_mailing_address', $mailing_address, '');

        return;
    }

    Public function create_user_application($entry_id, $fields){
        $user_application = new Includes\Uap_User_Application();

        $email = $fields[$this->registration_form_settings->email][ 'value' ];
        $cleansed_user_email =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $email);
        $application_pdf_name = $cleansed_user_email . '-' . $this->user_application_guid . '-universal_app.pdf';

        $user_application->user_application_guid = $this->user_application_guid;
        $user_application->registered_user_id = $this->registered_user_id;
        $user_application->application_date = $this->oym_date_manager->create_date();
        $current_application_year = $this->data->dbGet_current_application_year();
        $user_application->application_year_id = $current_application_year->id;
        $user_application->application_pdf_name = $application_pdf_name;
        $user_application->status = 'registered';

        return $this->data->dbCreate_user_application($user_application);
    }

    Public function add_programs_to_application($entry_id, $fields){
        $application_json = new Uap_User_Application_Json();
        if ($fields[$this->start_form_settings->program_energy_formula][ 'value' ] == "Yes"){
            $program = new Uap_User_Application_Program("energy", "Energy");
            $application_json->programs[] = $program;            
        }
        if ($fields[$this->start_form_settings->program_food_formula][ 'value' ] == "Yes"){
            $program = new Uap_User_Application_Program("food", "Food");
            $application_json->programs[] = $program;    
        }
        if ($fields[$this->start_form_settings->program_housing_formula][ 'value' ] == "Yes"){
            $program = new Uap_User_Application_Program("housing", "Housing");
            $application_json->programs[] = $program;   
        }
        if ($fields[$this->start_form_settings->program_ece_formula][ 'value' ] == "Yes"){
            $program = new Uap_User_Application_Program("ece", "Early Childhood Education");
            $application_json->programs[] = $program;     
        }
        if ($fields[$this->start_form_settings->program_snap_formula][ 'value' ] == "Yes"){
            $program = new Uap_User_Application_Program("snap", "SNAP");
            $application_json->programs[] = $program;     
        }

        $data = array(
            'application_json' => json_encode($application_json),
        );

        $where = array(
            'ID' => $this->application_id
        );
        $updated = $this->data->current_wpdb->update( $this->data->user_application_table, $data, $where ); 
    }

    Public function add_additional_users($entry_id, $fields){
        $this->current_user = wp_get_current_user();
        foreach ($this->start_form_settings->household_members as $hh_member){
            if (!$fields[$hh_member['name']][ 'value' ] == ''){
                $age = $fields[$hh_member['age']][ 'value' ];
                $user = new Includes\Uap_User();

                $user->user_application_guid = $this->user_application_guid;
                $user->wp_user_id = 0;
                $user->application_id = $this->application_id;
                $user->household_rank = $hh_member['household_rank'];
                $user->name = $fields[$hh_member['name']][ 'value' ];
                $user->gender = $fields[$hh_member['gender']][ 'value' ];
                $user->dob = $this->oym_date_manager->create_date($fields[$hh_member['dob']][ 'value' ]);
                $user->age = $age;
                $user->home_phone = '';
                $user->relationship = $fields[$hh_member['relationship']][ 'value' ];
                $user->mobile_phone = $fields[$hh_member['mobile_phone']][ 'value' ];
                $user->email = '';
                $user->hoh = 0;
                $user->created = $this->oym_date_manager->create_date();
 
                $this->data->dbCreate_user($user);
            }
        }
    }

    Public function add_household_to_application($entry_id, $fields){
        $application_household = new Includes\Uap_Household();

        $application_household->housing_owner_info = $fields[$this->household_form_settings->housing_owner_info][ 'value' ];
        $application_household->housing_owner_info_other = $fields[$this->household_form_settings->housing_owner_info_other][ 'value' ];
        $application_household->landlord_apartment_name = $fields[$this->household_form_settings->landlord_apartment_name][ 'value' ];
        $application_household->landlord_phone = $fields[$this->household_form_settings->landlord_phone][ 'value' ];
        $application_household->landlord_email = $fields[$this->household_form_settings->landlord_email][ 'value' ];
        $application_household->housing_type_info = $fields[$this->household_form_settings->housing_type_info][ 'value' ];
        $application_household->housing_type_info_other = $fields[$this->household_form_settings->housing_type_info_other][ 'value' ];
        $application_household->section_eight_mbq = $fields[$this->household_form_settings->section_eight_mbq][ 'value' ];
        $application_household->utility_allowance = $fields[$this->household_form_settings->utility_allowance][ 'value' ];
        $application_household->family_type = $fields[$this->household_form_settings->family_type][ 'value' ];
        $application_household->family_type_other = $fields[$this->household_form_settings->family_type_other][ 'value' ];
        $application_household->primary_household_language = $fields[$this->household_form_settings->primary_household_language][ 'value' ];
        $application_household->created = $this->oym_date_manager->create_date();

        $application_household_json = json_encode($application_household);

        $data = array(
            'household_json' => $application_household_json,

        );
        $where = array('user_application_guid' => $this->user_application_guid);
        $this->data->dbUpdate_user_application($data, $where);

    }

    Public function add_demographics_to_user($entry_id, $fields){
        $user_demographic = new Includes\Uap_User_Demographics();

        $current_app_user = $this->data->dbGet_current_user_byGuid($this->user_application_guid);
        $user_demographic->user_id = $current_app_user->ID;
        $user_demographic->user_application_guid = $this->user_application_guid;
        $user_demographic->application_id = $this->application_id;
        $user_demographic->citizenship = $fields[$this->demographic_form_settings->citizenship][ 'value' ];
        $user_demographic->race = $fields[$this->demographic_form_settings->race][ 'value' ];
        $user_demographic->other_race = $fields[$this->demographic_form_settings->other_race][ 'value' ];
        $user_demographic->ethnicity = $fields[$this->demographic_form_settings->ethnicity][ 'value' ];
        $user_demographic->primary_language = $fields[$this->demographic_form_settings->primary_language][ 'value' ];
        $user_demographic->other_language = $fields[$this->demographic_form_settings->other_language][ 'value' ];
        $user_demographic->education_schooling = $fields[$this->demographic_form_settings->completed_education][ 'value' ];
        $user_demographic->job_status = $fields[$this->demographic_form_settings->job_status][ 'value' ];
        $user_demographic->school_job_training = $fields[$this->demographic_form_settings->school_or_training][ 'value' ];
        $user_demographic->school_job_training_name =$fields[$this->demographic_form_settings->school_or_training_name][ 'value' ];
        $user_demographic->military_status = $fields[$this->demographic_form_settings->military_status][ 'value' ];
        $user_demographic->marital_status = $fields[$this->demographic_form_settings->marital_status][ 'value' ];
        $user_demographic->disabled = $fields[$this->demographic_form_settings->is_disabled][ 'value' ];
        $user_demographic->insurance_coverage = $fields[$this->demographic_form_settings->health_insurance][ 'value' ];
        $user_demographic->created = $this->oym_date_manager->create_date();

        $user_demographic_json = json_encode($user_demographic);

        $data = array(
            'demographics_json' => $user_demographic_json,

        );
        $where = array('ID' => $current_app_user->ID);
        
        $this->data->dbUpdate_user($data, $where);

    }

    Public function add_income_to_user($entry_id, $fields){
        $current_app_user = $this->data->dbGet_current_user_byGuid($this->user_application_guid);
        $non_cash_benefits = $this->oym_utils->replace_line_breaks($fields[$this->income_form_settings->non_cash_benefits][ 'value' ]);

        $user_income = new Includes\Uap_User_Income();

        $user_income->user_id = $current_app_user->ID;
        $user_income->user_application_guid = $this->user_application_guid;
        $user_income->application_id = $this->application_id;
        $user_income->income_last_thirty = $fields[$this->income_form_settings->income_last_thirty][ 'value' ];
        $user_income->employment_sources = $fields[$this->income_form_settings->employment_sources][ 'value' ];
        $user_income->full_time_job = $fields[$this->income_form_settings->full_time_job][ 'value' ];
        $user_income->part_time_job = $fields[$this->income_form_settings->part_time_job][ 'value' ];
        $user_income->paid_in_cash = $fields[$this->income_form_settings->paid_in_cash][ 'value' ];
        $user_income->self_employed = $fields[$this->income_form_settings->self_employed][ 'value' ];
        $user_income->short_term_disability = $fields[$this->income_form_settings->short_term_disability][ 'value' ];
        $user_income->long_term_disability = $fields[$this->income_form_settings->long_term_disability][ 'value' ];
        $user_income->veteran_benefits = $fields[$this->income_form_settings->veteran_benefits][ 'value' ];
        $user_income->child_support_alimony = $fields[$this->income_form_settings->child_support_alimony][ 'value' ];
        $user_income->pension = $fields[$this->income_form_settings->pension][ 'value' ];
        $user_income->social_security = $fields[$this->income_form_settings->social_security][ 'value' ];
        $user_income->tanf = $fields[$this->income_form_settings->tanf][ 'value' ];
        $user_income->unemployment = $fields[$this->income_form_settings->unemployment][ 'value' ];
        $user_income->workers_comp = $fields[$this->income_form_settings->workers_comp][ 'value' ];
        $user_income->non_cash_benefits = $non_cash_benefits;
        $user_income->created = $this->oym_date_manager->create_date();

        $user_income_json = json_encode($user_income);

        $data = array(
            'income_json' => $user_income_json,

        );
        $where = array('ID' => $current_app_user->ID);
  
        $this->data->dbUpdate_user($data, $where);
    }

    Public function add_energy_to_application($entry_id, $fields){
        $application_energy = new Includes\Uap_Energy();

        $application_energy->crisis = $fields[$this->energy_form_settings->crisis][ 'value' ];
        $application_energy->electric_off = $fields[$this->energy_form_settings->electric_off][ 'value' ];
        $application_energy->eusp = $fields[$this->energy_form_settings->eusp][ 'value' ];
        $application_energy->electric_company_name = $fields[$this->energy_form_settings->electric_company_name][ 'value' ];
        $application_energy->electric_company_bill_name = $fields[$this->energy_form_settings->electric_company_bill_name][ 'value' ];
        $application_energy->electric_company_account = $fields[$this->energy_form_settings->electric_company_account][ 'value' ];
        $application_energy->heating_off = $fields[$this->energy_form_settings->heating_off][ 'value' ];
        $application_energy->meap = $fields[$this->energy_form_settings->meap][ 'value' ];
        $application_energy->main_heating_source = $fields[$this->energy_form_settings->main_heating_source][ 'value' ];
        $application_energy->heating_company_name = $fields[$this->energy_form_settings->heating_company_name][ 'value' ];
        $application_energy->heating_company_bill_name = $fields[$this->energy_form_settings->heating_company_bill_name][ 'value' ];
        $application_energy->heating_company_account = $fields[$this->energy_form_settings->heating_company_account][ 'value' ];
        $application_energy->uspp = $fields[$this->energy_form_settings->uspp][ 'value' ];
        $application_energy->ara = $fields[$this->energy_form_settings->ara][ 'value' ];
        $application_energy->gara = $fields[$this->energy_form_settings->gara][ 'value' ];
        $application_energy->dhcd = $fields[$this->energy_form_settings->dhcd][ 'value' ];
        $application_energy->created = $this->oym_date_manager->create_date();

        $application_energy_json = json_encode($application_energy);

        $data = array(
            'energy_json' => $application_energy_json,

        );
        $where = array('user_application_guid' => $this->user_application_guid);
        $this->data->dbUpdate_user_application($data, $where);

    }

    Public function add_housing_to_application($entry_id, $fields){
        $application_housing = new Includes\Uap_Housing();

        $application_housing->cac_assistance_last_thirty = $fields[$this->housing_form_settings->cac_assistance_last_thirty][ 'value' ];
        $application_housing->owe_landlord_currently = $fields[$this->housing_form_settings->owe_landlord_currently][ 'value' ];
        $application_housing->contribute_to_owed_rent = $fields[$this->housing_form_settings->contribute_to_owed_rent][ 'value' ];
        $application_housing->federal_state_assistance = $fields[$this->housing_form_settings->federal_state_assistance][ 'value' ];
        $application_housing->steady_income = $fields[$this->housing_form_settings->steady_income][ 'value' ];
        $application_housing->income_equal_rent = $fields[$this->housing_form_settings->income_equal_rent][ 'value' ];
        $application_housing->crisis = $fields[$this->housing_form_settings->crisis][ 'value' ];
        $application_housing->eviction_soon = $fields[$this->housing_form_settings->eviction_soon][ 'value' ];
        $application_housing->created = $this->oym_date_manager->create_date();

        $application_housing_json = json_encode($application_housing);

        $data = array(
            'housing_json' => $application_housing_json,

        );
        $where = array('user_application_guid' => $this->user_application_guid);
        $this->data->dbUpdate_user_application($data, $where);

    }

    Public function add_completion_to_application($entry_id, $fields){
        $data = array(
            'hear_about_cac' => $fields[$this->completion_form_settings->hear_about_cac][ 'value' ],
            'application_certification' => $fields[$this->completion_form_settings->application_certification][ 'value' ],
            'signature_certification' => $fields[$this->completion_form_settings->signature_certification][ 'value' ],
            'typed_name' => $fields[$this->completion_form_settings->typed_name][ 'value' ],
            'signature_url' => $fields[$this->completion_form_settings->signature][ 'value' ],
            'status' => 'completed',
        );
        $where = array(
            'ID' => $this->application_id
        );
        $updated = $this->data->current_wpdb->update( $this->data->user_application_table, $data, $where ); 
    }

    #endregion


    #region utility
    
    Private function create_user_addresses($home_address, $mailing_address){
        $user_addresses = new Includes\Uap_User_Addresses();
        $user_addresses->home = $this->create_address($home_address);
        $user_addresses->mailing = (! empty($mailing_address)) ? $this->create_address($mailing_address) : [];

        return $user_addresses;
    }

    Public function create_address($address_data){
        $address = new Includes\Uap_Address();

        $address_parts =  preg_split ('/\n/', $address_data);

        $address_has_street_2 = (count($address_parts) == 4) ? true : false;

        $address->street_1 = $address_parts[0];
        $address->street_2 = ($address_has_street_2) ? $address_parts[1] : '';
        $city_state = ($address_has_street_2) ? explode(',', $address_parts[2]) : explode(',', $address_parts[1]);
        $address->city = $city_state[0];
        $address->state = $city_state[1];
        $address->zip = ($address_has_street_2) ? $address_parts[3] : $address_parts[2];

        return $address;
    }

    Public function update_programs_in_application($application_json){
        $data = array(
            'application_json' => json_encode($application_json),
        );

        $where = array(
            'user_application_guid' => $this->user_application_guid
        );
        $updated = $this->data->current_wpdb->update( $this->data->user_application_table, $data, $where ); 
    }

    Public function store_user_application_guid($user_application_guid){
        $this->user_application_guid = $user_application_guid;
        $this->store_application_id_from_guid();
    }

    Public function store_application_id_from_guid(){
        $user_application = $this->user_application_manager->get_user_application($this->user_application_guid);
        $this->application_id = $user_application->ID;
    }


    Public function update_user_application_status($status, $user_application_guid){
        $data = array('status' => $status,);
        $where = array('user_application_guid' => $user_application_guid);
        $this->data->dbUpdate_user_application($data, $where);
    }

    Public function set_current_user_as_complete($user_application_guid){
        $current_app_user = $this->data->dbGet_current_user_byGuid($this->user_application_guid);
        $table = $this->data->user_table;
        $data = array(
            'completed' => $this->oym_date_manager->create_date()
        );

        $where = array(
            'ID' => $current_app_user->ID
        );
        
        $updated = $this->data->current_wpdb->update( $table, $data, $where ); 

        $next_user_id = $this->data->dbGet_next_user_id_byGuid($user_application_guid);
        $this->set_current_user($next_user_id);

        return ($next_user_id == '') ? 0 : $next_user_id;
    }

    Public function set_current_user($user_id){
        $table = $this->data->user_application_table;
        $data = array(
            'current_app_user' => $user_id
        );

        $where = array(
            'ID' => $this->application_id
        );
        
        $updated = $this->data->current_wpdb->update( $table, $data, $where ); 
    }
    #endregion

}


