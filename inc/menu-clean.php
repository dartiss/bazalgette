<?php
/**
 * Menu Clean
 *
 * Main function that performs the menu tidying
 *
 * @package bazalgette
 */

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 */
function bazalgette_init() {

	global $menu;
	global $submenu;

	//print_r($menu);
	//print_r($submenu);

	// Assign a version to the cleaning engine. Upon any change in logic, this will be updated to force a refresh of the cache.
	$clean_engine = 1.0;

	// Caclculate a hash for the current menus (plus the above version) and fetch the current cache.
	$hash  = sha1( wp_json_encode( $menu ) . wp_json_encode( $submenu ) . $clean_engine );
	$cache = get_transient( 'bazalgette' );

	// If the cached hash is different, we need to regenerate the cache. Otherwise, override the current menus with the cache.
	//if ( $hash != $cache['hash'] ) {
	if ( 1 == 1 ) { // Use this line in preference to the one above to switch off cache (for testing).

		$action = array();
		$number = 1;

		// Read through each main menu option.

		foreach ( $menu as $array_key => $menu_array ) {

			$menu_title = $menu_array[0];
			$capability = $menu_array[1];
			$slug       = $menu_array[2];
			$page_title = $menu_array[3];
			$class      = $menu_array[4];
			$id         = $menu_array[5];
			$icon       = $menu_array[6];

			// Check if a menu title exists - this rules out menu spacing.

			if ( $menu_title ) {

				// Check if dashicon is used - if not, set a default.
				if ( 'dashicons-' !== substr( $icon, 0, 10 ) ) {
					$menu[ $array_key ][6] = bazalgette_allocate_icon( $menu_title, $page_title ); // @codingStandardsIgnoreLine -- this is the best and most efficient way of achieving this
					/* translators: %s is replaced with the menu title */
					$action[ $number ]['text']  = sprintf( __( '%s: Is not using a Dashicon, so one has been assigned to it.', 'bazalgette' ), $menu_title );
					$action[ $number ]['slug']  = $slug;
					$action[ $number ]['issue'] = 1;
					$number++;
				}

				// If Comments or Jetpack, then these are allowed to have empty sub-menus (Jetpack has no sub-menus before initially setting up).
				if ( 'jetpack' !== $slug && 'edit-comments.php' !== $slug ) {

					// Check if a sub-menu exists.
					if ( isset( $submenu[ $slug ] ) ) {

						// Loop through the sub-menus for the current menu item.
						$sub_array = $submenu[ $slug ];
						$removed   = 0;

						foreach ( $sub_array as $sub_array_key => $submenu_array ) {

							// Extract the individual elements from the sub-menu array.
							$submenu_title      = $submenu_array[0];
							$submenu_capability = $submenu_array[1];
							$submenu_slug       = $submenu_array[2];
							$subpage_title      = $submenu_array[3];

							// Strip sub-menus of un-needed code. Those with number counts in them are excluded.
							if ( strpos( $submenu_title, 'update-plugins' ) !== false ) {
								$title = $submenu_title;
							} else {
								$title = wp_strip_all_tags( $submenu_title );
							}

							// If the title has changed, update it!
							if ( $title != $submenu_title ) {
								$submenu[ $slug ][ $sub_array_key ][0] = $title;  // @codingStandardsIgnoreLine -- this is the best and most efficient way of achieving this
								/* translators: %s is replaced with the menu title */
								$action[ $number ]['text']  = sprintf( __( '%s: HTML has been removed from the menu title.', 'bazalgette' ), $menu_title );
								$action[ $number ]['slug']  = $slug;
								$action[ $number ]['issue'] = 2;
								$number++;
							}

							$title = strtolower( $title );

							// Remove any sub-menus trying to sell us something!
							if ( strpos( $title, 'upgrade' ) !== false || 'pro' == substr( $title, -3, 3 ) || 'pro!' == substr( $title, -4, 4 ) || strpos( $title, 'addon' ) !== false || strpos( $title, 'add-on' ) !== false || strpos( $title, ' extend' ) !== false || strpos( $title, 'affiliat' ) !== false || strpos( $title, 'giveaways' ) !== false ) {
								remove_submenu_page( $slug, $submenu_slug );
								$removed++;
							}

							// Remove any "about us" menus.
							if ( 'about' == $title || 'about us' == $title ) {
								remove_submenu_page( $slug, $submenu_slug );
								$removed++;
							}

							// Remove any support/contact menus.
							if ( strpos( $title, 'support' ) !== false || strpos( $title, 'contact us' ) !== false || strpos( $title, 'suggest ' ) !== false || strpos( $title, 'help' ) !== false || strpos( $title, 'forum' ) !== false || strpos( $title, 'documentation' ) !== false || strpos( $title, 'getting started' ) !== false ) {
								remove_submenu_page( $slug, $submenu_slug );
								$removed++;
							}

							// Move any setting sub-menus to Settings.
							if ( ( strpos( $title, 'settings' ) !== false || strpos( $title, 'options' ) !== false ) && ( 'Settings' !== $menu_title ) ) {
								//array_push( $submenu['options-general.php'], $sub_to_add );
								//unset( $submenu[ $slug ][ $sub_array_key ] );
								//remove_submenu_page( $slug, $submenu_slug );
								add_submenu_page( 'options-general.php', $subpage_title, $menu_title, $submenu_capability, $submenu_slug, $submenu_link );
								$removed++;
							}

							// Move any tools sub-menus to Tools.
							if ( 'tools' == $title && 'Tools' !== $menu_title ) {
								//array_push( $submenu['tools.php'], $sub_to_add );
								//unset( $submenu[ $slug ][ $sub_array_key ] );
								$removed++;
							}

							// Move any dashboard sub-menus to Dashboard.
							if ( 'dashboard' == $title && 'Dashboard' !== $menu_title ) {
								//array_push( $submenu['index.php'], $sub_to_add );
								//unset( $submenu[ $slug ][ $sub_array_key ] );
								$removed++;
							}
						}

						// Check if any other sub-menus still exist. If not, and it's a result of all the above processing, delete the top-menu.
						// If I didn't do anything to cause this, then we need to keep the menu in place.
						if ( 0 == count( $submenu[ $slug ] ) && 1 < $removed ) {
							//unset( $menu[ $array_key ] );
						}
					}
				}
			}
		}

		$cache['hash']    = $hash;
		$cache['menu']    = $menu;
		$cache['submenu'] = $submenu;
		set_transient( 'bazalgette', $cache, 0 );

	} else {
		$menu    = $cache['menu'];  // @codingStandardsIgnoreLine -- this is the best and most efficient way of achieving this
		$submenu = $cache['submenu'];  // @codingStandardsIgnoreLine -- this is the best and most efficient way of achieving this
	}
}

add_action( 'admin_init', 'bazalgette_init' );
