<?php
namespace Oym\Uap\Admin\Pages;
require_once OYMUAP_PLUGIN_DIR . 'includes/classes/dates/application-year.php';
use Oym\Uap\Includes;

Class User_Applications_Page{
    Public $user_id;
    Public $application_year;
    Public $year_dropdown_html;

    function __construct() {
        $this->user_id = $_GET['user_id'] ?? 0;
        $this->application_year = new Includes\Application_Year();
        $this->year_dropdown_html = $this->application_year->year_dropdown_html;
    }
    
    #endregion

}


?>