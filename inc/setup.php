<?php
/**
 * Setup
 *
 * Various set-up functions.
 *
 * @package bazalgette
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Core menu array
 *
 * Build an array of either core menu slugs or names.
 *
 * @param  string $version Which version of the array to return.
 * @return array           Core menu array.
 */
function bazalgette_core_menu( $version ) {

	if ( 'slugs' === $version ) {
		$core_menus = array(
			'index.php'               => 'Dashboard',
			'edit.php'                => 'Posts',
			'upload.php'              => 'Media',
			'edit.php?post_type=page' => 'Pages',
			'edit-comments.php'       => 'Comments',
			'themes.php'              => 'Appearance',
			'plugins.php'             => 'Plugins',
			'users.php'               => 'Users',
			'tools.php'               => 'Tools',
			'options-general.php'     => 'Settings',
		);
	} else {
		$core_menus = array(
			'Dashboard'  => 'index.php',
			'Posts'      => 'edit.php',
			'Media'      => 'upload.php',
			'Pages'      => 'edit.php?post_type=page',
			'Comments'   => 'edit-comments.php',
			'Appearance' => 'themes.php',
			'Plugins'    => 'plugins.php',
			'Users'      => 'users.php',
			'Tools'      => 'tools.php',
			'Settings'   => 'options-general.php',
		);
	}

	return $core_menus;
}
