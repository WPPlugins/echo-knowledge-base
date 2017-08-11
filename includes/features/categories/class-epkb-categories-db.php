<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Query categories data in the database
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */
class EPKB_Categories_DB {

	/**
	 * Get all top-level categories
	 *
	 * @param $kb_id
	 * @param string $hide_choice - if 'hide_empty' then do not return empty categories
	 *
	 * @return array or empty array on error
	 *
	 */
	function get_top_level_categories( $kb_id, $hide_choice='hide_empty' ) {

		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			EPKB_Logging::add_log_var( 'Invalid kb id', $kb_id );
			return array();
		}

		$args = array(
				'parent'        => '0',
				'hide_empty'    => $hide_choice === 'hide_empty'
		);

		$terms = get_terms( EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ), $args );
		if ( is_wp_error( $terms ) ) {
			EPKB_Logging::add_log_var( 'cannot get terms for kb_id', $kb_id, $terms );
			return array();
		} else if ( empty($terms) || ! is_array($terms) ) {
			return array();
		}

		return array_values($terms);   // rearrange array keys
	}

	/**
	 * Get all categories that belong to given parent
	 *
	 * @param $kb_id
	 * @param int $parent_id is parent category we use to find children
	 * @param string $hide_choice
	 *
	 * @return array or empty array on error
	 */
	function get_child_categories( $kb_id, $parent_id, $hide_choice='hide_empty' ) {

		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			EPKB_Logging::add_log_var( 'Invalid kb id', $kb_id );
			return array();
		}

		if ( ! EPKB_Utilities::is_positive_int( $parent_id ) ) {
			EPKB_Logging::add_log_var( 'Invalid parent id', $parent_id );
			return array();
		}

		$args = array(
				'child_of'      => $parent_id,
				'parent'        => $parent_id,
				'hide_empty'    => $hide_choice === 'hide_empty'
		);

		$terms = get_terms( EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ), $args );
		if ( is_wp_error( $terms ) ) {
			EPKB_Logging::add_log_var( 'failed to get terms for kb_id: ' . $kb_id . ', parent_id: ' . $parent_id, $terms );
			return array();
		}

		if ( empty( $terms ) || ! is_array( $terms ) ) {
			return array();
		}

		return array_values($terms);
	}

	/**
	 * Get all existing KB categories for given taxonomy.
	 *
	 * @param $kb_id
	 * @param string $order_by
	 * @return array|null
	 */
	public static function get_kb_categories( $kb_id, $order_by='name' ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$order_by = $order_by == 'date' ? 'term_id' : $order_by;   // terms don't have date so use id
		$kb_category_taxonomy_name = EPKB_KB_Handler::get_category_taxonomy_name( $kb_id );
		$result = $wpdb->get_results( $wpdb->prepare("SELECT t.*, tt.*
												   FROM $wpdb->terms AS t INNER JOIN $wpdb->term_taxonomy AS tt ON t.term_id = tt.term_id
												   WHERE tt.taxonomy IN (%s) ORDER BY " . esc_sql('t.' . $order_by) . " ASC", $kb_category_taxonomy_name ) );
		return isset($result) && is_array($result) ? $result : null;
	}
}