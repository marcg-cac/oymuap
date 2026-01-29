<?php
namespace Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;
use Oym\Uap\Admin\Pages;

Class Uap_Program_Settings extends Utility_Includes\App_Settings {
    public $energy;
    public $food;
    public $housing;
    public $ece;
    public $snap;

    function __construct() {
        parent::__construct(option_group: 'oymuap_program_settings', option_name: 'oymuap_program_settings');
        
		$this->settings[] = new Utility_Includes\App_Setting(id: "energy", title: "Energy Program", property: "energy", type: "boolean_radio", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "food", title: "Food Program", property: "food", type: "boolean_radio", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "housing", title: "Housing Program", property: "housing", type: "boolean_radio", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "ece", title: "ECE Program", property: "ece", type: "boolean_radio", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "snap", title: "Snap Program", property: "snap", type: "boolean_radio", required: true);

		$this->set_property_values($this);   
    }
}

Class Uap_Pdf_Settings extends Utility_Includes\App_Settings {
	public $page_subtitle_font = '';
    public $page_subtitle_font_size;
    public $page_subtitle_font_style = '';

    function __construct() {
        parent::__construct(option_group: 'oymuap_pdf_settings', option_name: 'oymuap_pdf_settings');
        
		$this->settings[] = new Utility_Includes\App_Setting(id: "page_subtitle_font", title: "Page Subtitle Font", property: "page_subtitle_font", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "page_subtitle_font_size", title: "Page Subtitle Font Size", property: "page_subtitle_font_size", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "page_subtitle_font_style", title: "Page Subtitle Font Style", property: "page_subtitle_font_style", type: "text", required: true);

        $this->set_property_values($this);   
    }
}

Class Uap_Url_Settings extends Utility_Includes\App_Settings {
    public $family_portal = '';
    public $pdf_page = '';
    public $application_start = '';
    public $application_household = '';
    public $application_demographics = '';
    public $application_income = '';
    public $application_energy = '';
    public $application_housing = '';
    public $application_completion = '';
    public $application_continuation = '';

    function __construct() {
        parent::__construct(option_group: 'oymuap_url_settings', option_name: 'oymuap_url_settings');
        
		$this->settings[] = new Utility_Includes\App_Setting(id: "family_portal", title: "Family Portal URL", property: "family_portal", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "pdf_page", title: "PDF Page URL", property: "pdf_page", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_start", title: "Application Start URL", property: "application_start", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_household", title: "Application Household URL", property: "application_household", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_demographics", title: "Application Demographics URL", property: "application_demographics", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_income", title: "Application Income URL", property: "application_income", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_energy", title: "Application Energy URL", property: "application_energy", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_housing", title: "Application Housing URL", property: "application_housing", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_completion", title: "Application Completion URL", property: "application_completion", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "application_continuation", title: "Application Continuation URL", property: "application_continuation", type: "text", required: true);

        $this->set_property_values($this);    
    }

    public function get_full_url($prop){
        return OYMUAP_SITE_URL . $this->{$prop};  
    }
}

Class Uap_File_Settings extends Utility_Includes\App_Settings {
    public $supplement_file_path = '';
    public $ece_supplement = '';
    public $food_supplement = '';
    public $energy_supplement = '';
    public $housing_supplement = '';

    function __construct() {
        parent::__construct(option_group: 'oymuap_file_settings', option_name: 'oymuap_file_settings');
        
        $this->settings[] = new Utility_Includes\App_Setting(id: "supplement_file_path", title: "Supplement File Path", property: "supplement_file_path", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "ece_supplement", title: "ECE Supplement File Name", property: "ece_supplement", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "food_supplement", title: "Food Supplement File Name", property: "food_supplement", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "energy_supplement", title: "Energy Supplement File Name", property: "energy_supplement", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "housing_supplement", title: "Housing Supplement File Name", property: "housing_supplement", type: "text", required: true);
	
        $this->set_property_values($this);    
    }
}

Class Uap_Email_Settings extends Utility_Includes\App_Settings {
    public $from_email = '';
    public $from_name = '';
    public $assistance_to_email = '';
    public $assistance_to_name = '';

    function __construct() {
        parent::__construct(option_group: 'oymuap_email_settings', option_name: 'oymuap_email_settings');
        
		$this->settings[] = new Utility_Includes\App_Setting(id: "from_email", title: "From Email", property: "from_email", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "from_name", title: "From Name", property: "from_name", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "assistance_to_email", title: "Assistance To Email", property: "assistance_to_email", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "assistance_to_name", title: "Assistance To  Name", property: "assistance_to_name", type: "text", required: true);

        $this->set_property_values($this);
    }
}

Class Uap_Debug_Settings extends Utility_Includes\App_Settings {
    public $override_on = '';
    public $to_email_override = '';
    public $assistance_to_email_override = '';
    public $to_emails = [];
    public $assistance_to_emails = [];

    function __construct() {
		parent::__construct(option_group: 'oymuap_debug_settings', option_name: 'oymuap_debug_settings');

		$this->settings[] = new Utility_Includes\App_Setting(id: "override_on", title: "Override On", property: "override_on", type: "boolean_radio", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "to_email_override", title: "Volunteer To Email Override", property: "to_email_override", type: "text", required: true);
		$this->settings[] = new Utility_Includes\App_Setting(id: "assistance_to_email_override", title: "Assistance To Email Override", property: "assistance_to_email_override", type: "text", required: true);

        $this->set_property_values($this);
		$this->set_emails();
    }

	public function set_emails(){
        $to_emails = explode(",", $this->to_email_override);
        foreach ($to_emails as $email){
            $new_email = new Uap_Email($email, $email);
            $this->to_emails[] = $new_email;
        }

        $assistance_to_emails = explode(",", $this->assistance_to_email_override);
        foreach ($assistance_to_emails as $email){
            $new_email = new Uap_Email($email, $email);
            $this->assistance_to_emails[] = $new_email;
        }
    }

}

