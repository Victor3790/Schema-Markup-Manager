<?php
/**
 * Provide SEO data from Yoast or WordPress.
 *
 * @package Schema_Markup_Manager
 * @since   1.0.0
 */

namespace Schema_Markup_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * The SEO class is responsible for retrieving SEO data from Yoast or WordPress.
 */
class SEO {

	/**
	 * Get Yoast's metadata for the current page.
	 *
	 * Call after WordPress has resolved the main query.
	 *
	 * @return object|null
	 */
	private static function get_presentation() {
		if ( ! function_exists( 'YoastSEO' ) ) {
			return null;
		}

		$presentation = \YoastSEO()->meta->for_current_page();

		return is_object( $presentation ) ? $presentation : null;
	}

	/**
	 * Get the current page title.
	 *
	 * @return string
	 */
	public static function get_title() {
		$presentation = self::get_presentation();

		if (
			null !== $presentation
			&& is_string( $presentation->title )
			&& '' !== trim( $presentation->title )
		) {
			return $presentation->title;
		}

		return wp_get_document_title();
	}

	/**
	 * Get the current page description.
	 *
	 * @return string
	 */
	public static function get_description() {
		$presentation = self::get_presentation();

		if (
			null !== $presentation
			&& is_string( $presentation->description )
			&& '' !== trim( $presentation->description )
		) {
			return $presentation->description;
		}

		if ( is_front_page() ) {
			return trim( get_bloginfo( 'description', 'raw' ) );
		}

		if ( is_singular() ) {
			$post = get_queried_object();

			if (
				$post instanceof \WP_Post
				&& '' !== trim( $post->post_excerpt )
			) {
				return trim(
					wp_strip_all_tags( $post->post_excerpt, true )
				);
			}
		}

		return '';
	}
}
