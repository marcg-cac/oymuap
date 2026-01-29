<?php
namespace Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;

Class User_Application_Page{
    Private $data;
    Public $user_application_manager;
    Public $user_application;
    Public $user_application_guid;
    Public $programs_html;

    function __construct() {
        $this->data =  new Includes\Data();	
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();	
        $this->user_application_guid = (ISSET($_GET["user_application_guid"])) ? $_GET["user_application_guid"] : "";
    }

    public function run() {
        $this->user_application = $this->user_application_manager->get_user_application($this->user_application_guid);
        $this->get_application_section_data();
        // $this->build_user_tabs() ;
    }
    
    #region application info
    public function get_application_section_data() {
        $programs = [];
        foreach ($this->user_application->programs_arr as $program){
            $programs[] = $program->short_name;
        }

        $this->programs_html = '<div class="oym-table-cell">ECE</div><div class="oym-table-cell">';
        $this->programs_html .= (in_array("ece", $programs)) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
        $this->programs_html .= '</div>';

        $this->programs_html .= '<div class="oym-table-cell">Energy</div><div class="oym-table-cell">';
        $this->programs_html .= (in_array("energy", $programs)) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
        $this->programs_html .= '</div>';

        $this->programs_html .= '<div class="oym-table-cell">Food</div><div class="oym-table-cell">';
        $this->programs_html .= (in_array("food", $programs)) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
        $this->programs_html .= '</div>';

        $this->programs_html .= '<div class="oym-table-cell">Housing</div><div class="oym-table-cell">';
        $this->programs_html .= (in_array("housing", $programs)) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
        $this->programs_html .= '</div>';

        $this->programs_html .= '<div class="oym-table-cell">SNAP</div><div class="oym-table-cell">';
        $this->programs_html .= (in_array("snap", $programs)) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
        $this->programs_html .= '</div>';

    }

    #endregion

   
    #region users info

    public function build_user_info_section(){
        $html = <<<user_info_section_html_start
            <div id="user-info-section" class="application-section">  
                <div class="oym-section-label">                
                    <span id="user-info-section-expand" class="dashicons dashicons-editor-expand section-expand"></span>
                    <span id="user-info-section-contract" class="dashicons dashicons-editor-contract section-contract oym-hidden"></span>
                    USER INFORMATION
                </div>
                <div id="user-info-section-content" class="section-content oym-hidden">
                    <div class="oym-tabs">
        user_info_section_html_start;

        $html .= $this->build_user_tabs();
        $html .= "</div></div>";

        return $html;
    }

    private function build_user_tabs() {
        $user_tabs_html = '';
        if (!empty($this->user_application->users_arr)){
            foreach ($this->user_application->users_arr as $user) {
                $user_tabs_html .=  '<input type="radio" name="tabs" id="tab' . $user->ID . '"><label for="tab' . $user->ID . '">' . $user->name . '</label>';
                $user_tabs_html .= '<div class="tab-content">';
                $user_tabs_html .= $this->build_user_subtabs($user);
                $user_tabs_html .= '</div>';
            }
        }
        return $user_tabs_html;
    }

    private function build_user_subtabs($user) {
        $html = '';
        if (!empty($user)){          
            $html .= '<div class="oym-subtabs">';

            $html .=  '<input type="radio" name="subtabs" id="user-tab' . $user->ID . '"><label for="user-tab' . $user->ID . '">User Info</label>';
            $html .=  '<div class="subtab-content">';
            $html .=  $this->build_user_info_subtab_content($user);
            $html .= '</div>';

            $html .=  '<input type="radio" name="subtabs" id="demo-tab' . $user->ID . '"><label for="demo-tab' . $user->ID . '">Demographics</label>';
            $html .=  '<div class="subtab-content">';
            $html .=  $this->build_user_demo_subtab_content($user);
            $html .= '</div>';

            
            $html .=  '<input type="radio" name="subtabs" id="income-tab' . $user->ID . '"><label for="income-tab' . $user->ID . '">Income</label>';
            $html .=  '<div class="subtab-content">';
            $html .=  $this->build_user_income_subtab_content($user);
            $html .= '</div>';

            $html .= '</div>';
        }
        return $html;
    }

    private function build_user_info_subtab_content($user) {
        $html = '';
        if (!empty($user)){
            foreach ($user as $key => $value) {
                //if (in_array($key, array("ID", "name", "gender", "dob", "age", "home_phone", "mobile_phone", "email"))){
                if (! in_array($key, array("user_application_guid", "income_obj", "demographics_obj", "address_obj"))){
                    if (! is_object($value)){
                        $html .= '<div>' . $key . ': ' . $value . '</div>';
                    }
                }
            }
        }
        return $html;
    }

    private function build_user_demo_subtab_content($user) {
        $html = '';
        if (!empty($user)){
            if (!empty($user->demographics_obj)){
                foreach ($user->demographics_obj as $key => $value) {
                    if (! in_array($key, array("user_application_guid", "user_id", "application_id", "created"))){
                        if (! is_object($value)){
                            $html .= '<div>' . $key . ': ' . $value . '</div>';
                        }
                    }
                }
            }
        }
        return $html;
    }

    private function build_user_income_subtab_content($user) {
        $html = '';
        if (!empty($user)){
            if (!empty($user->income_obj)){
                foreach ($user->income_obj as $key => $value) {
                    if (! in_array($key, array("user_application_guid", "user_id", "application_id", "created"))){
                        if (! is_object($value)){
                            $html .= '<div>' . $key . ': ' . $value . '</div>';
                        }
                    }
                }
            }
        }
        return $html;
    }

    #endregion

    #region housing info
    public function build_housing_section_content() {
        $html = '';
        if (!empty($this->user_application->housing_obj)){
            $html .=   '<div class="oym-section-label">
            <span id="household-housing-section-expand" class="dashicons dashicons-editor-expand"></span>
            <span id="household-housing-section-contract" class="dashicons dashicons-editor-contract oym-hidden"></span>
             HOUSING INFORMATION</div>';
            $html .=  '<div id="household-housing-section" class="oym-hidden oym-content-section">';
            foreach ($this->user_application->housing_obj as $key => $value) {
                if (! in_array($key, array("household_id", "application_id", "created"))){
                    if (! is_object($value)){
                        $html .= '<div><b>' . $key . ': </b>' . $value . '</div>';
                    }
                }
            }
            $html .= '</div>';
        }
        return $html;
    }

    #endregion

    #region energy info
    public function build_energy_section_content() {
        $html = '';
        if (!empty($this->user_application->energy_obj)){
            $html .=   '<div class="oym-section-label">
            <span id="household-energy-section-expand" class="dashicons dashicons-editor-expand section-expand"></span>
            <span id="household-energy-section-contract" class="dashicons dashicons-editor-contract section-contract oym-hidden"></span>
            ENERGY INFORMATION</div>';
            $html .=  '<div id="household-energy-section" class="oym-hidden oym-content-section">';
            foreach ($this->user_application->energy_obj as $key => $value) {
                if (! in_array($key, array("household_id", "application_id", "created"))){
                    if (! is_object($value)){
                        $html .= '<div><b>' . $key . ': </b>' . $value . '</div>';
                    }
                }
            }
            $html .= '</div>';
        }
        return $html;
    }
    #endregion

}


?>