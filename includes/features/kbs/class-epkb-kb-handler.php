<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle operations on knowledge base such as adding, deleting and updating KB
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */
class EPKB_KB_Handler {

	// name of KB shortcode
	const KB_MAIN_PAGE_SHORTCODE_NAME = 'epkb-knowledge-base'; // changing this requires db update

	// Prefix for custom post type name associated with given KB; this will never change
	const KB_POST_TYPE_PREFIX = 'epkb_post_type_';  // changing this requires db update
	const KB_CATEGORY_TAXONOMY_SUFFIX = '_category';  // changing this requires db update; do not translate
	const KB_TAG_TAXONOMY_SUFFIX = '_tag'; // changing this requires db update; do not translate

	/**
	 * Get KB slug based on default KB name and ID. Default KB has slug without ID.
	 *
	 * @param $kb_id
	 *
	 * @return string
	 */
	public static function get_default_slug( $kb_id ) {
		/* translators: do NOT change this translation again. It will break links !!! */
		return sanitize_title_with_dashes( _x( 'Knowledge Base', 'slug', 'echo-knowledge-base' ) . ( $kb_id == EPKB_KB_Config_DB::DEFAULT_KB_ID ? '' : '-' . $kb_id ) );
	}

	/**
	 * Create a new Knowledge Base using default configuration when:
	 *  a) plugin is installed
	 *  b) user clicks on 'Add Knowledge Base' button (requires Multiple KBs add-on)
	 *
	 * First default knowledge base has name 'Knowledge Base' with ID 1
	 * Add New KB will create KB with pre-set name 'Knowledge Base 2' with ID 2 and so on.
	 *
	 * If missing add:
	 * - first category
	 * - first article
	 * - first KB main page
	 *
	 * @param int $new_kb_id - ID of the new KB
	 *
	 * @return array|WP_Error the new KB configuration or WP_Error
	 */
	public static function add_new_knowledge_base( $new_kb_id ) {

		$kb_category_taxonomy_name = self::get_category_taxonomy_name( $new_kb_id );

		// 1. register custom post type for this knowledge base; we don't need to do this now as this
		//  is done every time plugin is loaded but we want to see if there is no issue doing so before we
		//  finish creating knowledge base
		$error = EPKB_Articles_CPT_Setup::register_custom_post_type( $new_kb_id, $new_kb_id );
		if( is_wp_error( $error ) ) {
			EPKB_Logging::add_log_var("Could not register post type when adding a new KB", $new_kb_id, $error);
			// ignore error and try to continue
		}

		// 2. find or add KB configuration
		$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $new_kb_id );
		if ( is_wp_error( $kb_config ) ) {
			$kb_config = EPKB_KB_Config_Specs::get_default_kb_config( $new_kb_id );
		}

		// 3. Add a sample category if none exists
		$kb_term_id = '';
		$all_kb_terms = EPKB_Categories_DB::get_kb_categories( $new_kb_id );
		if ( $all_kb_terms !== null && empty($all_kb_terms) ) {
			$term_id_array = wp_insert_term( __( $kb_config['default_category_name'], 'echo-knowledge-base' ), $kb_category_taxonomy_name );
			if ( is_wp_error($term_id_array) ) {
				EPKB_Logging::add_log_var( 'Cound not insert default category for new KB', $new_kb_id, $term_id_array );
				// ignore error and try to continue
			} else {
				$kb_term_id = $term_id_array['term_id'];
				EPKB_Utilities::save_kb_option( $new_kb_id, EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META, array( $kb_term_id => '' ), true );
			}
		}

		// 4. Add a sample article if none exists
		$all_kb_articles = EPKB_Articles_DB::get_count_of_all_kb_articles( $new_kb_id );
		if ( empty($all_kb_articles) ) {
			$my_post = array(
				'post_title'    => __( $kb_config['sample_article_title'], 'echo-knowledge-base' ),
				'post_type'     => self::get_post_type( $new_kb_id ),
				'post_content'  => __( $kb_config['empty_article_content'], 'echo-knowledge-base' ),
				'post_status'   => 'publish',
				// current user or 'post_author'   => 1,
			);
			$post_id = wp_insert_post( $my_post );

			// add category to the new post
			if ( ! is_wp_error( $post_id ) && ! empty($post_id) && ! empty($kb_term_id) ) {
				wp_set_object_terms( $post_id, $kb_term_id, $kb_category_taxonomy_name );
			}

			$articles_array = array( $kb_term_id => array(
				                                        '0' => __( $kb_config['default_category_name'], 'echo-knowledge-base' ),
				                                        '1' => '',
			                                            $post_id => __( $kb_config['sample_article_title'], 'echo-knowledge-base' ) ) );
			EPKB_Utilities::save_kb_option( $new_kb_id, EPKB_Articles_Admin::KB_ARTICLES_SEQ_META, $articles_array, true );
		}

