<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/includes
 * @author Matt <matt@mediaworksweb.com>
 */
class WP_Wide_Submenus_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 0.0.1
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'wp-wide-submenus',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}