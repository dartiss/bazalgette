<?php
/**
Plugin Name: Bazalgette
Plugin URI: https://wordpress.org/plugins/bagalgette
Description: Automatically tidy up your cluttered admin menus
Version: 0.1
Author: David Artiss
Author URI: https://artiss.blog
Text Domain: bazalgette

@package bazalgette
 */

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 */
function bazalgette() {

	global $menu;
	global $submenu;

	//print_r( $menu );
	//print_r( $submenu );
	$output = array();
	$i      = 0;

	// Read through each main menu option.

	foreach ( $menu as $array_key => $menu_array ) {

		$menu_title = $menu_array[0];
		$capability = $menu_array[1];
		$slug       = $menu_array[2];
		$page_title = $menu_array[3];
		$icon       = $menu_array[6];

		// Check if a menu title exists - this rules out menu spacing.

		if ( $menu_title ) {

			// Check if dashicon is used - if not, set a default.

			if ( 'dashicons-' !== substr( $icon, 0, 10 ) ) {
				$menu[ $array_key ][6] = 'dashicons-admin-plugins';
			}

			// Rename menu, if inappropriate.

			// If Comments or Jetpack, then these are allowed to have empty sub-menus (Jetpack has no sub-menus before initially setting up).

			if ( 'jetpack' !== $slug && 'edit-comments.php' !== $slug ) {

				// Check if a sub-menu exists. 

				if ( isset( $submenu[ $slug ] ) ) {

					// Loop through the sub-menus for the current menu item.

					$sub_array = $submenu[ $slug ];

					foreach ( $sub_array as $sub_array_key => $submenu_array ) {

						// Extract the individual elements from the sub-menu array.

						$submenu_title      = $submenu_array[0];
						$submenu_capability = $submenu_array[1];
						$subpage_link       = $submenu_array[2];
						$subpage_title      = $submenu_array[3];

						// Create a new array, if the sub-menu needs moving elsewhere.

						$sub_to_add = array(
							0 => $menu_title,
							1 => $capability,
							2 => $slug,
							3 => $page_title,
						);

						// Remove any sub-menus trying to sell us something!

						$title = strtolower( wp_strip_all_tags( $submenu_title ) );

						if ( strpos( $title, 'upgrade' ) !== false || 'pro' == substr( $title, -3, 3 ) || 'pro!' == substr( $title, -4, 4 ) || strpos( $title, 'addon' ) !== false || strpos( $title, 'add-on' ) !== false || strpos( $title, ' extend' ) !== false || strpos( $title, 'affiliat' ) !== false ) {
							unset( $submenu[ $slug ][ $sub_array_key ] );
						}

						// Move any setting sub-menus to Settings.

						if ( ( strpos( $title, 'settings' ) !== false || strpos( $title, 'options' ) !== false ) && ( 'Settings' !== $menu_title ) ) {
							array_push( $submenu['options-general.php'], $sub_to_add );
							unset( $submenu[ $slug ][ $sub_array_key ] );
						}

						// Move any tools sub-menus to Tools.

						if ( 'tools' == $title && 'Tools' !== $menu_title ) {
							array_push( $submenu['tools.php'], $sub_to_add );
							unset( $submenu[ $slug ][ $sub_array_key ] );
						}
					}

					// Check if any other sub-menus still exist. If not, delete the top-menu.

					if ( ! isset( $submenu[ $slug ] ) ) {
						unset( $menu[ $array_key ] );
					}
				//} else {

					// When no sub-menu exists, move the menu option to settings.

					//unset( $menu[ $array_key ] );
					//$sub_array = array(
					//	0 => $menu_title,
					//	1 => $capability,
					//	2 => $slug,
					//	3 => $page_title,
					//);
					//array_push( $submenu['options-general.php'], $sub_array );

				}
			}
		}
	}
}

add_action( 'admin_init', 'bazalgette' );
