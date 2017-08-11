<?php

/**  Register JS and CSS files  */

/**
 * FRONT-END pages using our plugin features
 */
function epkb_load_public_resources() {

	// if this is not KB Main Page then do not load public resources
	/*$kb_id = EPKB_KB_Handler::get_kb_id_from_kb_main_shortcode(); //TODO Make this test for custom post types now too.
	if ( empty($kb_id) ) {
		return;
	}*/

	epkb_load_public_resources_now();
}
add_action( 'wp_enqueue_scripts', 'epkb_load_public_resources' );

function epkb_load_public_resources_now() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_style( 'epkb-public-styles', Echo_Knowledge_Base::$plugin_url . 'css/public-styles' . $suffix . '.css', array(), Echo_Knowledge_Base::$version );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
	wp_enqueue_script( 'epkb-public-scripts', Echo_Knowledge_Base::$plugin_url . 'js/public-scripts' . $suffix . '.js',
					array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-effects-core', 'jquery-effects-bounce'), Echo_Knowledge_Base::$version );
	wp_localize_script( 'epkb-public-scripts', 'epkb_vars', array(
		'msg_try_again'         => esc_html__( 'Please try again later.', 'echo-knowledge-base' ),
		'error_occurred'        => esc_html__( 'Error occurred', 'echo-knowledge-base' ),
		'not_saved'             => esc_html__( 'Error occurred - configuration NOT saved.', 'echo-knowledge-base' ),
		'unknown_error'         => esc_html__( 'unknown error', 'echo-knowledge-base' ),
		'reload_try_again'      => esc_html__( 'Please reload the page and try again.', 'echo-knowledge-base' ),
		'save_config'           => esc_html__( 'Saving configuration', 'echo-knowledge-base' ),
		'input_required'        => esc_html__( 'Input is required', 'echo-knowledge-base' ),
		'reduce_name_size'      => esc_html__( 'Warning: Please reduce your name size. Tab will only show first 25 characters', 'echo-knowledge-base' ),
	));
}

/**
 * Only used for KB Configuration page
 */
function epkb_kb_config_load_public_css() {
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_style( 'epkb-public-styles', Echo_Knowledge_Base::$plugin_url . 'css/public-styles' . $suffix . '.css', array(), Echo_Knowledge_Base::$version );

	$kb_id = EPKB_KB_Handler::get_current_kb_id();

	$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );
	if ( is_wp_error( $kb_config ) ) {
		EPKB_Logging::add_log_var( 'Could not get KB config for ID', $kb_id );
		return;
	}

	echo epkb_frontend_kb_theme_styles_now( $kb_config );
}

/**
 * ADMIN-PLUGIN MENU PAGES (Plugin settings, reports, lists etc.)
 */
function epkb_load_admin_plugin_pages_resources(  ) {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	wp_enqueue_style( 'epkb-admin-plugin-pages-styles', Echo_Knowledge_Base::$plugin_url . 'css/admin-plugin-pages' . $suffix . '.css', array(), Echo_Knowledge_Base::$version );
	wp_enqueue_style( 'wp-color-picker' ); //Color picker

	wp_enqueue_script( 'epkb-admin-plugin-pages-scripts', Echo_Knowledge_Base::$plugin_url . 'js/admin-plugin-pages' . $suffix . '.js',
					array('jquery', 'jquery-ui-core','jquery-ui-dialog','jquery-effects-core','jquery-effects-bounce', 'jquery-ui-sortable'), Echo_Knowledge_Base::$version );
	wp_localize_script( 'epkb-admin-plugin-pages-scripts', 'epkb_vars', array(
					'msg_try_again'         => esc_html__( 'Please try again later.', 'echo-knowledge-base' ),
					'error_occurred'        => esc_html__( 'Error occurred', 'echo-knowledge-base' ),
					'not_saved'             => esc_html__( 'Error occurred - configuration NOT saved.', 'echo-knowledge-base' ),
					'unknown_error'         => esc_html__( 'unknown error', 'echo-knowledge-base' ),
					'reload_try_again'      => esc_html__( 'Please reload the page and try again.', 'echo-knowledge-base' ),
					'save_config'           => esc_html__( 'Saving configuration', 'echo-knowledge-base' ),
					'input_required'        => esc_html__( 'Input is required', 'echo-knowledge-base' ),
				));

	if ( isset($_REQUEST['page']) && $_REQUEST['page'] == 'epkb-kb-configuration' ) {
		wp_enqueue_script( 'epkb-admin-kb-config-script', Echo_Knowledge_Base::$plugin_url . 'js/admin-kb-config-script' . $suffix . '.js',
					array('jquery',	'jquery-ui-core', 'jquery-ui-dialog', 'jquery-effects-core', 'jquery-effects-bounce'), Echo_Knowledge_Base::$version );
		wp_localize_script( 'epkb-admin-kb-config-script', 'epkb_vars', array(
			'msg_try_again'         => esc_html__( 'Please try again later.', 'echo-knowledge-base' ),
			'error_occurred'        => esc_html__( 'Error occurred', 'echo-knowledge-base' ),
			'not_saved'             => esc_html__( 'Error occurred - configuration NOT saved.', 'echo-knowledge-base' ),
			'unknown_error'         => esc_html__( 'unknown error', 'echo-knowledge-base' ),
			'reload_try_again'      => esc_html__( 'Please reload the page and try again.', 'echo-knowledge-base' ),
			'save_config'           => esc_html__( 'Saving configuration', 'echo-knowledge-base' ),
			'input_required'        => esc_html__( 'Input is required', 'echo-knowledge-base' ),
			'reduce_name_size'      => esc_html__( 'Warning: Please reduce your name size. Tab will only show first 25 characters', 'echo-knowledge-base' ),
		));
	}

	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
}

