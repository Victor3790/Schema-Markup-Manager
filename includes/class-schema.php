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

		$url   = home_url( '/' );

		$schema = array(
			'@context' => 'https://schema.org',
			'@graph'   => array(
				array(
					'@type'       => 'WebSite',
					'@id'         => $url . '#website',
					'url'         => $url,
					'name'        => 'Mi Comunidad',
					'description' => 'Guía local con información sobre la colonia Mi Comunidad, sus lugares de interés, servicios y negocios.',
					'inLanguage'  => 'es-MX',
					'about'       => array(
						'@id' => $url . '#place',
					),
				),
				array(
					'@type'       => 'WebPage',
					'@id'         => $url . '#webpage',
					'url'         => $url,
					'name'        => 'Mi Comunidad - Guía local',
					'description' => 'Información sobre la colonia Mi Comunidad, su historia, ubicación, lugares de interés y servicios locales.',
					'inLanguage'  => 'es-MX',
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
					'name'    => 'Mi Comunidad',
					'address' => array(
						'@type'           => 'PostalAddress',
						'addressLocality' => 'Ciudad de México',
						'addressRegion'   => 'Ciudad de México',
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
