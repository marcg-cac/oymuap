<?php
namespace Oym\Uap\Includes;
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://onyourmarcllc.com/about-plugins
 * @since      1.0.0
 *
 * @package    Oym_Uap
 * @subpackage Oym_Uap/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Oym_Uap
 * @subpackage Oym_Uap/includes
 * @author     Marc Guerrasio <onyourmarc@guerrasio.com>
 */
class i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'oymuap',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
