<?php
namespace Oym\Uap\Includes;
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/user-applications/user-application-pdf.php';
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/email/notifications.php';
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

Class Registration_Form{
    Public $registration_form_settings;

    function __construct($entry_id, $fields ) {
        $this->registration_form_settings = new Includes\Uap_Wpforms_Registration_Form_Settings(); 
        $user_application->user_application_guid = $fields[$this->registration_form_settings->user_application_guid][ 'value' ];
        $user_application->$user_email = $fields[$this->registration_form_settings->email][ 'value' ];
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


}