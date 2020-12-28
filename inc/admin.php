<?php
/**
 * Admin functions
 *
 * Various functions around setting up admin options
 *
 * @package bazalgette
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

	if ( false !== strpos( $file, 'bazalgette.php' ) ) {

		$links = array_merge(
			$links,
			array( '<a href="https://github.com/dartiss/bazalgette">' . __( 'Github', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/bazalgette">' . __( 'Support', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://artiss.blog/donate">' . __( 'Donate', 'bazalgette' ) . '</a>' ),
			array( '<a href="https://wordpress.org/support/plugin/bazalgette/reviews/#new-post">' . __( 'Write a Review', 'bazalgette' ) . '&nbsp;⭐️⭐️⭐️⭐️⭐️</a>' )
		);
	}

	return $links;
}

add_filter( 'plugin_row_meta', 'bazalgette_plugin_meta', 10, 2 );
