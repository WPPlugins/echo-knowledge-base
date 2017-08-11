<?php
/**
 * The template for displaying single KB Article.
 *
 * This template can be overridden by copying it to yourtheme/kb_templates/single-article.php.
 *
 * HOWEVER, on occasion Echo Plugins will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @author 		Echo Plugins
 * @version     1.0.0
 */

global $post;

// this is KB Article URL so get KB ID
$kb_id = EPKB_KB_Handler::get_kb_id_from_post_type( $post->post_type );
if ( is_wp_error($kb_id) ) {
    $kb_id = EPKB_KB_Config_DB::DEFAULT_KB_ID;
}

// if this is  SBL then display KB Page title
$kb_main_pg_title = '';
$kb_main_pg_layout = epkb_get_instance()->kb_config_ojb->get_value( 'kb_main_page_layout', $kb_id );
$kb_article_pg_layout = epkb_get_instance()->kb_config_ojb->get_value( 'kb_article_page_layout', $kb_id );
if ( EPKB_KB_Config_Layouts::is_main_page_displaying_sidebar( $kb_main_pg_layout ) ||
     EPKB_KB_Config_Layouts::is_article_page_displaying_sidebar( $kb_article_pg_layout ) ) {
    $kb_main_pages_info = epkb_get_instance()->kb_config_ojb->get_value( 'kb_main_pages', $kb_id, null );
    if ( ! empty($kb_main_pages_info) ) {
        reset($kb_main_pages_info);
        $page_id = key($kb_main_pages_info);
        $kb_main_pg_title = '<h1 class="main_title">' . get_the_title( $page_id ) . '</h1>';
    }
}

$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $kb_id );

/**
 * Display ARTICLE PAGE content
 */
get_header();

$template_style1 = EPKB_Utilities::get_inline_style(
           'padding-top::       templates_for_kb_padding_top,
	        padding-bottom::    templates_for_kb_padding_bottom,
	        padding-left::      templates_for_kb_padding_left,
	        padding-right::     templates_for_kb_padding_right,
	        margin-top::        templates_for_kb_margin_top,
	        margin-bottom::     templates_for_kb_margin_bottom,
	        margin-left::       templates_for_kb_margin_left,
	        margin-right::      templates_for_kb_margin_right,', $kb_config );       ?>

	<div id="epkb-main-content" <?php echo $template_style1; ?>>
		<div class="epkb-container">        <?php

			// display KB Page Title
            echo $kb_main_pg_title;

			while ( have_posts() ) {

			    the_post();

				if ( post_password_required() ) {
					echo get_the_password_form();
					return;
				}

				the_content();

			}          	?>

		</div><!-- .epkb-container -->
	</div><!-- #epkb-main-content -->   <?php

// TODO output sidebar() as an option

get_footer();