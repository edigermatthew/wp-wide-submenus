<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since 0.0.1
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package WP_Wide_Submenus
 * @subpackage WP_Wide_Submenus/admin
 * @author Matt <matt@mediaworksweb.com>
 */
class WP_Wide_Submenus_Admin {

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
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Add nav menu item property.
	 * 
	 * Add a new nav menu item propterty.
	 * 
	 * @since 0.0.3 Checking for $item.
	 * @since 0.0.2
	 * @param object $item The menu item object.
	 * @return object The menu item object.
	 */
	public function add_nav_menu_item_property( $item ) {
		$item->submenu_columns_number = ! empty( $item ) && isset( $item->ID ) ? get_post_meta( $item->ID, '_menu_item_submenu_columns_number', true ) : '';
		return $item;
	}

	/**
	 * Update nav menu items.
	 * 
	 * Save the nav menu items.
	 * 
	 * @since 0.0.2
	 * @param int $menu_id The ID of the menu.
	 * @param int $menu_item_db_id The menu item db ID.
	 * @param array $menu_item_args The args for the menu item.
	 */
	public function update_nav_menu_item( $menu_id, $menu_item_db_id, $menu_item_args ) {
		if ( is_array( $_POST['menu-item-submenu-columns-number'] ) ) {
			$menu_item_args['menu-item-submenu-columns-number'] = $_POST['menu-item-submenu-columns-number'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_submenu_columns_number', sanitize_html_class( $menu_item_args['menu-item-submenu-columns-number'] ) );
		}
	}

	/**
	 * Add custom fields to walker.
	 * 
	 * Add the custom fields for the submenu columns number to the menu walker.
	 * 
	 * @since 0.0.2
	 * @param string $item_id Menu item ID as a numeric string.
	 * @param WP_Post $menu_item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param stdClass|null $args An object of menu item arguments.
	 * @param int $current_object_id Nav menu ID.
	 */
	public function add_custom_fields_to_walker( $item_id, $menu_item, $depth, $args, $current_object_id ) {
		?>
			<label for="edit-menu-item-submenu-columns-number-<?php echo $item_id; ?>">
				<?php echo __( 'Submenu Columns', $this->plugin_name ); ?>
				<br />
				<input type="number" name="menu-item-submenu-columns-number[<?php echo $item_id; ?>]" id="edit-menu-item-submenu-columns-number-<?php echo $item_id; ?>" value="<?php echo esc_attr( $menu_item->submenu_columns_number ); ?>">
			</label>
		<?php
	}
}