		// 5. Add first KB Main page if none exists; first KB is just called Knowledge Base
		$post_title = __( 'Knowledge Base', 'echo-knowledge-base' ) . ( $new_kb_id == EPKB_KB_Config_DB::DEFAULT_KB_ID ? '' : ' ' . $new_kb_id );
		$kb_main_pages = $kb_config['kb_main_pages'];
		if ( empty($kb_main_pages) ) {
			$my_post = array(
				'post_title'    => $post_title,
				'post_type'     => 'page',
				'post_content'  => '[' . self::KB_MAIN_PAGE_SHORTCODE_NAME . ' id=' . $new_kb_id . ']',
				'post_status'   => 'publish',
				// current user or 'post_author'   => 1,
			);
			$post_id = wp_insert_post( $my_post );
			if ( ! is_wp_error( $post_id ) && ! empty($post_id) ) {
				$post = WP_Post::get_instance( $post_id );
				$kb_main_pages[ $post_id ] = $post->post_title;
				$kb_config['kb_main_pages'] = $kb_main_pages;
				$kb_config['kb_articles_common_path'] = $post->post_name;
			}
		}

		// 6. save new/updated KB configuration
		epkb_get_instance()->kb_config_ojb->update_kb_configuration( $new_kb_id, $kb_config );  // ignore errors

