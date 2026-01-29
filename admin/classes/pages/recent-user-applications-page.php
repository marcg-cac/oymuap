<?php
namespace Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;

Class Recent_User_Applications_Page{
    Private $current_user;
    Public  $user_applications_filter_html;

    function __construct() {
        $allow_pages = array('oymuap-recent-user-applications');
		if (! isset($_GET['page']) || (isset($_GET['page']) && ! in_array($_GET['page'], $allow_pages))     ) {
			return;
		}

        $this->current_user = wp_get_current_user();

        $this->user_applications_filter_html = <<<user_applications_filter_html
        <div class="oym-menu-container" id="oymtt-user-applications-menu-container">
            <div class="oym-flex-row oym-flex-row-center" >
                <div class="oym-flex-row oym-flex-row-left" >
                    <div class="oym-flex-row oym-flex-row-center">
                        <label for="user-application-start-date-filter" class="oym-field-label-inline">From Date</label>
                        <input type="text" id="user-application-start-date-filter" class="oym-date-input" name="user-application-start-date-filter" required>
                    </div>
                    <div class="oym-flex-row oym-flex-row-center">
                        <label for="user-application-end-date-filter" class="oym-field-label-inline">To Date</label> 
                        <input type="text" id="user-application-end-date-filter" class="oym-date-input" name="user-application-end-date-filter" required>
                    </div>
                    <div class="oym-flex-column"><button id="entries-search-button" class="oym-hidden" type="button" value="New">Search</button></div>
                </div>
            </div>
        </div></br>
    user_applications_filter_html;
    }

    #endregion

}


?>