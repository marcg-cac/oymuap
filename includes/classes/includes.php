<?php
use Oym\Uap\Includes;
use Oym\Uap\Admin;
use Oym\Uap\Public;

class Oym_Uap {
	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'OYMUAP_VERSION' ) ) {
			$this->version = OYMUAP_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'oym-uap';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	private function load_dependencies() {

		// The class responsible for orchestrating the actions and filters of the core plugin.
		require_once OYMUAP_PLUGIN_DIR . 'includes/classes/loader.php';

		// The class responsible for defining internationalization functionality of the plugin.
		require_once OYMUAP_PLUGIN_DIR . 'includes/classes/i18n.php';

		// The class responsible for defining all actions that occur in the admin area.
		require_once OYMUAP_PLUGIN_DIR . 'admin/classes/admin.php';

		// The class responsible for defining all actions that occur in the public-facing side of the site.
		require_once OYMUAP_PLUGIN_DIR . 'public/classes/public.php';

		$this->loader = new Includes\Loader();

	}

	private function set_locale() {
		$plugin_i18n = new Includes\i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function define_admin_hooks() {
		$plugin_admin = new Admin\Oym_Uap_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts_and_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	private function define_public_hooks() {
		$plugin_public = new Public\Oym_Uap_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts_and_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}
}
