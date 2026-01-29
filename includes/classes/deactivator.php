<?php
use Oym\Uap\Includes;
/**
 * Fired during plugin deactivation
 *
 * @link       https://onyourmarcllc.com/about-plugins
 * @since      1.0.0
 *
 * @package    Oym_Uap
 * @subpackage Oym_Uap/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Oym_Uap
 * @subpackage Oym_Uap/includes
 * @author     Marc Guerrasio <onyourmarc@guerrasio.com>
 */
class Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		
		require_once OYMUAP_PLUGIN_DIR . 'includes/classes/settings/settings.php';
		$program_settings = new Includes\Uap_Program_Settings();
		$program_settings->delete_setting();

		$pdf_settings = new Includes\Uap_Pdf_Settings();
		$pdf_settings->delete_setting();

		$url_settings = new Includes\Uap_Url_Settings();
		$url_settings->delete_setting();

		$file_settings = new Includes\Uap_File_Settings();
		$file_settings->delete_setting();

		$email_settings = new Includes\Uap_Email_Settings();
		$email_settings->delete_setting();

		$debug_settings = new Includes\Uap_Debug_Settings();
		$email_sedebug_settingsttings->delete_setting();

	}

}
