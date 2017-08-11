<?php  if ( ! defined( 'ABSPATH' ) ) exit;

class EPKB_Layouts_Setup {

	static $demo_mode = false;

	public function __construct() {
		add_shortcode( EPKB_KB_Handler::KB_MAIN_PAGE_SHORTCODE_NAME, array( 'EPKB_Layouts_Setup', 'output_kb_page_shortcode' ) );
	}

	/**
	 * Output layout based on KB Shortcode.
	 *
	 * @param array $shortcode_attributes are shortcode attributes that the user added with the shortcode
	 * @return string of HTML output replacing the shortcode itself
	 */
	public static function output_kb_page_shortcode( $shortcode_attributes ) {
        $kb_config = self::get_kb_config( $shortcode_attributes );
		return self:: output_kb_page( $kb_config );
	}

	/**
	 * Show KB Main page i.e. knowledge-base/ url or KB Article Page in case of SBL.
	 *
	 * @param bool $is_builder_on
	 * @param null $kb_config
	 * @param array $article_seq
	 * @param array $categories_seq
	 *
	 * @return string
	 */
	public static function output_kb_page( $kb_config, $is_builder_on=false, $article_seq=array(), $categories_seq=array() ) {
		global $eckb_sbl_article_id;

		// let layout class display the KB main page
		$layout_output = '';
		$layout = empty($kb_config['kb_main_page_layout']) ? '' : $kb_config['kb_main_page_layout'];

        // if current theme (using shortcode) + ARTICLE PAGE as SBL then ensure we have SBL outputted
		if ( ! empty($eckb_sbl_article_id)  && $kb_config['templates_for_kb'] == 'current_theme_templates' ) {
            $layout = EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT;
        }

		if ( ! self::is_core_layout( $layout ) ) {
			ob_start();
			apply_filters( 'epkb_' . strtolower($layout) . '_layout_output', $kb_config, $is_builder_on, $article_seq, $categories_seq );
			$layout_output = ob_get_clean();

			// use Basic Layout if current layout is missing
			$layout = empty($layout_output) ? EPKB_KB_Config_Layout_Basic::LAYOUT_NAME : $layout;
		}

		// if this is core layout then generate it; if this is add-on layout and is missing then use Basic Layout
		if ( empty($layout_output) ) {
			$layout_class_name = 'EPKB_Layout_' . ucfirst($layout);
			$layout_class = class_exists($layout_class_name) ? new $layout_class_name() : new EPKB_Layout_Basic();
			ob_start();
			$layout_class->display_kb_main_page( $kb_config, $is_builder_on, $article_seq, $categories_seq );
			$layout_output = ob_get_clean();
		}

		return $layout_output;
	}

	private static function is_core_layout( $layout ) {
		return $layout == EPKB_KB_Config_Layout_Basic::LAYOUT_NAME || $layout == EPKB_KB_Config_Layout_Tabs::LAYOUT_NAME;
	}

	/**
	 * Check that the layout exists and is properly configured
	 *
	 * @param array $shortcode_attributes
	 *
	 * @return array return the KB configuration
	 */
	private static function get_kb_config( $shortcode_attributes ) {

		$kb_id = empty($shortcode_attributes['id']) ? EPKB_KB_Config_DB::DEFAULT_KB_ID : $shortcode_attributes['id'] ;
		if ( ! EPKB_Utilities::is_positive_int( $kb_id ) ) {
			EPKB_Logging::add_log_var( "KB ID in shortcode is invalid. Using KB ID 1 instead of: ", $kb_id );
			$kb_id = EPKB_KB_Config_DB::DEFAULT_KB_ID;
		}

		if ( count( $shortcode_attributes ) > 1 ) {
			EPKB_Logging::add_log_var( "KB with ID " . $kb_id . ' has too many shortcode attributes', $shortcode_attributes );
		}

		//retrieve KB config
		$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );
		if ( is_wp_error( $kb_config ) ) {
			EPKB_Logging::add_log_var( "failed to retrieve KB configuration. Using defaults instead.", $kb_id, $kb_config );
			$kb_config = EPKB_KB_Config_Specs::get_default_kb_config( $kb_id );
		}

		return $kb_config;
	}

	/**
	 * Knowledge Base Article template
	 *
	 * This template is used by KB system to display an article based on settings in KB admin template
	 *
	 * @param WP_Post $post_in
	 * @param bool $display - whether to return or echo the article content
	 * @param $empty_post_meg
	 * @return mixed|string
	 *
	 * TODO Depricated. remove in EL-AY > 1.1.0
	 */
	public static function display_article( $post_in, $empty_post_meg, $display=true ) {

		$kb_id = 1;
		$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );

		return EPKB_Articles_Setup::get_article_content_and_features( $post_in, '', $kb_config );
	}
}
