<?php
/**
Plugin Name: Bazalgette
Plugin URI: https://github.com/dartiss/bazalgette
Description: Automatically tidy up your cluttered admin menus
Version: 0.1
Author: David Artiss
Author URI: https://artiss.blog
Text Domain: bazalgette

@package bazalgette
 */

/**
 * THIS VERSION IS AN EARLY PROOF OF CONCEPT - IT WORKS BUT THERE ARE NO OPTIONS AND IT DOES
 * JUST A BASIC CLEAN
 */

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 *
 * @param  string $links  Current links.
 * @param  string $file   File in use.
 * @return string         Links, now with settings added.
 */
function bazalgette_plugin_meta( $links, $file ) {

	if ( false !== strpos( $file, 'tidy-my-menus.php' ) ) {

		$links = array_merge( $links, array( '<a href="https://github.com/dartiss/bazalgette">' . __( 'Github', 'bazalgette' ) . '</a>' ) );

		$links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/bazalgette">' . __( 'Support', 'bazalgette' ) . '</a>' ) );

		$links = array_merge( $links, array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'bazalgette' ) . '</a>' ) );

		$links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/bazalgette/reviews/#new-post">' . __( 'Write a Review', 'bazalgette' ) . '&nbsp;⭐️⭐️⭐️⭐️⭐️</a>' ) );
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'bazalgette_plugin_meta', 10, 2 );

/**
 * Tidy the menus
 *
 * Main code to automatically move and remove items from the admin menus
 */
function bazalgette() {

	$menus    = $GLOBALS['menu'];
	$submenus = $GLOBALS['submenu'];

	// Exclusion array - none of the menu options in this list will be touched by the automated removal process.

	$exclusion_array = array( 'bp-activity', 'edit-comments.php', 'galleries_bwg' );

	// Special array - move specific sub-menus to settings or just delete the menu altogether.

	$special_array = array(
		'gutenberg'  => '',
		'metaslider' => 'MetaSlider',
		'wppr'       => 'Product Review',
	);

	// Process through the current menu structure, moving and removing menus according to the above array and some simple logic that a menu option
	// with no sub-menus probably belongs in 'settings'.

	foreach ( $menus as $main_menu_array ) {

		$submenu = $submenus[ $main_menu_array[2] ];

		if ( 0 === count( $submenu ) && ! in_array( $main_menu_array[2], $exclusion_array, true ) && '' !== $main_menu_array[0] ) {

			// Remove the menu option.
			remove_menu_page( $main_menu_array[2] );

			// Work out the correct page name.
			$page = $main_menu_array[5];
			if ( 'toplevel_page_' === substr( $page, 0, 14 ) ) {
				$page = substr( $page, 14 );
			}

			// Now add menu option as a sub-menu to settings.
			add_submenu_page( 'options-general.php', $main_menu_array[3], $main_menu_array[0], $main_menu_array[1], $page, $main_menu_array[6] );
		}

		// If this is a standard menu and it matches the main menu slug, process it.
		if ( isset( $submenu ) && array_key_exists( $main_menu_array[2], $special_array ) ) {

			$submenu_page = $special_array[ $main_menu_array[2] ];

			// Remove the main menu option.
			remove_menu_page( $main_menu_array[2] );

			// If the requested submenu page is blank, that means I just want to delete all the menus out and not move anything.
			if ( '' !== $submenu_page ) {

				// Now look for the relevant sub-menu and move it to the settings.
				foreach ( $submenu as $sub_menu_array ) {
					if ( $sub_menu_array[0] === $submenu_page ) {
						add_submenu_page( 'options-general.php', $sub_menu_array[3], $sub_menu_array[0], $sub_menu_array[1], $sub_menu_array[2] );
					}
				}
			}
		}
	}
}

add_action( 'admin_init', 'bazalgette' );
