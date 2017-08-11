<?php

/**
 * Handle loading EP templates
 *
 * Adapted from code in EDD/WooCommmerce (Copyright (c) 2017, Pippin Williamson) and WP.
 */
class EPKB_Templates {

	public function __construct() {
   		add_filter( 'template_include', array( __CLASS__, 'template_loader' ) );
	}

	/**
	 * Load article templates. Templates are in the 'templates' folder.
	 *
	 * Templates can be overriden in /theme/knowledgebase/ folder.
	 *
	 * @param mixed $template
	 * @return string
	 */
	public static function template_loader( $template ) {
        global $post, $eckb_kb_id, $eckb_kb_templates_on;

        // KEEP performance optimized

        // ignore list of categories (for now) and other non-page/post conditions
        if ( ! self::is_post_page() ) {
            return $template;
        }

        // ignore posts that are not KB Articles
        if (  $post->post_type == 'post' && ! strncmp($post->post_type, EPKB_KB_Handler::KB_POST_TYPE_PREFIX, strlen(EPKB_KB_Handler::KB_POST_TYPE_PREFIX)) == 0 ) {
            return $template;
        }

        // is this KB Main Page ?
        $kb_id = EPKB_KB_Config_DB::DEFAULT_KB_ID;
        $is_kb_main_page = false;
        $templates_for_kb = 'current_theme_templates';
        $template_file_name = 'single-article';
        $all_kb_configs = epkb_get_instance()->kb_config_ojb->get_kb_configs( true );
        foreach ( $all_kb_configs as $one_kb_config ) {
            if ( ! empty($one_kb_config['kb_main_pages']) && is_array($one_kb_config['kb_main_pages']) &&
                 in_array($post->ID, array_keys($one_kb_config['kb_main_pages']) ) ) {
                $templates_for_kb = $one_kb_config['templates_for_kb'];
                $is_kb_main_page = true;
                $kb_id = $one_kb_config['id'];
                $template_file_name = $one_kb_config['kb_main_page_layout'];
                break;  // found matching post
            }
        }

        // is this KB article url ?
        if ( ! $is_kb_main_page ) {
            $kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type );
            if ( is_wp_error( $kb_id ) ) {
                return $template;
            }

            $template_file_name = empty($all_kb_configs[$kb_id]['kb_article_page_layout']) ?
                                    EPKB_KB_Config_Layouts::KB_ARTICLE_PAGE_NO_LAYOUT : $all_kb_configs[$kb_id]['kb_article_page_layout'];
            $templates_for_kb = empty($all_kb_configs[$kb_id]['templates_for_kb']) ? $templates_for_kb : $all_kb_configs[$kb_id]['templates_for_kb'];
        }

        // do not use our template unless theme template is explicilty specified
        if ( $templates_for_kb == 'current_theme_templates' ) {
            return $template;
        }

		$eckb_kb_templates_on = true;
		$eckb_kb_id = $kb_id;

		$template_name = self::get_template_name( $template_file_name );
		if ( empty($template_name) ) {
			return $template;
		}

