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

		// Register plugin hooks here.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
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
	 * Register the settings page.
	 *
	 * @return void
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Schema Markup Manager', 'schema-markup-manager' ),
			__( 'Schema Markup Manager', 'schema-markup-manager' ),
			'manage_options',
			'schema-markup-manager',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

			<p>
				<?php
				esc_html_e(
					'Configura el schema markup de tu sitio web.',
					'schema-markup-manager'
				);
				?>
			</p>
		</div>
		<?php
	}
}
