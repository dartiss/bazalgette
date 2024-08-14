<?php
/**
 * Clean the menu
 *
 * Process the menu and sub-menu items and clean.
 *
 * @package bazalgette
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Process the menus
 *
 * Loops through the menu and sub-menu global arrays and processes them for cleaning.
 */
function bazalgette_process_menus() {

	// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited
	global $menu, $submenu;

	// Make a copy of the menu arrays - these are the ones that we'll read.
	$menu_copy       = $menu;
	$submenu_copy    = $submenu;
	$core_menu_slugs = bazalgette_core_menu( 'slugs' );

	// Read through each main menu option.
	foreach ( $menu_copy as $menu_array_key => $menu_record ) {

		// Make sure the menu being processed isn't one of the core ones.
		if ( ! array_key_exists( $menu_record[2], $core_menu_slugs ) ) {

			// Check if a menu title exists - this rules out menu spacing.
			if ( $menu_record[0] ) {

				// Clean the menu title.
				$menu[ $menu_array_key ][0] = bazalgette_clean_title( $menu_record[0] );

				// Remove any menu classes other than a default.
				$menu[ $menu_array_key ][4] = 'menu-top';
				$menu[ $menu_array_key ][5] = '';

				// Look for a non-standard menu icon and change to a default,if required.
				if ( ( 'none' === $menu_record[6] ) || ( 'dashicons-' !== substr( $menu_record[6], 0, 10 ) && 'data:image/svg' !== substr( $menu_record[6], 0, 14 ) ) ) {
					$menu[ $menu_array_key ][6] = 'dashicons-admin-plugins';
				}

				// Check if a sub-menu exists for the current menu. If so, process that.
				if ( isset( $submenu_copy[ $menu_record[2] ] ) ) {

					// Process each sub-menu record for the current record.
					foreach ( $submenu_copy[ $menu_record[2] ] as $submenu_array_key => $submenu_record ) {

						// Check if a submenu title exists - this rules out menu spacing.
						if ( $submenu_record[0] ) {

							// Clean the sub-menu title.
							$title = bazalgette_clean_title( $submenu_record[0] );
							$submenu[ $menu_record[2] ][ $submenu_array_key ][0] = $title;

							// Remove any menu classes.
							$submenu[ $menu_record[2] ][ $submenu_array_key ][4] = '';

							bazalgette_evaluate_submenus( $menu_record, $submenu_record, $title );

						} else {

							// Remove any empty sub-menus.
							remove_submenu_page( $menu_record[2], $submenu_record[2] );
						}
					}
				}
			} else {

				// Remove any empty menus OPTIONAL!
				remove_menu_page( $menu_record[2] );
			}
		}
	}
}

add_action( 'admin_head', 'bazalgette_process_menus', 9999 );

/**
 * Clean menu title
 *
 * Cleans up the HTML in a menu or sub-menu title.
 *
 * @param    string $title  The title to be cleaned.
 * @return   string         The cleaned title.
 */
function bazalgette_clean_title( $title ) {

	// Regular expression to match numbers inside <span> elements.
	preg_match_all( '/<span[^>]*>(\d+)<\/span>/', $title, $matches );
	$number = $matches[1][0];

	// Regular expression to match numbers inside <span> elements, removing the number but keeping the span tags.
	$title = preg_replace( '/(<span[^>]*>)(\d+)(<\/span>)/', '$1$3', $title );

	// Strip the title of all HTML tags.
	$title = wp_strip_all_tags( $title );

	// If there was a counter, re-add it.
	if ( isset( $number ) && $number > 0 ) {
		$title .= '<span class="update-plugins count-' . $number . '">' . $number . '</span>';
	}

	return $title;
}

/**
 * Evaluate Sub-menus
 *
 * Looks at a sub-menu and decides if it can be delete or removed.
 *
 * @param array  $menu_record     Array of menu being processed.
 * @param array  $submenu_record  Array of sub-menu being processed.
 * @param string $title           Cleaned sub-menu title.
 */
function bazalgette_evaluate_submenus( $menu_record, $submenu_record, $title ) {

	// Create an array of words to look for at the start of the title.
	$remove_start = array(
		'Upgrade',
		'Addon',
		'Add-on',
		'About us',
		'Welcome',
	);

	// Loop through the above array.
	foreach ( $remove_start as $remove_text ) {

		// If the sentence is found at the beginning of the name, remove the entire sub-menu.
		if ( 0 === stripos( $title, $remove_text ) ) {
			remove_submenu_page( $menu_record[2], $submenu_record[2] );
		}
	}
}