		$located_template = self::locate_template( $template_name );
        return ( empty($located_template) ? $template : $located_template );
	}

    /**
     * Check if current post/page could be KB one
     *
     * @return bool
     */
    public static function is_post_page() {
        global $wp_query, $post;

        if ( ( isset( $wp_query->is_archive ) && $wp_query->is_archive ) ||
             ( isset( $wp_query->is_embed ) && $wp_query->is_embed ) ||
             ( isset( $wp_query->is_category ) && $wp_query->is_category ) ||
             ( isset( $wp_query->is_tag ) && $wp_query->is_tag ) ||
             ( isset( $wp_query->is_attachment ) && $wp_query->is_attachment ) ) {
            return false;
        }

        if ( empty( $post ) || ! isset( $post->post_type ) || $wp_query->post_count != 1 || ! isset( $wp_query->post ) ||
             ! isset( $wp_query->posts[0] ) || ! isset( $wp_query->is_single ) || ! isset( $wp_query->is_page )	) {
            return false;
        }

        return true;
    }

    /**
     * Get the default filename for a template.
     *
     * @param $template_type
     * @return string
     */
	private static function get_template_name( $template_type ) {

        $template_type = strtolower( $template_type );
		if ( $template_type == 'article' ) {
			return 'single-article.php';
		} else if ( in_array($template_type, array('basic', 'tabs', 'grid', 'sidebar') ) ) {
            return $template_type . '-layout.php';
        }

		return '';
	}

	/**
	 * Retrieves a template part
	 *
	 * Taken from bbPress
	 *
	 * @param string $slug
	 * @param string $name Optional. Default null
	 * @param $kb_config - used in templates
	 * @param $article
	 * @param bool $load
	 *
	 * @return string
	 */
	public static function get_template_part( $slug, $name = null, /** @noinspection PhpUnusedParameterInspection */ $kb_config,
												/** @noinspection PhpUnusedParameterInspection */$article, $load = true ) {
		// Execute code for this part
		do_action( 'epkb_get_template_part_' . $slug, $slug, $name );

		$load_template = apply_filters( 'epkb_allow_template_part_' . $slug . '_' . $name, true );
		if ( false === $load_template ) {
			return '';
		}

		// Setup possible parts
		$templates = array();
		if ( isset( $name ) )
			$templates[] = $slug . '-' . $name . '.php';
		$templates[] = $slug . '.php';

		// Allow template parts to be filtered
		$templates = apply_filters( 'epkb_get_template_part', $templates, $slug, $name );

		// Return the part that is found
		$template_path = self::locate_template( $templates );
        if ( ( true == $load ) && ! empty( $template_path ) ) {
            include( $template_path );
        }

        return $template_path;
    }

	/**
	 * Retrieve the name of the highest priority template file that exists.
	 *
	 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that CHILD THEME which
	 * inherit from a PARENT THEME can just overload one file. If the template is
	 * not found in either of those, it looks in KB template folder last
	 *
	 * Taken from bbPress
	 *
	 * @param string|array $template_names Template file(s) to search for, in order.
	 * @return false|string The template filename if one is located.
	 */
	public static function locate_template( $template_names ) {

		// No file found yet
		$located = false;

		// loop through hierarchy of template names
		foreach ( (array) $template_names as $template_name ) {

			// Continue if template is empty
			if ( empty( $template_name ) )
				continue;

			// Trim off any slashes from the template name
			$template_name = ltrim( $template_name, '/' );

			// loop through hierarchy of template file locations ( child -> parent -> our theme )
			foreach( self::get_theme_template_paths() as $template_path ) {
				if ( file_exists( $template_path . $template_name ) ) {
					$located = $template_path . $template_name;
					break;
				}
			}

			if ( $located ) {
				break;
			}
		}

		return $located;
	}

	/**
	 * Returns a list of paths to check for template locations:
	 * 1. Child Theme
	 * 2. Parent Theme
	 * 3. KB Theme
	 *
	 * @return mixed|void
	 */
	private static function get_theme_template_paths() {

		$template_dir = self::get_theme_template_dir_name();

		$file_paths = array(
			1 => trailingslashit( get_stylesheet_directory() ) . $template_dir,
			10 => trailingslashit( get_template_directory() ) . $template_dir,
			100 => self::get_templates_dir()
		);

		$file_paths = apply_filters( 'epkb_template_paths', $file_paths );

		// sort the file paths based on priority
		ksort( $file_paths, SORT_NUMERIC );

		return array_map( 'trailingslashit', $file_paths );
	}

	/**
	 * Returns the path to the EP templates directory
	 * @return string
	 */
	private static function get_templates_dir() {
		return Echo_Knowledge_Base::$plugin_dir . 'templates';
	}

	/**
	 * Returns the URL to the EP templates directory
	 * @return string
	 */
	private static function get_templates_url() {
		return Echo_Knowledge_Base::$plugin_url . 'templates';
	}

	/**
	 * Returns name of directory inside child or parent theme folder where KB templates are located
	 * Themes can filter this by using the epkb_templates_dir filter.
	 *
	 * @return string
	 */
	private static function get_theme_template_dir_name() {
		return trailingslashit( apply_filters( 'epkb_templates_dir', 'kb_templates' ) );
	}
}