/**
 * ADMIN-FEATURES complementing WP ADMIN PAGES (not ADMIN BAR)
 */
function epkb_load_admin_features_resources() {

	// if SCRIPT_DEBUG is off then use minified scripts
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	// EMPTY CSS wp_enqueue_style('epkb-admin-features-styles', Echo_Knowledge_Base::$plugin_url . 'css/admin-features' . $suffix . '.css', array(), Echo_Knowledge_Base::$version );
	wp_enqueue_script( 'epkb-admin-features-scripts', Echo_Knowledge_Base::$plugin_url . 'js/admin-features' . $suffix . '.js',
						array('jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'jquery-effects-core'), Echo_Knowledge_Base::$version );
	wp_enqueue_style( 'wp-jquery-ui-dialog' );
}

/**
 * ADMIN-BAR is visible  (BACK-END and sometimes user signed-in on the FRONT-END)
 */
function epkb_load_admin_bar_resources() {

	// if SCRIPT_DEBUG is off then use minified scripts
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

	/* NOT NEEDED YET
	wp_enqueue_style('epkb-admin-bar-styles', Echo_Knowledge_Base::$plugin_url . 'css/admin-bar' . $suffix . '.css', array(), Echo_Knowledge_Base::$version );
	wp_enqueue_script('epkb-admin-bar-scripts', Echo_Knowledge_Base::$plugin_url . 'js/admin-bar' . $suffix . '.js', array('jquery'), Echo_Knowledge_Base::$version );
	wp_localize_script( 'epkb-admin-bar-scripts', 'epkb_scripts', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) ); */
}

/**
 * Add style for current KB theme
 */
function epkb_frontend_kb_theme_styles() {

	$kb_id = EPKB_KB_Handler::get_kb_id_from_kb_main_shortcode();
	if ( empty( $kb_id ) ) {
		return;
	}

	$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );
	if ( is_wp_error( $kb_config ) ) {
		EPKB_Logging::add_log_var( 'Could not get KB config for ID', $kb_id );
		return;
	}

	echo epkb_frontend_kb_theme_styles_now( $kb_config );
}
add_action( 'wp_head', 'epkb_frontend_kb_theme_styles' );

function epkb_frontend_kb_theme_styles_now( $kb_config ) {

	return '<style type="text/css" id="epkb-advanced-style">
		#epkb-content-container .epkb-nav-tabs .active:after {
			border-top-color: ' . $kb_config['tab_nav_active_background_color'] . '!important
		}
		#epkb-content-container .epkb-nav-tabs .active {
			background-color: ' . $kb_config['tab_nav_active_background_color'] . '!important
		}
		#epkb-content-container .epkb-nav-tabs .active .epkb-category-level-1,
		#epkb-content-container .epkb-nav-tabs .active p {
			color: ' . $kb_config['tab_nav_active_font_color'] . '!important
		}
		#epkb-content-container .epkb-nav-tabs .active:before {
			border-top-color: ' . $kb_config['tab_nav_border_color'] . '!important
		}
	</style>
	';
}
