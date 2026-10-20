<?php
/**
 * Main class for the Schema Markup Manager plugin.
 *
 * @package Schema_Markup_Manager
 * @since   1.0.0
 */

namespace Schema_Markup_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Initialize the plugin and register its hooks.
 */
class Main {

	/**
	 * The plugin instance.
	 *
	 * @var Main|null
	 */
	private static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		$this->define_constants();

		// Include classes.
		require_once PLUGIN_DIR . 'includes/class-schema.php';
		require_once PLUGIN_DIR . 'includes/class-settings.php';

		// Register plugin hooks here.
		add_action( 'admin_menu', array( Settings::class, 'add_page' ) );
		add_action( 'admin_init', array( Settings::class, 'register_options' ) );
		add_action( 'wp_head', array( $this, 'output_schema' ) );

		add_filter( 'wpseo_json_ld_output', '__return_false' );
	}

	/**
	 * Get the plugin instance.
	 *
	 * @return Main
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Define the plugin constants.
	 *
	 * @return void
	 */
	private function define_constants() {
		if ( ! defined( __NAMESPACE__ . '\VERSION' ) ) {
			define( __NAMESPACE__ . '\VERSION', '1.0.0' );
		}

		if ( ! defined( __NAMESPACE__ . '\PLUGIN_DIR' ) ) {
			define(
				__NAMESPACE__ . '\PLUGIN_DIR',
				plugin_dir_path( SCHEMA_MARKUP_MANAGER_PLUGIN_FILE )
			);
		}
	}

	/**
	 * Handle the schmema markup output depending on the page.
	 */
	public function output_schema() {
		if ( is_front_page() ) {
			Schema::output_home_schema();
		} elseif ( is_page( 'directorio' ) ) {
			Schema::output_directory_schema();
		}
	}
}