		return $kb_config;
	}

	/**
	 * Retrieve current KB ID based on post_type value in URL based on user request etc.
	 *
	 * @return String | <empty> if not found
	 */
	public static function get_current_kb_id() {

		global $current_screen;

		// 1. retrieve current post being used and if user selected a tab for specific KB
		$kb_id = new WP_Error('unknown KB ID.');
		if ( ! empty($_REQUEST['post_type']) ) {
			$kb_id = self::get_kb_id_from_post_type( $_REQUEST['post_type'] );
		}

		if ( is_wp_error( $kb_id ) && ! empty($_REQUEST['taxonomy']) ) {
			$kb_id = self::get_kb_id_from_category_taxonomy_name( $_REQUEST['taxonomy'] );
		}

		if ( is_wp_error( $kb_id ) && ! empty($_REQUEST['taxonomy']) ) {
			$kb_id = self::get_kb_id_from_tag_taxonomy_name( $_REQUEST['taxonomy'] );
		}

		if ( is_wp_error( $kb_id ) && ! empty($current_screen->post_type) ) {
			$kb_id = self::get_kb_id_from_post_type( $current_screen->post_type );
		}

		// e.g. when adding category within KB article
		if ( is_wp_error( $kb_id ) && ! empty($_REQUEST['action']) && strpos( $_REQUEST['action'], self::KB_POST_TYPE_PREFIX ) !== false ) {
			$found_kb_id = EPKB_Utilities::filter_number( $_REQUEST['action'] );
			if ( EPKB_Utilities::is_positive_int( $found_kb_id ) ) {
				$kb_id = $found_kb_id;
			}
		}

		if ( is_wp_error( $kb_id ) && ! empty($_REQUEST['epkb_kb_id']) && EPKB_Utilities::is_positive_int( $_REQUEST['epkb_kb_id'] )) {
			$kb_id = $_REQUEST['epkb_kb_id'];
		}

		// when editing article
		if ( is_wp_error( $kb_id ) && ! empty($_REQUEST['action']) && $_REQUEST['action'] == 'edit' && ! empty($_REQUEST['post']) && EPKB_Utilities::is_positive_int( $_REQUEST['post'] )) {
			$post = EPKB_Utilities::get_post_secure( $_REQUEST['post'] );
			if ( ! empty($post) && EPKB_KB_Handler::is_kb_post_type( $post->post_type )) {
				$kb_id = self::get_kb_id_from_post_type( $post->post_type );
			}
		}

		if ( empty($kb_id) || is_wp_error( $kb_id ) || ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			return '';
		}

		// 2. check if the "current id" belongs to one of the existing KBs
		if ( $kb_id != EPKB_KB_Config_DB::DEFAULT_KB_ID ) {
			$db_kb_config = new EPKB_KB_Config_DB();
			$kb_ids = $db_kb_config->get_kb_ids();
			if ( ! in_array( $kb_id, $kb_ids ) ) {
				EPKB_Logging::add_log_var("Found current KB ID to be unknown", $kb_id);
				return '';
			}
		}

		return $kb_id;
	}

	/**
	 * Is this KB post type?
	 *
	 * @param $post_type
	 * @return bool
	 */
	public static function is_kb_post_type( $post_type ) {
		if ( empty($post_type) || ! is_string($post_type)) {
			return false;
		}
		// we are only interested in KB articles
		return strncmp($post_type, self::KB_POST_TYPE_PREFIX, strlen(self::KB_POST_TYPE_PREFIX)) == 0;
	}

	/**
	 * Is this KB taxonomy?
	 *
	 * @param $taxonomy
	 * @return bool
	 */
	public static function is_kb_taxonomy( $taxonomy ) {
		if ( empty($taxonomy) || ! is_string($taxonomy) ) {
			return false;
		}
		// we are only interested in KB articles
		return strncmp($taxonomy, self::KB_POST_TYPE_PREFIX, strlen(self::KB_POST_TYPE_PREFIX)) == 0;
	}

	/**
	 * Does request have KB taxonomy or post type ?
	 *
	 * @return bool
	 */
	public static function is_kb_request() {
		$is_kb_post_type = isset($_REQUEST['post_type']) && self::is_kb_post_type( $_REQUEST['post_type'] );
		$is_kb_taxonomy = isset($_REQUEST['taxonomy']) && self::is_kb_taxonomy( $_REQUEST['taxonomy'] );
		return $is_kb_post_type || $is_kb_taxonomy;
	}

	/**
	 * Retrieve current KB post type based on post_type value in URL based on user request etc.
	 *
	 * @return String | <empty> if valid post type not found
	 */
	public static function get_current_kb_post_type() {
		$kb_id = self::get_current_kb_id();
		if ( empty( $kb_id ) ) {
			return '';
		}
		return self::get_post_type( $kb_id );
	}

	/**
	 * Retrieve KB post type name e.g. epkb_post_type_1
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_post_type( $kb_id ) {
		$kb_id = EPKB_Utilities::filter_int($kb_id, EPKB_KB_Config_DB::DEFAULT_KB_ID );
		return self::KB_POST_TYPE_PREFIX . $kb_id;
	}

	/**
	 * Retrieve KB post type name e.g. epkb_post_type_1
	 *
	 * @return string | <empty> when kb id cannot be determined
	 */
	public static function get_post_type2() {
		$kb_id = self::get_current_kb_id();
		if ( empty( $kb_id ) ) {
			return '';
		}
		return self::KB_POST_TYPE_PREFIX . $kb_id;
	}

	/**
	 * Return category name e.g. epkb_post_type_1_category
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_category_taxonomy_name( $kb_id ) {
		return self::get_post_type( $kb_id ) . self::KB_CATEGORY_TAXONOMY_SUFFIX;
	}

	/**
	 * Return category name e.g. epkb_post_type_1_category
	 *
	 * @return string | <empty> when kb id cannot be determined
	 */
	public static function get_category_taxonomy_name2() {
		$kb_id = self::get_current_kb_id();
		if ( empty( $kb_id ) ) {
			return '';
		}
		return self::get_post_type( $kb_id ) . self::KB_CATEGORY_TAXONOMY_SUFFIX;
	}

	/**
	 * Return tag name e.g. epkb_post_type_1_tag
	 *
	 * @param $kb_id - assumed valid id
	 *
	 * @return string
	 */
	public static function get_tag_taxonomy_name( $kb_id ) {
		return self::get_post_type( $kb_id ) . self::KB_TAG_TAXONOMY_SUFFIX;
	}

	/**
	 * Retrieve KB ID from category taxonomy name
	 *
	 * @param $category_name
	 *
	 * @return int | WP_Error
	 */
	public static function get_kb_id_from_category_taxonomy_name( $category_name ) {
		if ( empty($category_name) || ! is_string($category_name) ) {
			return new WP_Error('40', "kb_id not found");
		}

		$kb_id = str_replace( self::KB_POST_TYPE_PREFIX, '', $category_name );
		if ( empty($kb_id) ) {
			return new WP_Error('41', "kb_id not found");
		}

		$kb_id = str_replace( self::KB_CATEGORY_TAXONOMY_SUFFIX, '', $kb_id );
		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			return new WP_Error('42', "kb_id not valid");
		}

		return $kb_id;
	}

	/**
	 * Retrieve KB ID from tag taxonomy name
	 *
	 * @param $tag_name
	 *
	 * @return int | WP_Error
	 */
	public static function get_kb_id_from_tag_taxonomy_name( $tag_name ) {
		if ( empty($tag_name) || ! is_string($tag_name) ) {
			return new WP_Error('50', "kb_id not found");
		}

		$kb_id = str_replace( self::KB_POST_TYPE_PREFIX, '', $tag_name );
		if ( empty($kb_id) ) {
			return new WP_Error('51', "kb_id not found");
		}

		$kb_id = str_replace( self::KB_TAG_TAXONOMY_SUFFIX, '', $kb_id );
		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			return new WP_Error('52', "kb_id not valid");
		}

		return $kb_id;
	}

	/**
	 * Retrieve KB ID from article type name
	 *
	 * @param String $post_type is post or post type
	 *
	 * @return int | WP_Error if no kb_id found
	 */
	public static function get_kb_id_from_post_type( $post_type ) {
		if ( empty($post_type) || ! is_string($post_type) ) {
			return new WP_Error('35', "kb_id not found");
		}

		$kb_id = str_replace( self::KB_POST_TYPE_PREFIX, '', $post_type );
		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			return new WP_Error('36', "kb_id not valid");
		}

		return $kb_id;
	}

	/**
	 * Determine if the current page is KB main page i.e. it contains KB shortcode and return its KB ID if any
	 * @param null $the_post - either pass post to the method or use current post
	 * @return int|null return KB ID if current page is KB main page otherwise null
	 */
	public static function get_kb_id_from_kb_main_shortcode( $the_post=null ) {
		/** @var $wpdb Wpdb */
		global $wpdb;

		// ensure WP knows about the shortcode
		add_shortcode( self::KB_MAIN_PAGE_SHORTCODE_NAME, array( 'EPKB_Layouts_Setup', 'output_kb_page_shortcode' ) );

		$apost = empty($the_post) ? $GLOBALS['post'] : $the_post;
        if ( empty($apost) || ! $apost instanceof WP_Post ) {
            return null;
        }

		// determine whether this page contains this plugin shortcode
		$content = '';
		if ( has_shortcode( $apost->post_content, self::KB_MAIN_PAGE_SHORTCODE_NAME ) ) {
			$content = $apost->post_content;
		} else if ( isset($apost->ID) ) {
			$content = $wpdb->get_var( "SELECT meta_value FROM $wpdb->postmeta " .
			                           "WHERE post_id = {$apost->ID} and meta_value LIKE '%%" . self::KB_MAIN_PAGE_SHORTCODE_NAME . "%%'" );
		}

		return self::get_kb_id_from_shortcode( $content );
	}

	/**
	 * Retrieve KB ID from post content - shortcode
	 *
	 * @param String $content should have the shortcode with KB ID
	 *
	 * @return int|null returns KB ID if found
	 */
	private static function get_kb_id_from_shortcode( $content ) {

		if ( empty($content) || ! is_string($content) ) {
			return null;
		}

		$start = strpos($content, self::KB_MAIN_PAGE_SHORTCODE_NAME);
		if ( empty($start) || $start < 0 ) {
			return null;
		}

		$end = strpos($content, ']', $start);
		if ( empty($start) || $start < 1 ) {
			return null;
		}

		$shortcode = substr($content, $start, $end);
		if ( empty($shortcode) || strlen($shortcode) < strlen(self::KB_MAIN_PAGE_SHORTCODE_NAME)) {
			return null;
		}

		preg_match_all('!\d+!', $shortcode, $number);
		$number = empty($number[0][0]) ? 0 : $number[0][0];
		if ( ! EPKB_Utilities::is_positive_int( $number ) ) {
			return null;
		}

		return (int)$number;
	}

	/**
	 * Return HTML to display status message
	 *
	 * @param $kb_config
	 * @param string $chosen_layout
	 * @param $articles_seq_data
	 * @param $category_seq_data
	 * @return string
	 */
	public static function get_kb_status_msg( $kb_config, $chosen_layout='', $articles_seq_data=array(), $category_seq_data=array() ) {

		$message = self::get_kb_status( $kb_config, $chosen_layout, $articles_seq_data, $category_seq_data );

		// 7. show all messages
		if ( empty($message) ) {
			$callout_class = 'callout_success';
			$msg = __( 'No problems found', 'echo-knowledge-base' );
			$details = esc_html__( 'Everything seems to be working, no errors have been detected.', 'echo-knowledge-base' );
		} else {
			$callout_class = 'callout_warning';
			$msg = __( 'Found potential problems', 'echo-knowledge-base' );
			$details = '<p class="note_type_1">' .
			           esc_html__( "NOTE: It is OK to see warnings if you are creating and updating your knowledge base. After you're done, you should see " .
			               "no warnings. Warnings indicate that your users might come accross empty categories, that they might not see certain " .
			               "articles or they might otherwise have less-than-optimal experience", 'echo-knowledge-base' ) . ' ' .
			           '.</p>';
			$details .= $message;
		}

		return
			"<div id='kb_status' class='callout $callout_class'>
				<h4><strong>Status:</strong>&nbsp;&nbsp;" . esc_html($msg) . "</h4>" .
				$details /** DO NOT escape with HTML. Already escaped. */. "  
			</div>";
	}

	/**
	 * Show status of current Knowledge Base
	 *
	 * @param $kb_config
	 * @param string $chosen_layout - layout user just switched to or empty
	 * @param array $articles_seq_data
	 * @param array $category_seq_data
	 * @return string
	 */
	private static function get_kb_status( $kb_config, $chosen_layout='', $articles_seq_data=array(), $category_seq_data=array() ) {

		$message = '';
		$kb_id = $kb_config['id'];
		$current_layout =  empty($chosen_layout) ? EPKB_KB_Config_Layouts::get_kb_main_page_layout_name( $kb_config ) : $chosen_layout;

		$add_on_message = apply_filters('epkb_add_on_message', '');
		if ( ! empty($add_on_message) ) {
			$message .= '<div class="status_group">' . $add_on_message . '</div>';
		}

		// 1. ensure we have KB pages with KB shortcode
		$kb_main_pages = $kb_config['kb_main_pages'];
		$kb_main_page_found = false;
		foreach( $kb_main_pages as $post_id => $post_title ) {
			$post_status = get_post_status( $post_id );
			if ( ! empty($post_status) && in_array( $post_status, array( 'publish', 'future', 'private' ) ) ) {
				$kb_main_page_found = true;
				break;
			}
		}

		if ( ! $kb_main_page_found ) {
			/* translators: refers to Knowledge Base main page that shows all links to articles */
			$i18_KB_Main = '<strong>' . esc_html__( 'Knowledge Base Main', 'echo-knowledge-base' ) . '</strong>';
			$i18_KB_shortcode = '<strong>' . esc_html__( 'KB shortcode', 'echo-knowledge-base' ) . '</strong>';
			/* translators: first %s will be replaced with the word 'Knowledge Base Main' (in bold) and the second %s will be replaced with 'KB shortcode' (also in bold). */
			$message .= '<div class="status_group"><p>' .
			            sprintf( __( 'Did not find active %s page. Only page with %s will display KB Main page. If you do have a KB shortcode on a page, ' .
			                         'save that page and this message should disappear.', 'echo-knowledge-base' ), $i18_KB_Main, $i18_KB_shortcode ) . '</p></div>';
		}

		$i18_articles = '<strong>' . esc_html__( 'articles', 'echo-knowledge-base' ) . '</strong>';
		$i18_edit_word = esc_html__( 'Edit', 'echo-knowledge-base' );
		$i18_category = '<strong>' . esc_html__(  _x( 'category', 'taxonomy singular name', 'echo-knowledge-base' ), 'echo-knowledge-base' ) . '</strong>';

		// 2. check orphan articles
		$article_db = new EPKB_Articles_DB();
		$orphan_articles = $article_db->get_orphan_published_articles( $kb_id );
		if ( ! empty($orphan_articles) ) {
			$message .= '<div class="status_group">';
				/* translators: the %s will be replaced with the word 'articles' (in bold) */
				$message .= '<p>' . sprintf( esc_html__( 'The following %s have no categories assigned:', 'echo-knowledge-base' ), $i18_articles ) . '</p>';
				$message .= '<ul>';
				foreach( $orphan_articles as $orphan_article ) {
					$message .= '<li>' . $orphan_article->post_title . ' &nbsp;&nbsp;' . '<a href="' .  get_edit_post_link( $orphan_article->ID ) . '" target="_blank">' . $i18_edit_word . '</a></li>';
				}
				$message .= '</ul>';
			$message .= '</div>';
		}

		if ( empty($articles_seq_data) || empty($category_seq_data) ) {
			// ensure category hierarchy is up to date
			$category_admin = new EPKB_Categories_Admin();
			$category_admin->update_categories_sequence();

			// ensure articles assignment to categories is up to date
			$article_admin = new EPKB_Articles_Admin();
			$article_admin->update_articles_sequence('');

			// category and article sequence
			$articles_seq_data = EPKB_Utilities::get_kb_option( $kb_id, EPKB_Articles_Admin::KB_ARTICLES_SEQ_META, array(), true );
			$category_seq_data = EPKB_Utilities::get_kb_option( $kb_id, EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META, array(), true );
		}

		// 3. check if this is Tabs layout and there are articles attached to the top level category
		//    AND do not have any other non-top category, report them
		if ( $current_layout == EPKB_KB_Config_Layout_Tabs::LAYOUT_NAME ) {

			// 3.1 retrieve top-level categories and attached articles
			$top_level_categories = array();
			$top_level_category_articles = array();
			foreach ( $category_seq_data as $category_id => $subcategories ) {
				$top_level_categories[] = $category_id;

				// ignore empty category
				if ( $category_id == 0 || empty($articles_seq_data[$category_id]) || count($articles_seq_data[$category_id]) < 3 ) {
					continue;
				}

				$top_level_category_articles += $articles_seq_data[$category_id];
				unset($top_level_category_articles[0]);
				unset($top_level_category_articles[1]);
			}

			// 3.2 remove top-level articles that are also attached sub-catagories
			foreach ( $articles_seq_data as $category_id => $sub_category_article_list ) {
				// skip top level categories
				if ( in_array($category_id, $top_level_categories) || $category_id == 0 ) {
					continue;
				}
				// does sub-category have top-level article as well?
				unset($sub_category_article_list[0]);
				unset($sub_category_article_list[1]);
				foreach ( $top_level_category_articles as $top_level_article_id => $top_level_article_title ) {
					if ( in_array($top_level_article_id, array_keys($sub_category_article_list) ) ) {
						unset($top_level_category_articles[$top_level_article_id]);
					}
				}
			}

			// 3.3 output articles that are only on top-level
			$top_level_msg = '';
			$ix = 0;
			$top_level_category_articles = array_unique( $top_level_category_articles );
			foreach( $top_level_category_articles as $top_level_article_id => $top_level_article_title ) {
				$ix++;
				$top_level_msg .= '<li>' . $top_level_article_title . ' &nbsp;&nbsp;' . '<a href="' .  get_edit_post_link( $top_level_article_id ) . '" target="_blank">' . $i18_edit_word . '</a></li>';
			}

			if (  !empty($top_level_msg) ) {
				$i18_layout = '<strong>' . esc_html__( 'Layout', 'echo-knowledge-base' ) . '</strong>';
				$i18_tabs = '<strong>' . esc_html__( 'Tabs', 'echo-knowledge-base' ) . '</strong>';

				/* translators: the first %s will be replaced with the word 'Layout' (in bold) and the second %s will replaced with 'Tabs' word (in bold) */
				$msg1 = sprintf( esc_html__( 'Current %s is set to %s.', 'echo-knowledge-base' ), $i18_layout, $i18_tabs );
				/* translators: the %s will be replaced with the word 'category' (in bold) */
				$msg2 = sprintf( esc_html(_n( 'The following article has only top-level %s and will not be displayed' .
				                     ' on KB Main page. In the Tab layout, this article needs to be assigned to a sub-category.',
									 'The following articles have only top-level %s and will not be displayed' .
									 ' on KB Main page. In the Tab layout, these articles need to be assigned to a sub-category.', $ix, 'echo-knowledge-base')), $i18_category );

				$message .= '<div class="status_group">'.
							'<p>'. $msg1 .'</p>'.
							'<p>' . $msg2 . '</p>
			                <ul>'. $top_level_msg . '</ul>
			                </div>
			                ';
			}
		}

		$stored_ids_obj = new EPKB_Categories_Array( $category_seq_data ); // normalizes the array as well
		$category_ids_levels = $stored_ids_obj->get_all_keys();


		// 4. check if user does not have too many levels of categories; these categories and articles within them
		//    will not show; ignore empty categories
		$max_category_level = apply_filters( 'epkb_max_layout_level', $chosen_layout );
		$max_category_level = EPKB_Utilities::is_positive_or_zero_int( $max_category_level ) ? $max_category_level : 3;
		if ( $max_category_level > 0 ) {

			// 4.1 get all visible articles
			$visible_articles = array();
			foreach ( $category_ids_levels as $category_id => $level ) {
				if ( $level <= $max_category_level && ! empty( $articles_seq_data[ $category_id ] ) && count( $articles_seq_data[ $category_id ] ) > 2 ) {
					$visible_articles += $articles_seq_data[ $category_id ];
					unset( $visible_articles[0] );
					unset( $visible_articles[1] );
				}
			}

			// 4.2 get invisible subcategories (these categories are too deep)
			$invisible_articles = array();
			$invisible_cat_msg  = '';
			foreach ( $category_ids_levels as $category_id => $level ) {
				if ( $level > $max_category_level && ! empty( $articles_seq_data[ $category_id ] ) ) {
					$invisible_cat_msg .= '<li>' . $articles_seq_data[ $category_id ][0] . ' &nbsp;&nbsp;' . '<a href="' .
					                      get_edit_term_link( $category_id, self::get_category_taxonomy_name( $kb_id ), self::get_post_type( $kb_id ) ) .
					                      '" target="_blank">' . $i18_edit_word . '</a></li>';
					$invisible_articles += $articles_seq_data[ $category_id ];
					unset( $invisible_articles[0] );
					unset( $invisible_articles[1] );
				}
			}

			// 4.3 list any articles that are NOT in other visible categories
			$invisible_articles_msg = '';
			foreach( $invisible_articles as $article_id => $article_title ) {
				if ( in_array( $article_id, $visible_articles) ) {
					continue;
				}
				$invisible_articles_msg .= '<li>' . $article_title . ' &nbsp;&nbsp;' . '<a href="' .  get_edit_post_link( $article_id ) . '" target="_blank">' . $i18_edit_word . '</a></li>';
			}
		}

		$i18_categories = '<strong>' . esc_html__( 'categories', 'echo-knowledge-base' ) . '</strong>';

		if ( ! empty($invisible_cat_msg) ) {
			/* translators: the first %s will be replaced with the word 'categories' (in bold) and the second %s will replaced with 'basic' or 'tabs' word (in bold) */
			$msg3 = sprintf( esc_html__( 'The following %s are nested too deeply to be visible with the selected %s layout:', 'echo-knowledge-base' ), $i18_categories, $current_layout );
			$message .= '<div class="status_group"><p>' . $msg3 . '</p><ul>' . $invisible_cat_msg . '</ul><p>' .
			                esc_html__( 'You can move the categories and/or switch layout.', 'echo-knowledge-base' ) . '</p></div>';
		}
		if ( ! empty($invisible_articles_msg) ) {

			/* translators: the first %s will be replaced with the word 'articles' (in bold) and the second %s will replaced with 'basic' or 'tabs' word (in bold) */
			$msg4 = sprintf( esc_html__( 'The following %s are assigned to categories not visible so they will not be visible with the selected %s layout:', 'echo-knowledge-base' ),
										$i18_articles, $current_layout );
			$message .= '<div class="status_group"><p>' . $msg4 . '</p><ul>' . $invisible_articles_msg . '</ul>' .
						'<p>' . esc_html__( 'You can either assign the article(s) to different categories and/or move categories.', 'echo-knowledge-base' ) . '</p></div>';
		}

		// 5. show empty categories; do not count categories containing other categories
		$empty_cat_msg = '';
		foreach( $stored_ids_obj->get_all_leafs() as $category_id ) {
			if ( isset($articles_seq_data[$category_id]) && count($articles_seq_data[$category_id]) < 3 ) {
				$empty_cat_msg .= '<li>' . $articles_seq_data[$category_id][0] . ' &nbsp;&nbsp;' . '<a href="' .
				                      get_edit_term_link( $category_id, self::get_category_taxonomy_name( $kb_id ), self::get_post_type( $kb_id) ) .
				                      '" target="_blank">' . $i18_edit_word. '</a></li>';
			}
		}
		if ( ! empty($empty_cat_msg) ) {
			/* translators: the first %s will be replaced with the word 'articles' (in bold) and the second %s will replaced with 'basic' or 'tabs' word (in bold) */
			$msg5 = sprintf( esc_html__( 'The following %s are currently empty:', 'echo-knowledge-base' ), $i18_categories );
			$message .= '<div class="status_group"><p>' . $msg5 . '</p><ul>' . $empty_cat_msg . '</ul></div>';
		}

		return $message;
	}

	/**
	 * Return KB status line
	 *
	 * @param $kb_config
	 * @param $chosen_layout - layout user just switched to or empty
	 * @return string
	 */
	public static function get_kb_status_line( $kb_config, $chosen_layout='' ) {
		$status_tab_url = 'edit.php?post_type=' . self::get_post_type( $kb_config['id'] ) . '&page=epkb-kb-configuration';
		$warning_msg = self::get_kb_status( $kb_config, $chosen_layout );
		$i18_status = esc_html__( 'Status', 'echo-knowledge-base' );
		if ( empty($warning_msg) ) {
			/* translators: indicates status */
			return "<div id='status_line' class='kb_status kb_status_success'><strong>" . $i18_status . ":</strong> " . esc_html__( 'OK', 'echo-knowledge-base' ) . "</div>";
		} else {
			/* translators: indicates status */
			return "<div id='status_line' class='kb_status kb_status_warning'><strong>" . $i18_status . ":</strong> " . esc_html__( 'Warning', 'echo-knowledge-base' ) .
			       " - <strong><a href='$status_tab_url'>" . esc_html__( 'Learn More', 'echo-knowledge-base' ) . "</a> </strong></div>";
		}
	}

    /**
     * Return all KB Main pages that we know about. Also remove old ones.
     *
     * @param $kb_config
     * @return array a list of KB Main Pages titles and links
     */
	public static function get_kb_main_pages( $kb_config) {

		$post_statuses = array(
			'draft'   => 'Draft',
			'pending' => 'Pending',
			'trash'   => 'Trash',
			'publish' => 'Published',
			'future'  => 'Scheduled',
			'private' => 'Private'
		);

		$kb_main_pages = $kb_config['kb_main_pages'];
		$kb_main_pages_info = array();
		foreach ( $kb_main_pages as $post_id => $post_title ) {

			$post_status = get_post_status( $post_id );

			// remove previous page versions
			if ( empty( $post_status ) || $post_status == 'inherit' || $post_status == 'trash' ) {
				unset( $kb_main_pages[ $post_id ] );
				continue;
			}

			$post = get_post( $post_id );  // TODO private ?
			if ( empty( $post ) || is_array( $post ) || ! $post instanceof WP_Post ) {
				unset( $kb_main_pages[ $post_id ] );
				continue;
			}

			// remove page that does not contain KB shortcode any more
			$kb_id = self::get_kb_id_from_kb_main_shortcode( $post );
			if ( empty( $kb_id ) || $kb_id != $kb_config['id'] ) {
				unset( $kb_main_pages[ $post_id ] );
				continue;
			}

			$post_status = isset( $post_statuses[ $post_status ] ) ? $post_statuses[ $post_status ] : 'unknown status';

			$kb_post_slug = get_page_uri($post_id);
			if ( is_wp_error( $kb_post_slug ) || empty($kb_home_slug) || is_array($kb_post_slug) ) {
				$kb_home_slug = EPKB_KB_Handler::get_default_slug( $kb_id );
			}

			$kb_main_pages_info[$post_id] = array( 'post_title' => $post_title, 'post_status' => $post_status, 'post_slug' => $kb_post_slug );
		}

		// we need to remove pages that are revisions
		if ( count( $kb_config['kb_main_pages'] ) != count( $kb_main_pages ) ) {
			$kb_config['kb_main_pages'] = $kb_main_pages;
			epkb_get_instance()->kb_config_ojb->update_kb_configuration( $kb_config['id'], $kb_config );
		}

		return $kb_main_pages_info;
	}

    /**
     * Find KB Main Page that is not in trash and get its URL.
     *
     * @param $kb_config
     * @return string|<empty>
     */
	public static function get_first_kb_main_page_url( $kb_config ) {
		$first_page_id = '';
        $kb_main_pages = $kb_config['kb_main_pages'];
        foreach ( $kb_main_pages as $post_id => $post_title ) {
			$first_page_id = $post_id;
			break;
		}

        $first_page_url = empty($first_page_id) ? '' : get_permalink( $first_page_id );

		return is_wp_error( $first_page_url ) ? '' : $first_page_url;
	}
}
