<?php
/**
 * Handles the settings for the plugin
 *
 * @package Schema_Markup_Manager
 * @since   1.0.0
 */

namespace Schema_Markup_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * This class is responsible for handling the settings and the rendering
 * of the option fields.
 */
class Settings {
	/**
	 * Register the settings page.
	 *
	 * @return void
	 */
	public static function add_page() {
		add_options_page(
			__( 'Schema Markup Manager', 'schema-markup-manager' ),
			__( 'Schema Markup Manager', 'schema-markup-manager' ),
			'manage_options',
			'schema-markup-manager',
			array( self::class, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
			<?php
			settings_fields( 'schema_markup_manager' );
			do_settings_sections( 'schema-markup-manager' );
			submit_button();
			?>
			</form>
		</div>
		<?php
	}

	/**
	 * Register plugin settings, sections, and fields.
	 *
	 * Hooked to admin_init.
	 *
	 * @return void
	 */
	public static function register_options() {
		register_setting(
			'schema_markup_manager',
			'schema_markup_manager_options',
			array(
				'type'              => 'array',
				'sanitize_callback' => array( self::class, 'sanitize_settings' ),
				'default'           => array(),
			)
		);

		// Register the place section and its fields.
		add_settings_section(
			'schema_markup_manager_place',
			__( 'Place', 'schema-markup-manager' ),
			'__return_false',
			'schema-markup-manager'
		);

		add_settings_field(
			'place_name',
			__( 'Name', 'schema-markup-manager' ),
			array( self::class, 'render_place_name_field' ),
			'schema-markup-manager',
			'schema_markup_manager_place',
			array(
				'label_for' => 'schema_markup_manager_place_name',
			)
		);

		add_settings_field(
			'place_address_locality',
			__( 'Address Locality', 'schema-markup-manager' ),
			array( self::class, 'render_place_address_locality_field' ),
			'schema-markup-manager',
			'schema_markup_manager_place',
			array(
				'label_for' => 'schema_markup_manager_place_address_locality',
			)
		);

		add_settings_field(
			'place_address_region',
			__( 'Address Region', 'schema-markup-manager' ),
			array( self::class, 'render_place_address_region_field' ),
			'schema-markup-manager',
			'schema_markup_manager_place',
			array(
				'label_for' => 'schema_markup_manager_place_address_region',
			)
		);

		add_settings_field(
			'place_address_country',
			__( 'Address Country', 'schema-markup-manager' ),
			array( self::class, 'render_place_address_country_field' ),
			'schema-markup-manager',
			'schema_markup_manager_place',
			array(
				'label_for' => 'schema_markup_manager_place_address_country',
			)
		);
	}

	/**
	 * Sanitize plugin settings before saving.
	 *
	 * @param mixed $input Submitted settings.
	 * @return array Sanitized settings.
	 */
	public static function sanitize_settings( $input ) {
		$options = get_option( 'schema_markup_manager_options', array() );
		$options = is_array( $options ) ? $options : array();

		$options['place_name'] = (
			is_array( $input )
			&& isset( $input['place_name'] )
			&& is_string( $input['place_name'] )
		)
			? sanitize_text_field( $input['place_name'] )
			: '';

		$options['place_address_locality'] = (
			is_array( $input )
			&& isset( $input['place_address_locality'] )
			&& is_string( $input['place_address_locality'] )
		)
			? sanitize_text_field( $input['place_address_locality'] )
			: '';

		$options['place_address_region'] = (
			is_array( $input )
			&& isset( $input['place_address_region'] )
			&& is_string( $input['place_address_region'] )
		)
			? sanitize_text_field( $input['place_address_region'] )
			: '';

		$country = (
			is_array( $input )
			&& isset( $input['place_address_country'] )
			&& is_string( $input['place_address_country'] )
		)
			? strtoupper( trim( sanitize_text_field( $input['place_address_country'] ) ) )
			: '';

		if ( '' === $country || preg_match( '/^[A-Z]{2}$/D', $country ) ) {
			$options['place_address_country'] = $country;
		} else {
			add_settings_error(
				'schema_markup_manager_options',
				'invalid_place_address_country',
				__(
					'Country must be a two-letter code, such as MX.',
					'schema-markup-manager'
				)
			);
		}

		return $options;
	}

	/**
	 * Render the place name field.
	 *
	 * @return void
	 */
	public static function render_place_name_field() {
		$options = get_option( 'schema_markup_manager_options', array() );
		$value   = isset( $options['place_name'] )
			&& is_string( $options['place_name'] )
			? $options['place_name']
			: '';
		?>
		<input
			type="text"
			id="schema_markup_manager_place_name"
			name="schema_markup_manager_options[place_name]"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		>
		<?php
	}

	/**
	 * Render the place address locality field.
	 *
	 * @return void
	 */
	public static function render_place_address_locality_field() {
		$options = get_option( 'schema_markup_manager_options', array() );
		$value   = isset( $options['place_address_locality'] )
			&& is_string( $options['place_address_locality'] )
			? $options['place_address_locality']
			: '';
		?>
		<input
			type="text"
			id="schema_markup_manager_place_address_locality"
			name="schema_markup_manager_options[place_address_locality]"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		>
		<?php
	}

	/**
	 * Render the place address region field.
	 *
	 * @return void
	 */
	public static function render_place_address_region_field() {
		$options = get_option( 'schema_markup_manager_options', array() );
		$value   = isset( $options['place_address_region'] )
			&& is_string( $options['place_address_region'] )
			? $options['place_address_region']
			: '';
		?>
		<input
			type="text"
			id="schema_markup_manager_place_address_region"
			name="schema_markup_manager_options[place_address_region]"
			value="<?php echo esc_attr( $value ); ?>"
			class="regular-text"
		>
		<?php
	}

	/**
	 * Render the place address country field.
	 *
	 * @return void
	 */
	public static function render_place_address_country_field() {
		$options = get_option( 'schema_markup_manager_options', array() );
		$value   = isset( $options['place_address_country'] )
			&& is_string( $options['place_address_country'] )
			? $options['place_address_country']
			: '';
		?>
		<input
			type="text"
			id="schema_markup_manager_place_address_country"
			name="schema_markup_manager_options[place_address_country]"
			value="<?php echo esc_attr( $value ); ?>"
			maxlength="2"
			pattern="[A-Za-z]{2}"
			aria-describedby="schema_markup_manager_place_address_country_description"
			class="small-text"
		>
		<p
			id="schema_markup_manager_place_address_country_description"
			class="description"
		>
			<?php
			esc_html_e(
				'Enter the two-letter ISO 3166-1 country code, such as MX.',
				'schema-markup-manager'
			);
			?>
		</p>
		<?php
	}
}
