<?php
/**
 * Assign a Dashicon
 *
 * Functions to assign a Dashicon to menus
 *
 * @package bazalgette
 */

/**
 * Allocate an icon
 *
 * For menu icons with non-SVG icons, allocate a new one.
 * Dashicon information can be found at https://developer.wordpress.org/resource/dashicons/
 *
 * @param  string $menu_title  The menu title.
 * @param  string $page_title  The page title.
 * @return string              The Dashicon to use.
 */
function bazalgette_allocate_icon( $menu_title, $page_title ) {

	// Set the default (if all else fails) icon!
	$icon = 'dashicons-admin-generic';

	// Convert text to lower case to ensure any case is matched.
	$menu_title = strtolower( $menu_title );
	$page_title = strtolower( $page_title );

	// Create an array of possible substitutions.
	$icon_array = array(
		'Security' => array(
			'check' => 'security',
			'icon'  => 'dashicons-privacy',
		),
		'SEO'      => array(
			'check' => 'seo',
			'icon'  => 'dashicons-search',
		),
		'Blocks'   => array(
			'check' => 'blocks',
			'icon'  => 'dashicons-screenoptions',
		),
	);

	// Process the above array - if the 'check' text is found, use the 'icon'.
	foreach ( $icon_array as $array ) { 
		if ( strpos( $menu_title, $array['check'] ) !== false ) {
			$icon = $array['icon'];
		}
	}

	return $icon;
}
