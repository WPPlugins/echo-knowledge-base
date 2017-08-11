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

global $eckb_kb_id;

$kb_config = epkb_get_instance()->kb_config_ojb->get_kb_config( $eckb_kb_id );
if ( is_wp_error( $kb_config ) ) {
    $kb_config = EPKB_KB_Config_Specs::get_default_kb_config( $eckb_kb_id );
}

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
	        margin-right::      templates_for_kb_margin_right,',	$kb_config );       ?>

	<div id="epkb-main-content" <?php echo $template_style1; ?>>
		<div class="epkb-container">        <?php

            echo '<h1 class="main_title">' . get_the_title() . '</h1>';

			while ( have_posts() ) {

			    the_post();

				if ( post_password_required() ) {
					echo get_the_password_form();
					return;
				}

				echo EPKB_Layouts_Setup::output_kb_page( $kb_config );
				
			}          	?>

		</div><!-- .epkb-container -->
	</div><!-- #epkb-main-content -->   <?php

// TODO output sidebar() as an option

get_footer();