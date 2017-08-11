<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Query article data in the database
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */
class EPKB_Articles_DB {

	// TODO NEXT RELEASE wp_cache_get, wp_cache_set etc. and set_transient/get_transient

	/**
	 * Get PUBLISHED articles related to a given category OR sub-category
	 *
	 * @param $kb_id
	 * @param $sub_or_category_id
	 * @param string $order_by
	 * @param int $nof_articles
	 *
	 * @return array of matching articles or empty array
	 *
	 */
	function get_published_articles_by_sub_or_category( $kb_id, $sub_or_category_id, $order_by='date', $nof_articles=99 ) {

		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			EPKB_Logging::add_log_var( 'Invalid kb id', $kb_id );
			return array();
		}

		if ( ! EPKB_Utilities::is_positive_int($sub_or_category_id) ) {
			EPKB_Logging::add_log_var( 'Invalid category id', $sub_or_category_id );
			return array();
		}

		$query_args = array(
			'post_type' => EPKB_KB_Handler::get_post_type( $kb_id ),
			'post_status' => 'publish',  // we want only published articles
			'posts_per_page' => $nof_articles,
			'orderby' => $order_by,
			'order'=> 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ),
					'terms' => $sub_or_category_id,
					'include_children' => false // Remove if you need posts with child terms
				)
			)
		);

		return get_posts( $query_args );  /** @secure 02.17 */
	}

	/**
	 * Retrieve all KB articles
	 *
	 * @param $kb_id
	 *
	 * @return number of all posts
	 */
	static function get_count_of_all_kb_articles( $kb_id ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		$kb_id = EPKB_Utilities::filter_int( $kb_id, EPKB_KB_Config_DB::DEFAULT_KB_ID );

		// parameters sanitized
		$posts = $wpdb->get_results( " SELECT * " .
									 " FROM $wpdb->posts " . /** @secure 02.17 */
		                             " WHERE post_type = '" . EPKB_KB_Handler::get_post_type( $kb_id ) . "' ");
		if ( empty( $posts ) || ! is_array( $posts ) ) {
			return 0;
		}

		return empty( $posts ) ? 0 : count( $posts );
	}

	/**
	 * Retrieve all PUBLISHED articles that do not have either category or subcategory
	 *
	 * @param $kb_id
	 *
	 * @return array of posts
	 */
	function get_orphan_published_articles( $kb_id ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// sanitize KB ID
		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			EPKB_Logging::add_log_var( 'Invalid kb id', $kb_id );
			return array();
		}

		// parameters sanitized
		$posts = $wpdb->get_results( "SELECT * FROM " .
		                             "   $wpdb->posts p LEFT JOIN " .  /** @secure 02.17 */
	                                 "   (SELECT object_id FROM $wpdb->term_relationships tr INNER JOIN $wpdb->term_taxonomy tt ON tt.term_taxonomy_id = tr.term_taxonomy_id " .
		                                        " WHERE tt.taxonomy = '" . EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ) . "') AS ta " .
		                             "ON ta.object_id = p.ID " .
		                             "WHERE post_type = '" . EPKB_KB_Handler::get_post_type( $kb_id ) . "' AND object_id IS NULL AND post_status in ('publish') ");  // Get only Published articles

		if ( empty( $posts ) || ! is_array( $posts ) ) {
			return array();
		}

		return $posts;
	}
}