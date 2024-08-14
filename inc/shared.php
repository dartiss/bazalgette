<?php
/**
 * Shared Functions
 *
 * A group of functions shared across my plugins, for consistency.
 *
 * @package bazalgette
 */

// Exit if accessed directly.

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add meta to plugin details
 *
 * Add options to plugin meta line
 *
 * @version  1.1
 * @param    string $links  Current links.
 * @param    string $file   File in use.
 * @return   string         Links, now with settings added.
 */
function bazalgette_plugin_meta( $links, $file ) {

	if ( false !== strpos( $file, 'bazalgette.php' ) ) {

		$links = array_merge(
			$links,
			array( '<a href="https://github.com/dartiss/bazalgette">' . __( 'Github', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/bazalgette">' . __( 'Support', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/bazalgette/reviews/?filter=5" title="' . __( 'Rate the plugin on WordPress.org', 'bazalgette' ) . '" style="color: #ffb900">' . str_repeat( '<span class="dashicons dashicons-star-filled" style="font-size: 16px; width:16px; height: 16px"></span>', 5 ) . '</a>' ),
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'bazalgette_plugin_meta', 10, 2 );

/**
 * WordPress Requirements Check
 *
 * Deactivate the plugin if certain requirements are not met.
 *
 * @version 1.1
 */
function bazalgette_requirements_check() {

	$reason = '';

	// Grab the plugin details.

	$plugins = get_plugins();
	$name    = $plugins[ BAZALGETTE_PLUGIN_BASE ]['Name'];

	// Check for a fork.

	if ( function_exists( 'calmpress_version' ) || function_exists( 'classicpress_version' ) ) {

		/* translators: 1: The plugin name. */
		$reason .= '<li>' . sprintf( __( 'A fork of WordPress was detected. %1$s has not been tested on this fork and, as a consequence, the author will not provide any support.', 'bazalgette' ), $name ) . '</li>';

	}

	// If a requirement is not met, output the message and stop the plugin.

	if ( '' !== $reason ) {

		// Deactivate this plugin.

		deactivate_plugins( BAZALGETTE_PLUGIN_BASE );

		// Set up a message and output it via wp_die.

		/* translators: 1: The plugin name. */
		$message = '<p><b>' . sprintf( __( '%1$s has been deactivated', 'bazalgette' ), $name ) . '</b></p><p>' . __( 'Reason:', 'bazalgette' ) . '</p><ul>' . $reason . '</ul>';

		$allowed = array(
			'p'  => array(),
			'b'  => array(),
			'ul' => array(),
			'li' => array(),
		);

		wp_die( wp_kses( $message, $allowed ), '', array( 'back_link' => true ) );
	}
}

add_action( 'admin_init', 'bazalgette_requirements_check' );
