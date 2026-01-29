<?php
use Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;

require_once OYMUTILITY_PLUGIN_DIR . 'vendor/fpdf/fpdf.php'; 

class User_Application_Pdf extends OYM_FPDF {
    Public $user_application_manager;
    Public $user_application;
    Public $user_application_obj;
    Public $user_application_guid;

    Public $pdf_filename;
    Public $pdf_filename_file_path;
    Public $pdf_filename_url_path;
    Public $page_message;

    function __construct($user_application_guid = "") {
        parent::__construct();
        //$this->oym_utility = new Utility_Includes\Oym_Utility_Loader();
        $this->user_application_guid = $user_application_guid == "" ? (ISSET($_GET["user_application_guid"]) ? $_GET["user_application_guid"] : "") : $user_application_guid;
        $this->user_application_manager =  new Includes\Uap_User_Application_Manager();
        $this->user_application = $this->user_application_manager->get_user_application($this->user_application_guid);
        
    }

    public function create_pdf(){  
        if ($this->user_application->status == 'completed'){
            $this->set_path_values();

            $this->setAutoPageBreak(true, 10);
            $this->AliasNbPages();
            $this->SetTopMargin(4);
            $this->AddPage();
    
            $this->create_household_page();
            $this->create_user_pages();
            $this->create_household_housing_page();
            $this->create_household_energy_page();
        
            $this->create_signature_page();
    
            $this->write_pdf();
            $this->page_message = "<a href='" . $this->pdf_filename_url_path . "'>Download PDF</a>";
        } else {
            $this->page_message = "This application is not complete.";
        } 
    }

    #region household

