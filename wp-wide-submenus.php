<?php
/**
 * Plugin Name: WP Wide Submenus
 * Plugin URI:  https://mediaworksweb.com
 * Description: Make your submenus wide! Extends menus by adding columns.
 * Version: 	0.0.4
 * Author: 		Matt
 * Author URI:  https://mediaworksweb.com/
 * License: 	GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: wp-wide-submenus
 * Domain Path: /languages
 * 
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link https://mediaworksweb.com
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @wordpress-plugin
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.0.1 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_WIDE_SUBMENUS_VERSION', '0.0.4' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-wide-submenus-activator.php
 */
function activate_wp_wide_submenus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-wide-submenus-activator.php';
	WP_Wide_Submenus_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-wide-submenus-deactivator.php
 */
function deactivate_wp_wide_submenus() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-wide-submenus-deactivator.php';
	WP_Wide_Submenus_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_wide_submenus' );
register_deactivation_hook( __FILE__, 'deactivate_wp_wide_submenus' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-wide-submenus.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 0.0.1
 */
function run_wp_wide_submenus() {
	$plugin = new WP_Wide_Submenus();
	$plugin->run();
}
run_wp_wide_submenus();