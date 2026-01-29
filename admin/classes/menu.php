<?php
/**
 * @since 1.0.0
 */
namespace Oym\Uap\Admin;
use Oym\Uap\Admin\Pages;
use Oym\Uap\Includes;
use Oym\Utility\Includes\Shared as Utility_Includes;

class AdminMenu {
	/**
	 * Primary class constructor.
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', [ $this, 'settings_init' ], 9 );
		add_action( 'admin_menu', [ $this, 'register_menus' ], 9 );
	}

	public function settings_init(){
		require_once OYMUAP_PLUGIN_DIR . 'includes/classes/settings/settings.php';
		$program_settings = new Includes\Uap_Program_Settings();
		$program_settings->create_setting();

		$pdf_settings = new Includes\Uap_Pdf_Settings();
		$pdf_settings->create_setting();

		$url_settings = new Includes\Uap_Url_Settings();
		$url_settings->create_setting();

		$file_settings = new Includes\Uap_File_Settings();
		$file_settings->create_setting();

		$email_settings = new Includes\Uap_Email_Settings();
		$email_settings->create_setting();

		$debug_settings = new Includes\Uap_Debug_Settings();
		$debug_settings->create_setting();

		$form_id_settings = new Includes\Uap_Wpforms_Form_Ids_Settings();
		$form_id_settings->create_setting();

		$registration_settings = new Includes\Uap_Wpforms_Registration_Form_Settings();
		$registration_settings->create_setting();

		$start_settings = new Includes\Uap_Wpforms_Start_Form_Settings();
		$start_settings->create_setting();

		$household_settings = new Includes\Uap_Wpforms_Household_Form_Settings();
		$household_settings->create_setting();

		$demographic_settings = new Includes\Uap_Wpforms_Demographic_Form_Settings();
		$demographic_settings->create_setting();

		$income_settings = new Includes\Uap_Wpforms_Income_Form_Settings();
		$income_settings->create_setting();

		$energy_settings = new Includes\Uap_Wpforms_Energy_Form_Settings();
		$energy_settings->create_setting();

		$housing_settings = new Includes\Uap_Wpforms_Housing_Form_Settings();
		$housing_settings->create_setting();

		$completion_settings = new Includes\Uap_Wpforms_Completion_Form_Settings();
		$completion_settings->create_setting();

		$add_household_member_settings = new Includes\Uap_Wpforms_Add_Household_Member_Form_Settings();
		$add_household_member_settings->create_setting();

		$add_program_settings = new Includes\Uap_Program_Add_Settings();
		$add_program_settings->create_setting();
	}


	/**
	 * Register our menus.
	 * @since 1.0.0
	 */
	public function register_menus() {
		// Default top level menu item.
		add_menu_page(
			esc_html__( 'OYM Universal App', 'oymuap' ),
            esc_html__( 'OYM Universal App', 'oymuap' ),
			'manage_options', 
			'oymuap-recent-user-applications',
			[ $this, 'display_recent_user_applications' ],
			'dashicons-format-gallery'
		);

		add_submenu_page(
			'oymuap-universal-applications-hidden',
			esc_html__( 'User Applications', 'oymuap' ),
			esc_html__( 'User Applications', 'oymuap' ),
			'manage_options', 
			'oymuap-user-applications',
			[ $this, 'display_user_applications' ]
		);
		
		add_submenu_page(
			'oymuap-universal-application-hidden',
			esc_html__( 'User Application', 'oymuap' ),
			esc_html__( 'User Application', 'oymuap' ),
			'manage_options', 
			'oymuap-user-application',
			[ $this, 'display_user_application' ]
		);

		add_submenu_page(
			'oymuap-universal-application-hidden',
			esc_html__( 'User Application PDF', 'oymuap' ),
			esc_html__( 'User Application PDF', 'oymuap' ),
			'manage_options', 
			'oymuap-user-application-pdf',
			[ $this, 'display_user_application_pdf' ]
		);
		
		if (current_user_can( 'oymuap_view_settings' ) ) {
			add_submenu_page(
				'oymuap-recent-user-applications',
				esc_html__( 'Settings', 'oymuap' ),
				esc_html__( 'Settings', 'oymuap' ),
				'manage_options', 
				'oymuap-settings',
				[ $this, 'display_settings' ]
			);
		}

		if (current_user_can( 'oymuap_view_settings' ) ) {
			add_submenu_page(
				'oymuap-recent-user-applications',
				esc_html__( 'WPForms Settings', 'oymuap' ),
				esc_html__( 'WPForms Settings', 'oymuap' ),
				'manage_options', 
				'oymuap-wpf-settings',
				[ $this, 'display_wpforms_settings' ]
			);
		}

		if (current_user_can( 'oymuap_edit_debug' ) ) {
			add_submenu_page(
				'oymuap-recent-user-applications',
				esc_html__( 'Universal App Debug', 'oymuap' ),
				esc_html__( 'Universal App Debug', 'oymuap' ),
				'manage_options', 
				'oymuap-utility-debug',
				[ $this, 'display_utility_debug' ]
			);
		}
	}



	/**
	 * Wrapper for the hook to render our custom pages.
	 * @since 1.0.0
	 */

	public function display_applications() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/applications.phtml';
	}

	public function display_application() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/application.phtml';
	}

	public function display_recent_user_applications() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/recent-user-applications.phtml';
	}

	public function display_user_applications() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/user-applications.phtml';
	}

	public function display_user_application() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/user-application.phtml';
	}

	public function display_user_application_pdf() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/user-application-pdf.phtml';
	}

	public function display_settings() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/settings.phtml';
	}

	public function display_wpforms_settings() {
		include_once OYMUAP_PLUGIN_DIR . 'admin/partials/wpforms-settings.phtml';
	}

	public function display_utility_debug() {
		include_once OYMUTILITY_PLUGIN_DIR . 'includes/partials/debug.phtml';
	}


}

new AdminMenu();
