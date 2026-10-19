<?php
/**
 * Generate and output schema markup.
 *
 * @package Schema_Markup_Manager
 * @since   1.0.0
 */

namespace Schema_Markup_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

require_once PLUGIN_DIR . 'includes/class-seo.php';

/**
 * The schema class is responsible for generating and outputting schema markup in JSON-LD format.
 */
class Schema {

	/**
	 * Output JSON-LD on the front page.
	 *
	 * @return void
	 */
	public static function output_home_schema() {
		if ( ! is_front_page() ) {
			return;
		}

		$url              = SEO::get_url();
		$title            = SEO::get_site_name();
		$tagline          = SEO::get_tagline();
		$language         = SEO::get_language();
		$page_title       = SEO::get_title();
		$page_description = SEO::get_description();

		$options    = get_option( 'schema_markup_manager_options', array() );
		$place_name = isset( $options['place_name'] )
						&& is_string( $options['place_name'] )
						? $options['place_name']
						: '';

		$address_locality = isset( $options['place_address_locality'] )
						&& is_string( $options['place_address_locality'] )
						? $options['place_address_locality']
						: '';

		$address_region = isset( $options['place_address_region'] )
						&& is_string( $options['place_address_region'] )
						? $options['place_address_region']
						: '';

		$schema = array(
			'@context' => 'https://schema.org',
			'@graph'   => array(
				array(
					'@type'       => 'WebSite',
					'@id'         => $url . '#website',
					'url'         => $url,
					'name'        => $title,
					'description' => $tagline,
					'inLanguage'  => $language,
					'about'       => array(
						'@id' => $url . '#place',
					),
				),
				array(
					'@type'       => 'WebPage',
					'@id'         => $url . '#webpage',
					'url'         => $url,
					'name'        => $page_title,
					'description' => $page_description,
					'inLanguage'  => $language,
					'isPartOf'    => array(
						'@id' => $url . '#website',
					),
					'about'       => array(
						'@id' => $url . '#place',
					),
				),
				array(
					'@type'   => 'Place',
					'@id'     => $url . '#place',
					'name'    => $place_name,
					'address' => array(
						'@type'           => 'PostalAddress',
						'addressLocality' => $address_locality,
						'addressRegion'   => $address_region,
						'addressCountry'  => 'MX',
					),
				),
			),
		);

		$json = wp_json_encode(
			$schema,
			JSON_UNESCAPED_UNICODE
			| JSON_UNESCAPED_SLASHES
			| JSON_HEX_TAG
			| JSON_HEX_AMP
			| JSON_HEX_APOS
			| JSON_HEX_QUOT
		);

		if ( false === $json ) {
			return;
		}

		// JSON is encoded with HTML-safe flags for the script context.
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<script type="application/ld+json">' . $json . '</script>' . "\n";
	}
}
