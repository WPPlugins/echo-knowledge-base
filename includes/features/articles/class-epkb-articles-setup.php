<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle article front-end setup
 *
 */
class EPKB_Articles_Setup {


	private $cached_comments_flag;

	public function __construct() {
		add_filter( 'the_content', array( $this, 'get_article_content_and_features_on_the_content_hook' ), 99999 ); // must be high priority
		add_filter( 'comments_open', array( $this, 'setup_comments'), 1, 2 );
	}

    /**
     * theme / ours template  ==>  the_content()  ==> shortocdes ( -> Layouts) =>  get article (this method)
     *
     * @param $content
     * @return string
     */
	public function get_article_content_and_features_on_the_content_hook( $content ) {
        /* @var $wp_embed WP_Embed */
        global $post, $eckb_sbl_article_id, $eckb_kb_templates_on;

		// ignore if not post, is archive or current theme with any layout
        // KEEP performance optimized
		if ( empty($post) || ! $post instanceof WP_Post || is_archive() || ! is_main_query() ||
             ( empty($eckb_kb_templates_on) && ! empty($eckb_sbl_article_id)  ) ) {
			return $content;
		}

         // check if this is KB Article URL; KEEP performance optimized
        if ( ! EPKB_KB_Handler::is_kb_post_type( $post->post_type ) || post_password_required() ) {
            return $content;
        }

		$kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type );
		if ( is_wp_error($kb_id) ) {
			$kb_id = EPKB_KB_Config_DB::DEFAULT_KB_ID;
		}

		// initialize KB config to be accessible to templates
		$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );
		if ( is_wp_error($kb_config) ) {
			return $content;
		}

        // c) if this is ARTICLE PAGE with SBL then get SBL
        $kb_article_pg_layout = epkb_get_instance()->kb_config_ojb->get_value( 'kb_article_page_layout', $kb_id, EPKB_KB_Config_Layouts::KB_ARTICLE_PAGE_NO_LAYOUT );
        if ( $kb_article_pg_layout == EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT ) {
            $eckb_sbl_article_id = $post->ID;
            $content = self:: output_article_page_with_layout( $content, $kb_config );
            return $content;
        }

        // d) retrieve article content (non-KB Home shortcodes already applied) and features; if current theme + SBL then get article content
        $content = self::get_article_content_and_features( $post, $content, $kb_config );

        return $content;
	}

    /**
     * Output SBL + article
     *
     * @param $article_content- article + features
     * @param $kb_config
     * @param bool $is_builder_on
     * @param bool $demo_mode
     * @param array $article_seq
     * @param array $categories_seq
     *
     * @return string
     */
    public static function output_article_page_with_layout( $article_content, $kb_config, $is_builder_on=false, $demo_mode=false, $article_seq=array(), $categories_seq=array() ) {
        
        // get Article Page Layout
        ob_start();
        apply_filters( 'epkb_article_page_layout_output', $article_content, $kb_config, $is_builder_on, $article_seq, $categories_seq );
        $layout_output = ob_get_clean();

        // if no layout found then just display the article
        if ( empty($layout_output) ) {
            $layout_output = $demo_mode ? wp_kses_post( EPKB_KB_Demo_Data::get_demo_article() ) : $article_content;
        }

        return $layout_output;
    }

    /**
     * Return single article content surrounded by features like breadcrumb and tags.
     *
     * NOTE: Assumes shortcodes already ran.
     *
     * @param $article
     * @param $content
     * @param $kb_config - front end or back end temporary KB config
     * @return string
     */
	public static function get_article_content_and_features( $article, $content, $kb_config ) {

        // if global post is empty initialize it
        if ( empty($GLOBALS['post']) ) {
            $GLOBALS['post'] = $article;
        }

        // if necessary get KB configuration
        if ( empty($kb_config) ) {
            $kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $article->post_type );
            if ( is_wp_error($kb_id) ) {
                $kb_id = EPKB_KB_Config_DB::DEFAULT_KB_ID;
            }

            // initialize KB config to be accessible to templates
            $kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );
            if ( is_wp_error($kb_config) ) {
                return $content;
            }
        }

		ob_start();      ?>

        <div id="eckb-article-page-container">  <?php

            // BACK NAVIGATION
            if ( $kb_config[ 'back_navigation_toggle'] == 'on' ) {
                EPKB_Templates::get_template_part( 'feature', 'navigation-back', $kb_config, $article );
            }

            // BREADCRUMB
            if ( $kb_config[ 'breadcrumb_toggle'] == 'on' ) {
                EPKB_Templates::get_template_part( 'feature', 'breadcrumb', $kb_config, $article );
            };

            // show article title only if: a) article has SBL or b) we are using KB article template
            if ( self::is_article_with_sbl( $kb_config ) || $kb_config['templates_for_kb'] == 'kb_templates' ) {
                $tag = self::is_article_with_sbl( $kb_config ) ? 'h2' : 'h1';
                $article_seq_no = empty($_REQUEST['seq_no']) ? '' : ' data-kb_article_seq_no=' . $_REQUEST['seq_no'];
                echo '<' . $tag . ' class="epkb-article-title kb-article-id" id="' . $article->ID . '"' . $article_seq_no . '>' . get_the_title( $article ) . '</' . $tag . '>';
            }

            // ARTICLE CONTENT                  ?>
            <div id="kb-article-content">		<?php
                echo $content;                  ?>
            </div>                              <?php

            // TAGS
            EPKB_Templates::get_template_part( 'feature', 'tags', $kb_config, $article );

            // COMMENTS - show only if using our KB template as theme templates display comments
            if ( $kb_config[ 'templates_for_kb' ] == 'kb_templates' && ! self::is_demo_article( $article ) ) {
                EPKB_Templates::get_template_part( 'feature', 'comments', array(), $article );
            }       ?>

        </div>      <?php

		$article_content = ob_get_clean();

        return str_replace( ']]>', ']]&gt;', $article_content );
	}

	/**
	 * Disable comments.
	 * Enable comments but it is up to WP, article and theme settings whether comments are actually displayed.
	 *
	 * @param $open
	 * @param $post_id
	 * @return bool
	 */
	public function setup_comments( $open, $post_id ) {

        global $eckb_kb_id;

		// verify it is a KB article
		$post = get_post();
		if ( empty($post) || ! $post instanceof WP_Post || ( ! EPKB_KB_Handler::is_kb_post_type( $post->post_type ) && empty($eckb_kb_id) ) ) {
			return $open;
		}

		$kb_id = empty($eckb_kb_id) ? EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type ) : $eckb_kb_id;
		if ( is_wp_error($kb_id) ) {
			return $open;
		}

		if ( empty($this->cached_comments_flag) ) {
			$this->cached_comments_flag = epkb_get_instance()->kb_config_ojb->get_value( 'articles_comments_global', $kb_id, 'off' );
		}

		return 'on' == $this->cached_comments_flag;
	}

    /**
     * Return true if article has SBL whether on KB Main Page or Article Page.
     * 
     * @param $kb_config
     * @return bool
     */
    public static function is_article_with_sbl( $kb_config ) {
        if ( EPKB_KB_Config_Layouts::is_main_page_displaying_sidebar( $kb_config['kb_main_page_layout'] ) ) {
            return true;
        }

        if ( EPKB_KB_Config_Layouts::is_article_page_displaying_sidebar( $kb_config['kb_article_page_layout'] ) ) {
            return true;
        }

        return false;
    }

    private static function is_demo_article( $article ) {
        return empty($article->ID) || empty($GLOBALS['post']) || empty($GLOBALS['post']->ID);
    }
}