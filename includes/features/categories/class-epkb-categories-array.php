<?php

/**
 * Handle manipulation of tree of IDs
 *
 *  [cat term id] -> []
 *  [cat term id] -> []
 *  [cat term id]
 *     ->  [sub-cat term id] -> []
 *     ->  [sub-cat term id]
 *             -> [sub-sub-cat term id] -> []
 *     ->  [sub-cat term id] -> []
 *
 */
class EPKB_Categories_Array {

	var $ids_array;

	public function __construct( array $cat_sequences ) {
		$this->ids_array = $cat_sequences;
		$this->normalize_and_sanitize();
	}

	public function normalize_and_sanitize() {
		if ( empty($this->ids_array) || ! is_array($this->ids_array) ) {
			$this->ids_array = array();
		}
		$this->normalize_recursive( $this->ids_array );
	}

	private function normalize_recursive( &$array, &$level=0 ) {
		$level++;
		foreach ($array as $key => &$value) {

			if ( ! EPKB_Utilities::is_positive_int( $key ) ||
			     ( ! empty($value) && ! is_array($value) ) ) {
				unset($array[$key]);
				continue;
			}

			if ( is_array($value) ) {
				if ( $level < 7 ) {
					$this->normalize_recursive( $value, $level );
					$level--;
				} else {
					unset($array[$key]);
				}
			}
		}
	}

	public function get_all_keys() {
		$keys = array();
		$this->get_all_keys_recursive( $this->ids_array, $keys );
		return $keys;
	}

	private function get_all_keys_recursive( $array, &$keys, &$level=0 ) {
		$level++;
		foreach ($array as $key => $value) {
			$keys[$key] = $level;
			if ( is_array($value) ) {
				if ( $level < 7 ) {
					$this->get_all_keys_recursive( $value, $keys, $level );
					$level--;
				}
			}
		}
		return $keys;
	}

	public function get_all_leafs() {
		$keys = array();
		$this->get_all_leafs_recursive( $this->ids_array, $keys );
		return $keys;
	}

	private function get_all_leafs_recursive( $array, &$keys, &$level=0 ) {
		$level++;
		foreach ($array as $key => $value) {
			if ( ! empty($value) ) {
				if ( $level < 7 ) {
					$this->get_all_leafs_recursive( $value, $keys, $level );
					$level--;
				}
			} else {
				$keys[] = $key;
			}
		}
		return $keys;
	}

	public function &get_parent_category_reference( $parent_category_id, $parent_level ) {
		if ( $parent_level == 0 ) {
			return $this->ids_array;
		}
		return $this->get_category_reference_recursive( $parent_category_id, $parent_level, $this->ids_array );
	}

	private function &get_category_reference_recursive( $parent_category_id, $parent_level, &$categories_data, &$level=0 ) {
		$level ++;
		$null_array = null;
		foreach ( $categories_data as $category_id => $sub_categories ) {
			// if we are on the level then see if we found the category
			if ( $parent_level == $level ) {
				if ( $parent_category_id == $category_id ) {
					return $categories_data;
				}
				continue;
			}

			// next level 2
			if ( ! is_array( $sub_categories ) ) {
				return $null_array;
			}

			if ( ! empty($sub_categories) && $level < 7 ) {
				$result = $this->get_category_reference_recursive( $parent_category_id, $parent_level, $sub_categories, $level );
				if ( $result !== null ) {
					return $result;
				}
				$level --;
			}
		}

		// did not find it
		return $null_array;
	}
}