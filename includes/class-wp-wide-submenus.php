<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/includes
 * @author Matt <matt@mediaworksweb.com>
 */
class WP_Wide_Submenus {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var WP_Wide_Submenus_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 0.0.1
	 * @access protected
	 * @var string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since 0.0.1
	 */
	public function __construct() {
		if ( defined( 'WP_WIDE_SUBMENUS_VERSION' ) ) {
			$this->version = WP_WIDE_SUBMENUS_VERSION;
		} else {
			$this->version = '0.0.1';
		}
		$this->plugin_name = 'wp-wide-submenus';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WP_Wide_Submenus_Loader. Orchestrates the hooks of the plugin.
	 * - WP_Wide_Submenus_i18n. Defines internationalization functionality.
	 * - WP_Wide_Submenus_Admin. Defines all hooks for the admin area.
	 * - WP_Wide_Submenus_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since 0.0.1
	 * @access private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-wide-submenus-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-wide-submenus-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-wide-submenus-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-wide-submenus-public.php';

		/**
		 * The class responsible for the menu walker.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-wide-submenus-walker.php';

		$this->loader = new WP_Wide_Submenus_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WP_Wide_Submenus_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since 0.0.1
	 * @access private
	 */
	private function set_locale() {
		$plugin_i18n = new WP_Wide_Submenus_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since  0.0.5 Adding wp_update_nav_menu hook.
	 * @since  0.0.1
	 * @access private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WP_Wide_Submenus_Admin( $this->get_plugin_name(), $this->get_version() );

		// Admin menu item hooks.
		$this->loader->add_filter( 'wp_setup_nav_menu_item', $plugin_admin, 'add_nav_menu_item_property' );
		$this->loader->add_action( 'wp_update_nav_menu_item', $plugin_admin, 'update_nav_menu_item', 10, 3 );
		$this->loader->add_action( 'wp_nav_menu_item_custom_fields', $plugin_admin, 'add_custom_fields_to_walker', 10, 5 );
		$this->loader->add_action( 'wp_update_nav_menu', $plugin_admin, 'update_nav_menu', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since 0.0.1
	 * @access private
	 */
	private function define_public_hooks() {
		$plugin_public = new WP_Wide_Submenus_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Public menu item hooks.
		$this->loader->add_filter( 'nav_menu_css_class', $plugin_public, 'add_submenu_columns_number_to_nav_menu_css_class', 10, 4 );
		$this->loader->add_filter( 'wp_nav_menu_args', $plugin_public, 'modify_nav_menu_args', 0 );
		//$this->loader->add_filter( 'walker_nav_menu_start_el', $plugin_public, 'filter_nav_item_output', 10, 4 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since 0.0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 0.0.1
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since 0.0.1
	 * @return WP_Wide_Submenus_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since 0.0.1
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}