    public function create_household_page(){
        $section_fields = array();
        $household = $this->user_application->household_obj;

        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Main Account Name", value: $this->user_application->hoh_obj->name));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Main Account Email", value: $this->user_application->hoh_obj->email));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Application Date", value: $this->user_application->application_date));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "People in Household", value: $this->user_application->user_count));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Programs", value: $this->user_application->programs_text));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Home Address", value: preg_replace('/<br(\s+\/)?>/', "\r\n", $this->user_application->home_address)));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Mailing Address", value: preg_replace('/<br(\s+\/)?>/', "\r\n", $this->user_application->mailing_address)));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Home Phone", value: $this->user_application->hoh_obj->home_phone));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing Ownership Information", value: $household->housing_owner_info));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing Ownership Information Other", value: $household->housing_owner_info_other));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Landlord Name/Apartment Complex", value: $household->landlord_apartment_name));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Landlord Phone Number", value: $household->landlord_phone ));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Landlord Email", value: $household->landlord_email));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing/Residence Type Information", value: empty($household->housing_type_info) ? $household->housing_type_info_other : $household->housing_type_info ));

      //  array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing/Residence Type Information", value: $household->housing_type_info));
      //  array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing/Residence Type Information Other", value: $household->housing_type_info_other));

        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Section 8 or MBQ", value: $household->section_eight_mbq));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Utility Allowance", value: $household->utility_allowance));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Family Type", value: (empty($household->family_type)) ? $household->family_type_other : $household->family_type));

      //array_push($section_fields, new User_Application_Pdf_Field(shortname: "Family Type", value: $household->family_type));
      //array_push($section_fields, new User_Application_Pdf_Field(shortname: "Family Type Other", value: $household->family_type_other));

        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Primary language spoken in Household", value: $household->primary_household_language ?? ""));
        
        $this->create_section("Household Info", $section_fields);
    }

        #region household housing
        public function create_household_housing_page(){
            if (!empty($this->user_application->housing_obj)){
                $this->AddPage();
                $housing = $this->user_application->housing_obj;
                $section_fields = array();

                array_push($section_fields, new User_Application_Pdf_Field(shortname: "CAC Housing Assistance in last 12 months", value: $housing->cac_assistance_last_thirty));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Owed Rent", value: $housing->owe_landlord_currently));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Contribute money to back rent?", value: $housing->contribute_to_owed_rent));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Federal or State Assistance?", value: $housing->federal_state_assistance));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Employed with Income?", value: $housing->steady_income));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Income equal to rent?", value: $housing->income_equal_rent));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Housing Crisis", value: $housing->crisis));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Eviction Less than 72 Hours", value: $housing->eviction_soon));

                $this->create_section("Housing Assistance", $section_fields);
            }
        } 

        #endregion

        #region household energy
        public function create_household_energy_page(){
            if (!empty($this->user_application->energy_obj)){
                $this->AddPage();
                $energy = $this->user_application->energy_obj;
                $section_fields = array();

                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Crisis", value: $energy->crisis));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Is your electric service off?", value: $energy->electric_off));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "EUSP", value: $energy->eusp));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "My electric company is", value: $energy->electric_company_name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Name on Electric Bill", value: $energy->electric_company_bill_name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Account Number", value: $energy->electric_company_account));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Is your heating service off?", value: $energy->heating_off));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "MEAP", value: $energy->meap));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Main Heating Source", value: $energy->main_heating_source));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "My heat supplier or fuel company is", value: $energy->heating_company_name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Name on heating Bill", value: $energy->heating_company_bill_name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Account Number", value: $energy->heating_company_account));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Enroll in USPP", value: $energy->uspp));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Enroll in ARA", value: $energy->ara));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Enroll in GARA", value: $energy->gara));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Enroll in DHCD Efficiency Program", value: $energy->dhcd));

                $this->create_section("Energy Assistance", $section_fields);
            }
        } 

        #endregion
    #endregion

    #region users
    public function create_user_pages(){
        if (!empty($this->user_application->users_arr)){
            foreach ($this->user_application->users_arr as $user){
                $this->AddPage();
                $this->create_page_subtitle("Information for " . $user->name);

                $section_fields = array();

                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Full Name", value: $user->name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Head of Household", value: $this->user_application->hoh_obj->name));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "DOB", value: $user->dob));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Age", value: $user->age));
                if ($user->email <> '') {
                    array_push($section_fields, new User_Application_Pdf_Field(shortname: "Email", value: $user->email));
                }
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Mobile Phone", value: $user->mobile_phone));
                array_push($section_fields, new User_Application_Pdf_Field(shortname: "Gender", value: $user->gender));

                if ($user->relationship <> '') {
                    array_push($section_fields, new User_Application_Pdf_Field(shortname: "Relationship to HOH", value: $user->relationship));
                }

                $this->create_section("Profile Info", $section_fields);

                $this->create_user_demographics($user);
                if (isset($user->income_obj)){
                    $this->create_user_income($user);
                }
      
            }
        }
    }

        #region user demographics
        public function create_user_demographics($user){
            $this->create_user_demographics_citizenship($user);
            $this->create_user_demographics_education($user);
            $this->create_user_demographics_military($user);
        }
    
        public function create_user_demographics_citizenship($user){
            $section_fields = array();
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Citizenship", value: $user->demographics_obj->citizenship));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Race", value: $user->demographics_obj->race));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Other Race", value: $user->demographics_obj->other_race));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Ethnicity", value: $user->demographics_obj->ethnicity));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Primary Language", value: $user->demographics_obj->primary_language));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Other Language", value: $user->demographics_obj->other_language));
            $this->create_section("Citizenship, Race and Ethnicity Information", $section_fields);
        }
    
        public function create_user_demographics_education($user){
            $section_fields = array();
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Completed Education/Schooling", value: $user->demographics_obj->education_schooling));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Job Status", value: $user->demographics_obj->job_status));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "In School or Job Training Program", value: $user->demographics_obj->school_job_training));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Name of School or Training Program", value: $user->demographics_obj->school_job_training_name));
            $this->create_section("Education and Employment Information", $section_fields);
        }
    
        public function create_user_demographics_military($user){
            $section_fields = array();
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Military Status", value: $user->demographics_obj->military_status));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Marital Status", value: $user->demographics_obj->marital_status));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Disabled", value: $user->demographics_obj->disabled));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Health Insurance Coverage", value: $user->demographics_obj->insurance_coverage));
            $this->create_section("Military, Marital and Health Status", $section_fields);
        }
    
        #endregion
    
        #region user income
        public function create_user_income($user){
            $this->create_user_income_employment($user);
            $this->create_user_income_other($user);
        } 
    
        public function create_user_income_employment($user){
            $section_fields = array();
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Any Income Last 30", value: $user->income_obj->income_last_thirty));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Full Time Job", value: $user->income_obj->full_time_job));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Part Time Job", value: $user->income_obj->part_time_job));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Paid in Cash", value: $user->income_obj->paid_in_cash));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Self Employed", value: $user->income_obj->self_employed));
            $this->create_section("Income Sources - Employment", $section_fields);
        }
    
        public function create_user_income_other($user){
            $section_fields = array();
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Other Income Sources", value: $user->income_obj->other_income_sources));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Short Term Disability", value: $user->income_obj->short_term_disability));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Long Term Disability", value: $user->income_obj->long_term_disability));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Veteran Benefits", value: $user->income_obj->veteran_benefits));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Child Support / Alimony", value: $user->income_obj->child_support_alimony));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Pension", value: $user->income_obj->pension));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Social Security", value: $user->income_obj->social_security));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "TANF", value: $user->income_obj->tanf));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Unemployment", value: $user->income_obj->unemployment));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Workers Compensation", value: $user->income_obj->workers_comp));
            array_push($section_fields, new User_Application_Pdf_Field(shortname: "Non-Cash Benefits", value: $user->income_obj->non_cash_benefits));
            $this->create_section("Income Sources - Other", $section_fields);
    
        }
        #endregion
        

    #endregion
    
    #region signature
    public function create_signature_page(){
        $this->AddPage();
        $section_fields = array();
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "How did you hear about us?", value: $this->user_application->hear_about_cac));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Application Certification", value: $this->user_application->application_certification));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Signature Certification", value: $this->user_application->signature_certification));
        array_push($section_fields, new User_Application_Pdf_Field(shortname: "Typed Name", value: $this->user_application->typed_name));
        array_push($section_fields, new User_Application_Pdf_Field(type: "IMAGE", shortname: "Signature", value: $this->user_application->signature_url));
        $this->create_section("Signature", $section_fields);
    }
    #endregion
    
    #region utils
    // Page header
    function Header() {    
        // Logo
        $this->Image(WP_CONTENT_URL . '/uploads/cac-images/horizontal-logo-300x58-6.7.png');
        $this->SetY(6);
        $this->SetFont('Arial','B',13);
        //$this->MultiCell(0,6,"Universal Services Application" ,0,'C');
        $this->Cell(0,10, "Universal Services Application" ,0,2,'R');  
        $this->SetY(12);
        $this->SetFont('Arial','B',8);
        $this->Cell(0,10, "Submitted on " .  $this->user_application->application_date ,0,2,'R');  
    }
    
    // Page footer
    function Footer()     {
        // Position at 1.5 cm from bottom
        $this->SetY(-12);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Community Action Council of Howard County 410-413-6440',0,2,'C');
        $this->SetY(-8);
        $this->Cell(0,10,'Submitted on '.  $this->user_application->application_date ,0,0,'L');
        $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,2,'R');  // Page number
    }
    
    private function set_path_values(){
        $this->pdf_filename_file_path = OYMUAP_PDF_FILE_PATH . $this->user_application->application_pdf_name;
        $this->pdf_filename_url_path = OYMUAP_PDF_URL_PATH . $this->user_application->application_pdf_name;
	}


    private function create_section($title, $section_fields) {
		$this->Ln(4);
		$this->SetFont('Arial','B',12);
		$this->Cell(0,8,strtoupper($title),0,0,'L'); // Title
		$this->Ln(8); 		// Line break
		$this->SetFont('Arial','',10);

        if (!empty($section_fields)){
            foreach ($section_fields as $section_field) {
                if ($section_field->type == "IMAGE"){
                    if (! empty($section_field->value)){
                        $this->Image($section_field->value,null,null,90);
                    }
                } else {
                    $this->SetFont('Arial','B',12);
                    $this->Cell(180,8,$section_field->shortname,0,0,'L');
                    $this->Ln(8);
                    $this->SetFont('Arial','',10);
                        //$this->Cell(180,8,$row[1],0,0,'L');
                    $this->Write(5,$section_field->value);
                    $this->Ln();
                }
            }
        }
	}


    private function create_page_subtitle($message) {
        $this->SetFont('Arial','B',13);
        $this->MultiCell(0,8,$message,0,'C');
	}

    public function write_pdf(){
		$this->Output('F',$this->pdf_filename_file_path);
    }
    #endregion
}
 
class User_Application_Pdf_Field{
    Public $type;
    Public $shortname;
    Public $value;

    function __construct(string $type="TEXT", string $shortname='', string $value='') {
        $this->type = $type;
        $this->shortname = $shortname;
        $this->value = (empty($value)) ? "N/A" : $value;
    }
}

?>