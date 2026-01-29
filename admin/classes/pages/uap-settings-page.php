<?php
namespace Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

Class Settings_Page extends Utility_Includes\App_Settings_Page {
    Public $page_name = 'UAP Settings';
    Public $default_slug = 'program_settings';
    Public $page_slug = 'oymuap-settings';
    Public $submit_permission = 'oymuap_edit_settings';

    function __construct() {
        $this->active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : $this->default_slug;

        $current_settings_object = match($this->active_tab) {
            'program_settings' => new Includes\Uap_Program_Settings(),
            'pdf_settings' => new Includes\Uap_Pdf_Settings(),
            'url_settings' => new Includes\Uap_Url_Settings(),
            'file_settings' => new Includes\Uap_File_Settings(),
            'email_settings' => new Includes\Uap_Email_Settings(),
            'debug_settings' => new Includes\Uap_Debug_Settings(),
            default => new Includes\Uap_Program_Settings(),
        };


        $this->current_section_title = match($this->active_tab) {
            'program_settings' => 'Program Settings',
            'pdf_settings' => 'PDF Settings',
            'url_settings' => 'URL Settings',
            'file_settings' => 'File Settings',
            'email_settings' => 'Email Settings',
            'debug_settings' => 'Debug Settings',
            default => 'Program Settings',
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
            slug: 'program_settings', 
            name: 'Program Settings',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'pdf_settings', 
            name: 'PDF Settings',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'url_settings', 
            name: 'URL Settings',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'file_settings', 
            name: 'File Settings',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'email_settings', 
            name: 'Email Settings',  
            page_slug: $this->page_slug
        );

        $this->add_tab(
            slug: 'debug_settings', 
            name: 'Debug Settings',  

            page_slug: $this->page_slug
        );

        $this->display();
    }
}


?>