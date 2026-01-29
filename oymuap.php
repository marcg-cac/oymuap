<?php
use Oym\Uap\Includes;
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://onyourmarcllc.com/about-plugins
 * @since             1.0.0
 * @package           Oym_Uap
 *
 * @wordpress-plugin
 * Plugin Name:       OYM UAP
 * Plugin URI:        https://onyourmarcllc.com/oym-uap
 * Description:       A plugin to let people submit a universal application.
 * Version:           1.25.12.16
 * Author:            Marc Guerrasio
 * Author URI:        https://onyourmarcllc.com/about-plugins/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       oymuap
 * Domain Path:       /languages
 * Requires Plugins: oymutility
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Rename this for your plugin and update it as you release new versions.
 */
#region plugin info
define( 'OYMUAP_VERSION', '0.5.7' );
define( 'OYMUAP_MINIMUM_WP_VERSION', '5.8' );
#endregion

#region default urls and paths
define( 'OYMUAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'OYMUAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'OYMUAP_PLUGINS_DIR', plugin_dir_path( __DIR__ ) );
define( 'OYMUAP_SITE_URL', get_site_url());
define( 'OYMUAP_BASE_URL', $_SERVER['HTTP_HOST']);
define( 'OYMUAP_PDF_FOLDER', 'cac-profiles');
define( 'OYMUAP_PDF_FILE_PATH', wp_get_upload_dir()['basedir'] . '/' . OYMUAP_PDF_FOLDER);
define( 'OYMUAP_PDF_URL_PATH', wp_get_upload_dir()['baseurl'] . '/' . OYMUAP_PDF_FOLDER); 
define( 'OYMUAP_CONTENT_DIR', WP_CONTENT_DIR . '/');

//define( 'OYMUAP_PDF_SUPPLEMENT_FILE_PATH', preg_replace( '/wp-content.*$/', '', __DIR__ ) . '/wp-content/uploads/cac-pdfs/');
#endregion

#region page urls
define( 'OYMUAP_RECENT_USER_APPLICATIONS_URL', admin_url() . 'admin.php?page=oymuap-recent-user-applications');
define( 'OYMUAP_USER_APPLICATIONS_URL', admin_url() . 'admin.php?page=oymuap-user-applications');
define( 'OYMUAP_USER_APPLICATION_PDF_URL', admin_url() . 'admin.php?page=oymuap-user-application-pdf');
define( 'OYMUAP_CONTINUATION_URL', OYMUAP_SITE_URL . '/wp-content/plugins/oymuap/public/classes/continuation.php');

#endregion


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/oymuap-activator.php
 */
 function activate_oym_uap() {
	//require_once OYMUAP_PLUGIN_DIR . 'includes/classes/activator.php';
	//Includes\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/oymuap-deactivator.php
 */
function deactivate_oym_uap() {
	//require_once OYMUAP_PLUGIN_DIR . 'includes/classes/deactivator.php';
	//Includes\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_oym_uap' );
register_deactivation_hook( __FILE__, 'deactivate_oym_uap' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
add_action('plugins_loaded', 'oymuap_init');

function oymuap_init() {
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/includes.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/access/capabilities.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/objects/objects.php'; // no dependencies
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/settings/settings.php'; // no dependencies
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/settings/wpforms-settings.php'; // no dependencies
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/data/data.php'; // no dependencies
	require_once OYMUAP_PLUGIN_DIR . 'admin/classes/menu.php'; // no dependencies

	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/user-applications/user-application-manager.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/user-applications/process-manager.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/custom/custom-wpforms.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/custom/custom-wordpress.php';
	require_once OYMUAP_PLUGIN_DIR . 'includes/classes/user-applications/user-application-pdf.php';
	require_once OYMUAP_PLUGIN_DIR . 'public/api/api-ajax.php';

	run_oym_uap();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_oym_uap() {
	$plugin = new Oym_Uap();
	$plugin->run();
}

