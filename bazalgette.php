<?php
/**
 * Bazalgette
 *
 * @package           bazalgette
 * @author            David Artiss
 * @license           GPL-2.0-or-later
 *
 * Plugin Name:       Bazalgette
 * Plugin URI:        https://wordpress.org/plugins/bazalgette/
 * Description:       Automatically tidy up your cluttered admin menus.
 * Version:           1.0
 * Requires at least: 4.6
 * Requires PHP:      x.x
 * Author:            David Artiss
 * Author URI:        https://artiss.blog
 * Text Domain:       bazalgette
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

// Require the various code components - all held within the inc folder.

require_once plugin_dir_path( __FILE__ ) . 'inc/admin.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/menu-clean.php';

require_once plugin_dir_path( __FILE__ ) . 'inc/assign-dashicon.php';
