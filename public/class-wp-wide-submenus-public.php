<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/public
 * @author Matt <matt@mediaworksweb.com>
 */
class WP_Wide_Submenus_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since 0.0.1
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 0.0.1
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 0.0.1
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 0.0.1
	 */
	public function enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Wide_Submenus_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Wide_Submenus_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-wide-submenus-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 0.0.1
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_Wide_Submenus_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_Wide_Submenus_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-wide-submenus-public.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add sub menu columns number to the nav menu css class.
	 * 
	 * Add the column number to the nav menu class.
	 * 
	 * @since 0.0.4 Changing columns to cols.
	 * @since 0.0.3 Cast menu_item_parent as integer.
	 * @since 0.0.2
	 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
	 * @param WP_Post $menu_item The current menu item object.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @return string[] $classes Array of the classes;
	 */
	public function add_submenu_columns_number_to_nav_menu_css_class( $classes, $menu_item, $args, $depth ) {
		$submenu_columns_number = $menu_item->submenu_columns_number;
	
		if ( ! empty( $submenu_columns_number ) ) {
			$classes[] = 'submenu-is-wide';
			$classes[] = 'submenu-has-' . $submenu_columns_number . '-cols';
			
			// Check for children items.
			$items = wp_get_nav_menu_items( $args->menu->term_id );
			if ( ! empty( $items ) ) {
				$children = 0;
				foreach ( $items as $item ) {
					if ( $menu_item->ID === (int) $item->menu_item_parent ) {
						$children++;
					}
				}
				$classes[] = 'submenu-has-' . $children . '-children';
			}
		}
	
		return $classes;
	}

	/**
	 * Filter nav item output.
	 * 
	 * Filter the output of the menu item.
	 * 
	 * @since 0.0.5
	 * @param string $item_output The menu item's starting HTML output.
	 * @param WP_Post $menu_item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @return string The item output.
	 */
	public function filter_nav_item_output( $item_output, $menu_item, $depth, $args ) {

		/**
		 * Check if menu item parent has submenu columns number property.
		 * 
		 * Get the menu items by the term_id of the menu.
		 */
		$parent_id = (int) $menu_item->menu_item_parent;

		if ( $parent_id > 0 ) {

			$_menu_items = wp_get_nav_menu_items( $args->menu->term_id );

			if ( ! empty( $_menu_items ) ) {
				$submenu_columns_number = 1;
				$submenu_children 		= 0;

				foreach ( $_menu_items as $_menu_item ) {
					// Parent.
					if ( $parent_id === $_menu_item->ID && ! empty( $_menu_item->submenu_columns_number ) ) {
						// Number of submenu columns.
						$submenu_columns_number = (int) $menu_item->submenu_columns_number;
					} elseif ( $parent_id === (int) $_menu_item->menu_item_parent ) {
						// Number of submenu children.
						$submenu_children++;
					}
				}

				if ( $submenu_columns_number > 1 && $submenu_children > 0 && ! empty( $submenu_columns_number ) ) {
					$divisor = round( $submenu_children / $submenu_columns_number );
					$count   = 0;

					// Loop through items again.
					foreach ( $_menu_items as $_menu_item ) {
						if ( $menu_item->ID === $_menu_item->ID ) {
							if ( 0 === $count ) {
								$item_output = '<div>' . $item_output;
							} elseif ( count( $_menu_items ) === $count ) {
								$item_output = $item_output . '</div>';
							} else {
								$item_output = '</div></div>' . $item_output;
							}
						}
						$count++;
					}
				}				
			}			
		}		

		return $item_output;
	}
}