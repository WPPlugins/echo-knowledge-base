<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display plugin settings
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_Settings_Page {

	/**
	 * Display feature settings
	 */
	function display_plugin_settings_page() {

		ob_start(); ?>

		<div class="wrap">
			<h1></h1>
		</div>
		<div class="epkb-top-notice-message"><a hidden id="top"></a></div>
		<div class="wrap" id="ekb_core_top_heading">
			<div id="ekb-admin-page-wrap" class="ekb-admin-page-wrap epkb-plugin-info-container">  			 <?php

			$header_option = get_option( 'epkb_show_welcome_header' );
			if ( ! empty( $header_option ) ) { ?>

				<div class="welcome_header">

					<div class="container-fluid">
						<div class="row">

							<div class="col-5">
								<h1><?php echo esc_html__( 'Welcome to Knowledge Base for Documents and FAQs', 'echo-knowledge-base' ) .
								               ' ' . Echo_Knowledge_Base::$version; ?></h1>
								<p><?php
									$i18_doc_link = '<a href="http://www.echoknowledgebase.com/documentation/" target="_blank">' .
									                esc_html_e( 'documentation', 'echo-knowledge-base' ) . '</a>';
									$i18_rating_link = '<a href="https://wordpress.org/support/plugin/echo-knowledge-base/reviews/?filter=5" ' .
									   'target="_blank">' . esc_html_e( '5-stars', 'echo-knowledge-base' ) . '</a>.';
									printf( esc_html( _x( 'Thanks for using Knowledge Base. To get started, read over the %s ' .
									                 'and play with the settings. If you enjoy this plugin please consider telling a ' .
													 'friend, or rating it %s ', ' document link, rating link', 'echo-knowledge-base') ),
													$i18_doc_link, $i18_rating_link );  ?>
								</p>
							</div>
							<div class="col-2">
								<div class="logo">
									<img src="<?php echo Echo_Knowledge_Base::$plugin_url . 'img/kb-icon.png'; ?>">
								</div>
								<button id="close_intro"><?php esc_html_e( 'Close', 'echo-knowledge-base' ); ?></button>
							</div>

						</div>
					</div>

				</div>   <?php
			}

			self::display_page_details(); ?>

			</div>
		</div>  <?php

		echo ob_get_clean();
	}

	/**
	 * Display all configuration fields
	 */
	private static function display_page_details() {

		// $plugin_settings = epkb_get_instance()->settings_obj->get_settings();
		// $settings_specs = EPKB_Settings_Specs::get_fields_specification();
		$form = new EPKB_HTML_Elements();     		?>

		<form id="epkb_settings_form" method="post" action="#">

			<div id="epkb-tabs" class="plugin_settings_container">

				<!--  NAVIGATION TABS  -->

				<section class="epkb-main-nav">

					<ul class="epkb-admin-pages-nav-tabs">
						<li class="nav_tab active">
							<h2><span class="ep_icon_life_saver"></span> <?php esc_html_e( 'Help | Info', 'echo-knowledge-base' ); ?></h2>
							<p><?php esc_html_e( 'Docs / Contact Us / About Us', 'echo-knowledge-base' ); ?></p>
						</li>
						<li class="nav_tab">
							<h2><span class="ep_icon_comment"></span> <?php esc_html_e( 'Feedback', 'echo-knowledge-base' ); ?></h2>
							<p><?php esc_html_e( 'Let us know what you think?', 'echo-knowledge-base' ); ?></p>
						</li>
						<li class="nav_tab">
							<h2><span class="ep_icon_building"></span> <?php esc_html_e( 'About us', 'echo-knowledge-base' ); ?></h2>
							<p><?php esc_html_e( 'What else do we do?', 'echo-knowledge-base' ); ?></p>
						</li>
					</ul>

				</section>

				<!--  TABS CONTENT  -->

				<div id="main_panels" class="ekb-admin-pages-panel-container">

					<!--   PLUGIN WIDE SETTINGS -->

					<?php
						//$plugin_settings = epkb_get_instance()->settings_obj->get_settings();
						//apply_filters( 'epkb_settings_specs', $plugin_settings );   ?>


					<!--   HELP AND OTHER INFO -->

					<div class="ekb-admin-page-tab-panel container-fluid active">  <?php
						self::display_help();    ?>
					</div>

					<div class="ekb-admin-page-tab-panel container-fluid">   <?php
						self::display_feedback_form( $form );       ?>
					</div>

					<div class="ekb-admin-page-tab-panel container-fluid">
						<section>   <?php
							self::display_other_plugins();     ?>
						</section>
					</div>

				</div>

				<div id='epkb-ajax-in-progress' style="display:none;">
					<?php esc_html_e( 'Saving settings...', 'echo-knowledge-base' ); ?> <img class="epkb-ajax waiting" style="height: 30px;"
					                        src="<?php echo Echo_Knowledge_Base::$plugin_url . 'img/loading_spinner.gif'; ?>">
				</div>

			</div>

		</form>

		<div id="epkb-dialog-info-icon" title="" style="display: none;">
			<p id="epkb-dialog-info-icon-msg"><span class="ui-icon ui-icon-alert"
			                                        style="float:left; margin:0 7px 20px 0;"></span></p>
		</div>  <?php
	}

	private static function display_help() {    ?>
		<div class="row">
			<section class="col-3">
				<h3><?php esc_html_e( 'Getting Started / What\'s New', 'echo-knowledge-base' ); ?></h3>
				<p><?php esc_html_e( 'Read about what\'s new in the latest plugin update or how to get started.', 'echo-knowledge-base' ); ?></p>
				<a class="button primary-btn"
				   href="<?php echo admin_url(); ?>/index.php?page=epkb-welcome-page&tab=get-started"><?php esc_html_e( 'Welcome Page', 'echo-knowledge-base' ); ?></a>
			</section>
			<section class="col-3">
				<h3><?php esc_html_e( 'Full Documentation', 'echo-knowledge-base' ); ?></h3>
				<p><?php esc_html_e( 'Knowledge Base that explains all plugin features.', 'echo-knowledge-base' ); ?></p>
				<a class="button primary-btn" href="http://www.echoknowledgebase.com/documentation/"
				   target="_blank"><?php esc_html_e( 'Knowledge Base', 'echo-knowledge-base' ); ?></a>
			</section>
			<section class="col-3">
				<h3><?php esc_html_e( 'Still Need Some Help?', 'echo-knowledge-base' ); ?></h3>
				<p><?php esc_html_e( 'If you encounter an issue or have a question, please submit your request below.', 'echo-knowledge-base' ); ?></p>
				<a class="button primary-btn"
				   href="https://www.echoplugins.com/contact-us/?inquiry-type=technical&plugin_type=knowledge-base"
				   target="_blank"><?php esc_html_e( 'Contact Us', 'echo-knowledge-base' ); ?></a>
			</section>
		</div>   <?php
	}

	/**
	 * @param EPKB_HTML_Elements $form
	 */
	private static function display_feedback_form( $form ) {   ?>

		<div class="form_options">

			<section>
				<h3><?php esc_html_e( 'What features should we add or improve?', 'echo-knowledge-base' ); ?></h3>

				<ul>				<?php

					echo $form->text( array(
						'label'       => __( 'Email *', 'echo-knowledge-base' ),
						'name'        => 'your_email',
						'info'        => __( 'If you would like to hear back from us please provide us your email.', 'echo-knowledge-base' ),
						'type'        => EPKB_Input_Filter::TEXT,
						'max'         => '50',
						'label_class' => 'col-3',
						'input_class' => 'col-4'
					) );

					echo $form->text( array(
							'label'       => __( 'Name *', 'echo-knowledge-base' ),
							'name'        => 'your_name',
							'info'        => __( 'First name is sufficient.', 'echo-knowledge-base' ),
							'type'        => EPKB_Input_Filter::TEXT,
							'max'         => '50',
							'label_class' => 'col-3',
							'input_class' => 'col-4'
					) );

					echo $form->textarea( array(
						'label'       => __( 'Your Ideas and Feedback *', 'echo-knowledge-base' ),
						'name'        => 'your_feedback',
						'info'        => '',
						'type'        => EPKB_Input_Filter::TEXT,
						'max'         => '1000',
						'min'         => '3',
						'label_class' => 'col-3',
						'input_class' => 'col-4',
						'rows'        => 7
					) ); ?>

				</ul>
			</section>

			<div id='epkb-ajax-in-progress-feedback' style="display:none;">
				<?php esc_html_e( 'Sending your feedback... ', 'echo-knowledge-base' ); ?><img class="epkb-ajax waiting" style="height: 30px;"
				                              src="<?php echo Echo_Knowledge_Base::$plugin_url . 'img/loading_spinner.gif'; ?>">
			</div>

			<section style="padding-top: 20px;" class="save-settings">    <?php
				$form->submit_button( 'Send Feedback', 'epkb_send_feedback', 'send_feedback' ); ?>
			</section>

		</div>    <?php
	}

	private static function display_other_plugins() {   ?>

		<h3><?php esc_html_e( 'Our other Plugins', 'echo-knowledge-base' ); ?></h3>

		<div class="preview_product">
			<div class="top_heading">
				<h3><?php esc_html_e( 'Show IDs', 'echo-knowledge-base' ); ?></h3>
			</div>
			<div class="featured_img">
				<img src="<?php echo 'http://www.echoplugins.com/wp-content/uploads/2016/10/show_id_plugin.png'; ?>">
			</div>
			<div class="description">
				<p>
					<span><?php echo wp_kses_post( __( '<strong>Show IDs</strong> of post, pages and taxonomies.', 'echo-knowledge-base' ) ); ?></span>
				</p>
				<p><i><?php esc_html_e( 'Free on WordPress.org', 'echo-knowledge-base' ); ?></i></p>
			</div>
			<a class="button primary-btn" href="https://wordpress.org/plugins/echo-show-ids//"
			   target="_blank"><?php esc_html_e( 'Learn More', 'echo-knowledge-base' ); ?></a>

		</div>
		<div class="preview_product">
			<div class="top_heading">
				<h3><?php esc_html_e( 'Content Down Arrow', 'echo-knowledge-base' ); ?></h3>
			</div>
			<div class="featured_img">
				<img src="<?php echo 'http://www.echoplugins.com/wp-content/uploads/2016/10/arrow_plugin.png'; ?>">
			</div>
			<div class="description">
				<p>
					<span><?php echo wp_kses_post( __( 'Display <strong>downward-pointing arrow</strong> to indicate more content below.', 'echo-knowledge-base' ) ); ?></span>
				</p>
			</div>
			<a class="button primary-btn"
			   href="https://www.echoplugins.com/wordpress-plugins/content-down-arrow/"
			   target="_blank"><?php esc_html_e( 'Learn More', 'echo-knowledge-base' ); ?></a>

		</div>
		<div class="preview_product">
			<div class="top_heading">
				<h3><?php esc_html_e( 'Enhanced Publishing', 'echo-knowledge-base' ); ?></h3>
			</div>
			<div class="featured_img">
				<img src="<?php echo 'http://www.echoplugins.com/wp-content/uploads/2016/10/enhanced_publishing_blurb-3.png'; ?>">
			</div>
			<div class="description">
				<p>
					<span><?php echo wp_kses_post( __( 'Access recently modified <strong>posts, pages and custom post types</strong>
                        from the admin bar. Also, find any post from an easily accessible alphabetical lookup.', 'echo-knowledge-base' ) ); ?></span>
				</p>
				<p><i><?php esc_html_e( 'Free on WordPress.org', 'echo-knowledge-base' ); ?></i></p>
			</div>
			<a class="button primary-btn" href="https://wordpress.org/plugins/enhanced-publishing/"
			   target="_blank"><?php esc_html_e( 'Learn More', 'echo-knowledge-base' ); ?></a>
		</div>    <?php
	}
}