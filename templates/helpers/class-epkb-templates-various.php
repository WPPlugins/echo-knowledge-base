<?php

/**
 * Handle loading EP templates
 *
 * Adapted from code in EDD/WooCommmerce (Copyright (c) 2017, Pippin Williamson) and WP.
 */
class EPKB_Templates_Various {

	/**
	 * BREADCRUMB: get given article breadccrumb categories
	 *
	 * @param $kb_config
	 * @param $article_id
	 * @return array
	 */
	public static function get_article_breadcrumb( $kb_config, $article_id ) {

		if ( isset($kb_config[EPKB_Articles_Admin::KB_ARTICLES_SEQ_META]) && isset($kb_config[EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META]) ) {
			$articles_seq_data = $kb_config[EPKB_Articles_Admin::KB_ARTICLES_SEQ_META];
			$category_seq_data = $kb_config[EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META];
		} else {
			$articles_seq_data = EPKB_Utilities::get_kb_option( $kb_config['id'], EPKB_Articles_Admin::KB_ARTICLES_SEQ_META, array(), true );
			$category_seq_data = EPKB_Utilities::get_kb_option( $kb_config['id'], EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META, array(), true );
		}

		$seq_no = empty($_REQUEST['seq_no']) ? 1 : EPKB_Utilities::filter_int( $_REQUEST['seq_no'] );
		$seq_cnt = 0;
		$first_instance = array();

		// find it on the first level
		foreach( $category_seq_data as $category_id => $sub_categories ) {

			if ( empty($articles_seq_data[$category_id][0]) ) {
				return array();
			}

			if ( isset($articles_seq_data[$category_id][$article_id]) ) {
				$result = array($category_id => $articles_seq_data[$category_id][0]);
				if ( ++$seq_cnt >= $seq_no ) {
					return $result;
				};
				$first_instance = empty($first_instance) ? $result : $first_instance;
			}

			// find it on the second level
			foreach( $sub_categories as $sub_category_id => $sub_sub_categories ) {

				if ( empty($articles_seq_data[$sub_category_id][0]) ) {
					return array();
				}

				if ( isset($articles_seq_data[$sub_category_id][$article_id]) ) {
					$result = array($category_id => $articles_seq_data[$category_id][0],
					                $sub_category_id => $articles_seq_data[$sub_category_id][0]);
					if ( ++$seq_cnt >= $seq_no ) {
						return $result;
					};
					$first_instance = empty($first_instance) ? $result : $first_instance;
				}

				// find it on the third level
				foreach( $sub_sub_categories as $sub_sub_category_id => $sub_sub_sub_categories ) {

					if ( empty($articles_seq_data[$sub_sub_category_id][0]) ) {
						return array();
					}

					if ( isset($articles_seq_data[$sub_sub_category_id][$article_id]) ) {
						$result = array($category_id => $articles_seq_data[$category_id][0],
						                $sub_category_id => $articles_seq_data[$sub_category_id][0],
						                $sub_sub_category_id => $articles_seq_data[$sub_sub_category_id][0]);
						if ( ++$seq_cnt >= $seq_no ) {
							return $result;
						};
						$first_instance = empty($first_instance) ? $result : $first_instance;
					}
				}
			}
		}

		return $first_instance;
	}
}