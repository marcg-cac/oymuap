<?php
namespace Oym\Uap\Public;
use Oym\Utility\Includes\Shared as Utility_Includes;

class Oym_Uap_Public {
	private $plugin_name;
	private $version;
 	public $utility_loader;
	public $utility_functions;
	public $localization_data;
	public $ajax_security_nonce;
	public $is_oymuap;
	private $is_debug;	
	
	public function __construct( $plugin_name, $version ) {
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
			'plugin_url' => OYMTT_PLUGIN_URL,
			'security'  => $this->ajax_security_nonce,
			'debug_on' => $this->is_debug,
		);

		$this->is_oymuap = $this->utility_loader->is_current_plugin(
			[
				'page'=>'oymuap-',
				'posttype'=>'oymuap-',
			]
		);
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
			wp_enqueue_style( $this->plugin_name, OYMUAP_PLUGIN_URL . 'public/css/public.css', array(), $this->version, 'all' );
		}
	}

	public function enqueue_scripts() {
		if ($this->is_oymuap){
		   	wp_enqueue_script($this->plugin_name, OYMUAP_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->utility_functions->unique_id(), false );
		   	wp_localize_script($this->plugin_name, 'oymuap_js_defaults', $this->localize_array);
		}
	}
}
