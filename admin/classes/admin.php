<?php
namespace Oym\Uap\Admin;
use Oym\Utility\Includes\Shared as Utility_Includes;

class Oym_Uap_Admin {
	private $plugin_name;
	private $version;
	public $utility_loader;
	public $utility_functions;
	public $localization_data;
	public $ajax_security_nonce;
	public $is_oymuap;
	public $has_tabulator;
	private $is_debug;	

	public function __construct( $plugin_name, $version ) {
		$this->is_debug = isset($_COOKIE['oym_is_debug']) ? $_COOKIE['oym_is_debug'] : false;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->utility_loader = new Utility_Includes\Oym_Utility_Loader();
		$this->utility_functions = new Utility_Includes\Oym_Utility_Functions();
		$this->set_variables();
	}

	function set_variables(){
		$this->ajax_security_nonce = wp_create_nonce( 'oymuap-security-nonce' );
		$this->localization_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'admin_url' => admin_url(),
			'plugin_url' => OYMUAP_PLUGIN_URL,
			'site_url' => OYMUAP_SITE_URL,
			'security'  => $this->ajax_security_nonce,
			'debug_on' => $this->is_debug,
		);
		
		$this->is_oymuap = $this->utility_loader->is_current_plugin(
			[
				'page'=>'oymuap-',
				'posttype'=>'oymuap-',
			]
		);	
		
		$this->has_tabulator = $this->utility_loader->has_tabulator(array('oymuap-users-with-applications', 'oymuap-user-applications', 'oymuap-recent-user-applications'));
	}

	public function enqueue_scripts_and_styles() {
		if ($this->is_oymuap){
			$this->utility_loader->enqueue_base_style();
			$this->utility_loader->enqueue_jquery_ui();
			$this->utility_loader->enqueue_jquery_timepicker();
		}
	}

	public function enqueue_styles() {
		if ($this->is_oymuap){
			wp_enqueue_style( 'oymuap-admin-css', OYMUAP_PLUGIN_URL . 'admin/css/admin.css', array(), $this->utility_functions->unique_id(), 'all' );
			if ($this->has_tabulator){
				$this->utility_loader->enqueue_tabulator();
			}
		}
	}

	public function enqueue_scripts() {
		if ($this->is_oymuap){
			if ($this->has_tabulator){
				$this->utility_loader->enqueue_tabulator();
			}
		}

		if ($this->utility_loader->page == "oymuap-users-with-applications"){
			wp_enqueue_script( 'oymuap-users-with-applications-page-js', OYMUAP_PLUGIN_URL . 'admin/js/pages/users-with-applications-page.js?version=' . $this->utility_functions->unique_id(), array( 'jquery' ), false, false );
			wp_localize_script( 'oymuap-users-with-applications-page-js', 'oymuap_js_defaults', $this->localization_data);
		}

		if ($this->utility_loader->page == "oymuap-user-applications"){
			wp_enqueue_script( 'oymuap-user-applications-page-js', OYMUAP_PLUGIN_URL . 'admin/js/pages/user-applications-page.js?version=' . $this->utility_functions->unique_id(), array( 'jquery' ), false, false );
			wp_localize_script( 'oymuap-user-applications-page-js', 'oymuap_js_defaults', $this->localization_data);
		}

		if ($this->utility_loader->page == "oymuap-recent-user-applications"){
			wp_enqueue_script( 'oymuap-recent-user-applications-page-js', OYMUAP_PLUGIN_URL . 'admin/js/pages/recent-user-applications-page.js?version=' . $this->utility_functions->unique_id(), array( 'jquery' ), false, false );
			wp_localize_script( 'oymuap-recent-user-applications-page-js', 'oymuap_js_defaults', $this->localization_data);
		}

		if ($this->utility_loader->page == "oymuap-user-application"){
			wp_enqueue_script( 'oymuap-user-application-page-js', OYMUAP_PLUGIN_URL . 'admin/js/pages/user-application-page.js?version=' . $this->utility_functions->unique_id(), array( 'jquery' ), false, false );
			wp_localize_script( 'oymuap-user-application-page-js', 'oymuap_js_defaults', $this->localization_data);
		}

	}

}
