<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://mainetcare.de
 * @since      1.0.0
 *
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Aktivregionostseekueste
 * @subpackage Aktivregionostseekueste/includes
 * @author     MaiNetCare <m.mai@mainetcare.de>
 */
class Aktivregionostseekueste_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'aktivregionostseekueste',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
