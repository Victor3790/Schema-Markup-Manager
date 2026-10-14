<?php
/**
 * Plugin Name: Schema Markup Manager
 * Description: A WordPress plugin for adding and managing schema markup.
 * Version: 1.0.0
 * Author: Victor Crespo
 * Author URI: https://victorcrespo.net
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Schema_Markup_Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Define the plugin file path.
if ( ! defined( 'SCHEMA_MARKUP_MANAGER_PLUGIN_FILE' ) ) {
	define( 'SCHEMA_MARKUP_MANAGER_PLUGIN_FILE', __FILE__ );
}

require_once 'includes/class-main.php';

$schema_markup_manager = Schema_Markup_Manager\Main::get_instance();
