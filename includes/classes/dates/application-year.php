<?php
namespace Oym\Uap\Includes;
use Oym\Uap\Includes;

Class Application_Year{
    Private $data;
    Public $year_dropdown_html;

    function __construct() {
        $this->data = new Includes\Data();
        $this->year_dropdown_html = $this->build_application_year_dropdown();
    }

    private function build_application_year_dropdown(){
        $years = $this->data->dbGet_application_years();
        $html = '';
        if (!empty($years)){
            foreach ($years as $year) {
                $selected = ($year->current_year == 1) ? 'selected' : '';
                $html .= '<option value="' . $year->id . '" ' . $selected . '>' . $year->name . '</option>';
            }
        }
        return $html;
    }
}


?>