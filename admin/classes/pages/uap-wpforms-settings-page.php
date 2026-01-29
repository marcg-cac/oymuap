<?php
namespace Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

Class Wpforms_Settings_Page extends Utility_Includes\App_Settings_Page {
    Public $uap_wpforms_settings;
    Public $page_name = 'UAP WPForms Settings';
    Public $default_slug = 'wpforms_formid_settings';
    Public $page_slug = 'oymuap-wpf-settings';
    Public $submit_permission = 'oymuap_edit_settings';

    function __construct() {
        $this->active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->default_slug;

        $current_settings_object = match($this->active_tab) {
            'wpforms_formid_settings' => new Includes\Uap_Wpforms_Form_Ids_Settings(),
            'wpforms_registration_form_settings' => new Includes\Uap_Wpforms_Registration_Form_Settings(),
            'wpforms_start_form_settings' => new Includes\Uap_Wpforms_Start_Form_Settings(),
            'wpforms_household_form_settings' => new Includes\Uap_Wpforms_Household_Form_Settings(),
            'wpforms_demographic_form_settings' => new Includes\Uap_Wpforms_Demographic_Form_Settings(),
            'wpforms_income_form_settings' => new Includes\Uap_Wpforms_Income_Form_Settings(),
            'wpforms_energy_form_settings' => new Includes\Uap_Wpforms_Energy_Form_Settings(),
            'wpforms_housing_form_settings' => new Includes\Uap_Wpforms_Housing_Form_Settings(),
            'wpforms_completion_form_settings' => new Includes\Uap_Wpforms_Completion_Form_Settings(),
            'program_add_settings' => new Includes\Uap_Program_Add_Settings(),
            'wpforms_add_household_member_form_settings' => new Includes\Uap_Wpforms_Add_Household_Member_Form_Settings(),
            default => new Includes\Uap_Wpforms_Form_Ids_Settings(),
        };

        $this->current_section_title = match($this->active_tab) {
            'wpforms_formid_settings' => 'WPForms Form ID Settings',
            'wpforms_registration_form_settings' => 'WPForms Registration Form Settings', 
            'wpforms_start_form_settings' => 'WPForms Start Form Settings', 
            'wpforms_household_form_settings' => 'WPForms Household Form Settings', 
            'wpforms_demographic_form_settings' => 'WPForms Demographic Form Settings', 
            'wpforms_income_form_settings' => 'WPForms Income Form Settings', 
            'wpforms_energy_form_settings' => 'WPForms Energy Form Settings', 
            'wpforms_housing_form_settings' => 'WPForms Housing Form Settings', 
            'wpforms_completion_form_settings' => 'WPForms Completion Form Settings', 
            'program_add_settings' => 'WPForms Program Add Settings', 
            'wpforms_add_household_member_form_settings' => 'WPForms Add Household Member Form Settings', 
            default => 'WPForms Form ID Settings',
        };

        parent::__construct(
            settings_object: $current_settings_object, 
            name: $this->page_name,
            default_tab: $this->default_slug, 
            submit_permission: $this->submit_permission
        );

        $this->settings_object->settings_page = $this;
    }

    public function settings_display() {
        $this->settings_init(
			option_group: $this->settings_object->option_group, 
			option_name: $this->settings_object->option_name, 
			section_id: $this->active_tab, 
			title: $this->current_section_title, 
			settings_page: $this, 
			field_callback: 'create_app_setting_field', 
        );

        $this->add_tab(
            slug: 'wpforms_formid_settings', 
            name: 'Form IDs',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_registration_form_settings', 
            name: 'Registration',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_start_form_settings', 
            name: 'Start',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_household_form_settings', 
            name: 'Household',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_demographic_form_settings', 
            name: 'Demographics',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_income_form_settings', 
            name: 'Income',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_energy_form_settings', 
            name: 'Energy',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_housing_form_settings', 
            name: 'Housing',
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_completion_form_settings', 
            name: 'Completion',
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'wpforms_add_household_member_form_settings', 
            name: 'Add Household Member',
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'program_add_settings', 
            name: 'Program Add Settings',
            page_slug: $this->page_slug
        );

        
        $this->display();
    }
}


?>