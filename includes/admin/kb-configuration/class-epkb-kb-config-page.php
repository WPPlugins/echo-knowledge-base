<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Display feature settings
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_KB_Config_Page {
	
	var $kb_config = array();
	/** @var  EPKB_KB_Config_Elements */
	var $form;
	var $feature_specs = array();
	var $kb_main_page_layout = EPKB_KB_Config_Layout_Basic::LAYOUT_NAME;
	var $kb_article_page_layout = EPKB_KB_Config_Layouts::KB_ARTICLE_PAGE_NO_LAYOUT;
	var $show_main_page = false;

	public function __construct( $kb_config=array() ) {
		$this->kb_config              = empty($kb_config) ? epkb_get_instance()->kb_config_ojb->get_current_kb_configuration() : $kb_config;
		$this->feature_specs          = EPKB_KB_Config_Specs::get_fields_specification( $this->kb_config['id'] );
		$this->form                   = new EPKB_KB_Config_Elements();
		$this->kb_main_page_layout    = EPKB_KB_Config_Layouts::get_kb_main_page_layout_name( $this->kb_config );
		$this->kb_article_page_layout = EPKB_KB_Config_Layouts::get_article_page_layout_name( $this->kb_config );
		$this->show_main_page         = isset($_REQUEST['epkb-demo']) || isset($_REQUEST['ekb-main-page']);
	}

	/**
	 * Displays the KB Config page with top panel + sidebar + preview panel
	 */
	public function display_kb_config_page() {

		// setup hooks for KB config fields for core layouts
		EPKB_KB_Config_Layouts::register_kb_config_hooks();

		// display all elements of the configuration page
		$this->display_page();
	}

	/**
	 * Display KB Config content areas
	 */
	private function display_page() {        ?>

		<div class="wrap">
			<h1></h1>
		</div>
		<div id="ekb-admin-page-wrap" class="ekb-admin-page-wrap epkb-config-container">
			<div class="epkb-config-wrapper">
				<div class="wrap" id="ekb_core_top_heading">
					<div><a hidden id="top"></a></div>
				</div>

				<div id="epkb-config-main-info">		<?php
					$this->display_top_panel();         ?>
                    <div class="epkb-open-mm">
                        <span class="ep_icon_arrow_carrot_down"></span>
                    </div>
				</div>                                  <?php

                    $this->top_panel_demo_info();       ?>

				<div id="epkb-admin-mega-menu" <?php echo $this->show_main_page ? 'class="epkb-active-page"' : ''; ?>>         <?php
                    $this->display_mega_menu();         ?>
				</div>                                  <?php

					$this->display_main_panel();

					$this->display_sidebar();			?>
			</div>

            <div class="epkb-kb-config-notice-message"></div>
		</div>
		<div id="epkb-dialog-sequence-now-custom"></div>

		<div id="epkb-dialog-info-icon" title="" style="display: none;">
			<p id="epkb-dialog-info-icon-msg"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
		</div>		<?php
	}


	/**************************************************************************************
	 *
	 *                   MEGA MENU
	 * Prefix mm = mega menu
	 *************************************************************************************/

	public function display_mega_menu() {

        echo '<div class="epkb-mm-sidebar">';

        $article_page_with_config_layout = $this->kb_article_page_layout != EPKB_KB_Config_Layouts::KB_ARTICLE_PAGE_NO_LAYOUT &&
                                           $this->kb_main_page_layout != EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT;

        /********************************************************************
         * 1. display MAIN PAGE and ARTICLE PAGE menu (right side)
         ********************************************************************/

        $main_page_core_links = array('SETUP', 'ORGANIZE', 'ALL TEXT', 'TUNING');
        $this->mega_menu_sidebar_links( array(
            'id'            => 'eckb-mm-mp-links',
            'core_links'    => $main_page_core_links,
            'add_on_links'  => array( 'Article', 'Widgets', 'Table of Contents', 'Article Feedback', 'Analytics', 'Advanced Search' )
        ));

        $article_page_core_links = $article_page_with_config_layout ? array('SETUP', 'FEATURES', 'ALL TEXT', 'TUNING') : array('SETUP', 'FEATURES', 'TUNING');
        $this->mega_menu_sidebar_links( array(
            'id'            => 'eckb-mm-ap-links',
            'core_links'    => $article_page_core_links,
            'add_on_links'  => ''
        ));

		echo '</div>';
	    echo '<div class="epkb-mm-content">';

		// if add-on is deactivated (even temporarily) then set the Main Page layout to Basic
		if ( ! in_array($this->kb_config['kb_main_page_layout'], EPKB_KB_Config_Layouts::get_main_page_layout_names()) ) {
			$this->kb_config['kb_main_page_layout'] = EPKB_KB_Config_Layout_Basic::LAYOUT_NAME;
		}

        // if add-on is deactivated (even temporarily) then set the Article Page layout to Article
        $article_page_layouts = EPKB_KB_Config_Layouts::get_article_page_layouts( $this->kb_main_page_layout );
        if ( ! in_array($this->kb_config['kb_article_page_layout'], $article_page_layouts) ) {
            $this->kb_config['kb_article_page_layout'] = EPKB_KB_Config_Layouts::KB_ARTICLE_PAGE_NO_LAYOUT;
        }

        $grid_layout = $this->kb_config['kb_main_page_layout'] == EPKB_KB_Config_Layouts::GRID_LAYOUT;

        /********************************************************************
         * 2. display MAIN PAGE menu content (left side)
         ********************************************************************/

        // MAIN PAGE - SETUP menu item
		$this->mega_menu_content_for_setup_option( array(
			'id'        => 'eckb-mm-mp-links-setup',
			//'class'     => 'epkb-mm-active',
			'sections'  => array(
				array(
					'heading' => '1. Layout',
					'form_elements' => array(
						array(
							'id'   => 'mega-menu-main-page-layout',
							'html' => $this->form->radio_buttons_vertical( array('name' => 'kb_main_page_layout_temp') + $this->feature_specs['kb_main_page_layout'] + array(
									'current'           => $this->kb_config['kb_main_page_layout'],
									'input_group_class' => 'config-col-12',
									'main_label_class'  => 'config-col-4',
									'input_class'       => '',
									'radio_class'       => 'config-col-12' ) )
						)
					)
				),
				array(
					'heading' => '2. Style',
					'form_elements' => array(
                        array(
                            'id'   => 'mega-menu-main-page-style',
                            'html' => $this->form->radio_buttons_vertical( array(
	                            'id' => 'main_page_reset_style',
	                            'name' => 'main_page_reset_style',
	                            'label' => '',
	                            'options' => EPKB_KB_Config_Layouts::get_main_page_style_names( $this->kb_config ),
	                            'input_group_class' => '',
	                            'main_label_class'  => '',
	                            'input_class'       => 'radio_buttons_resets',
	                            'radio_class'       => ''
                            )),
                        )
                    )
				),
				array(
					'heading' => '3. Colors',
					'form_elements' => array(
						array(
							'id'   => 'mega-menu-main-page-colors',
							'html' => $this->mega_menu_colors()
						)
					)
				),
                array(
                    'heading' => '4. Template',
                    'form_elements' => array(
                        array(
                            'id'   => 'mega-menu-main-page-kb-template',
                            'html' => $this->mega_menu_kb_templates()
                        )
                    )
                )
			)
		));

		// MAIN PAGE - ORGANIZE menu item
		$this->mega_menu_item_custom_html_content( array(
			'id'        => 'eckb-mm-mp-links-organize',
			'sections'  => array(
                array(
                    'heading' => 'Organize',
                    'links' => array(),
                    'form_elements' => array(
                        array(
                            'id'   => 'mega-menu-main-page-organize',
                            'html' => $this->mega_menu_organize()
                        )
                    )
                )
			)
		));

		// MAIN PAGE - ALL TEXT menu item
		$this->mega_menu_item_content( array(
			'id'        => 'eckb-mm-mp-links-alltext',
			'sections'  => array(
				array(
					'heading' => 'Text',
					'links' => array( 'Search Box', 'Categories', 'Articles' )
				),
			)
		));

		// MAIN PAGE - TUNING menu item
        $this->mega_menu_item_content( array(
            'id'        => 'eckb-mm-mp-links-tuning',
            'sections'  => array(
                array(
	                'heading' => 'Search Box',
	                'links' => array( 'Layout', 'Colors', 'Text', 'Advanced' )
                ),
                array(
	                'heading' => 'Content',
	                'links' => array( 'Style', 'Colors' )
                ),
                array(
	                'heading' => 'List of Articles',
                    'exclude' => $grid_layout,
	                'links' => array( 'Style', 'Colors', 'Text', 'Advanced' )
                ),
                array(
	                'heading' => 'Categories',
	                'links' => array( 'Style', 'Colors', 'Text', 'Advanced' )
                ),
            )
        ));


        /********************************************************************
         * 3. display ARTICLE PAGE menu content (left side)
         ********************************************************************/

		// ARTICLE PAGE - SETUP menu item
		if ( $article_page_with_config_layout ) {

	        $this->mega_menu_content_for_setup_option( array(
		        'id'        => 'eckb-mm-ap-links-setup',
		        'class'          => 'article-page-sidebar-layout-option',
		        'sections'  => array(
			        array(
				        'heading' => 'Layout',
				        'form_elements' => array(
					        array(
						        'id'   => 'mega-menu-article-page-layout',
						        'html' => $this->form->radio_buttons_vertical( array('options' => $article_page_layouts, 'name' => 'kb_article_page_layout_temp') +
                                                                               $this->feature_specs['kb_article_page_layout'] + array(
								        'current'           => $this->kb_config['kb_article_page_layout'],
								        'input_group_class' => 'config-col-12',
								        'main_label_class'  => 'config-col-4',
								        'input_class'       => '',
								        'radio_class'       => 'config-col-12' ) )
					        )
				        )
			        ),
			        array(
				        'heading' => 'Style',
				        'form_elements' => array(
					        array(
						        'id'   => 'mega-menu-article-page-style',
						        'html' => $this->form->radio_buttons_vertical( array('id' => 'article_page_reset_style', 'name' => 'article_page_reset_style','label' => '',
							        'options' => EPKB_KB_Config_Layouts::get_article_page_style_names( $this->kb_config ),
							        'input_group_class' => '',
							        'main_label_class'  => '',
							        'input_class'       => 'radio_buttons_resets',
							        'radio_class'       => ''
						        )),
					        )
				        )
			        ),
			        array(
				        'heading' => 'Colors',
				        'form_elements' => array(
					        array(
						        'id'   => 'mega-menu-article-page-colors',
						        'html' => $this->mega_menu_colors( false )
					        )
				        )
			        ),
                )
	        ));

        } else {
	        $this->mega_menu_content_for_setup_option( array(
		        'id'        => 'eckb-mm-ap-links-setup',
		        'class'          => 'article-page-article-layout-option',
		        'sections'  => array(
			        array(
				        'heading' => 'Layout',
				        'form_elements' => array(
					        array(
						        'id'   => 'mega-menu-article-page-layout',
						        'html' => $this->form->radio_buttons_vertical( array('options' => $article_page_layouts, 'name' => 'kb_article_page_layout_temp') + $this->feature_specs['kb_article_page_layout'] + array(
								        'current'           => $this->kb_config['kb_article_page_layout'],
								        'input_group_class' => 'config-col-12',
								        'main_label_class'  => 'config-col-4',
								        'input_class'       => '',
								        'radio_class'       => 'config-col-12' ) )
					        )
				        )
			        )
                )) );
        }

        // ARTICLE PAGE - FEATURES menu item
        $this->mega_menu_item_content( array(
            'id'        => 'eckb-mm-ap-links-features',
            'sections'  => array(
                array(
                    'heading' => 'Features',
                    'links' => array( 'Back Navigation', 'Comments', 'Breadcrumb' )
                ),
            )
        ));

        // ARTICLE PAGE - ALL TEXT menu item
        if ( $article_page_with_config_layout ) {
            // MAIN PAGE - ALL TEXT menu item
            $this->mega_menu_item_content( array(
                'id'       => 'eckb-mm-ap-links-alltext',
                'sections' => array(
                    array(
                        'heading' => 'Text',
                        'links'   => array( 'Search Box', 'Categories', 'Articles' )
                    ),
                )
            ) );
        }

        // ARTICLE PAGE - TUNING menu item
        if ( $article_page_with_config_layout ) {
            $this->mega_menu_item_content( array(
                'id'        => 'eckb-mm-ap-links-tuning',
                'sections'  => array(
                    array(
                        'heading' => 'Search Box',
                        'links' => array( 'Layout', 'Colors', 'Text', 'Advanced' )
                    ),
                    array(
                        'heading' => 'Content',
                        'links' => array( 'Style', 'Colors' )
                    ),
                    array(
                        'heading' => 'Categories',
                        'links' => array( 'Style', 'Colors', 'Text', 'Advanced' )
                    ),
                    array(
                        'heading' => 'List of Articles',
                        'links' => array( 'Style', 'Colors', 'Text', 'Advanced' )
                    ),
                    array(
                        'heading' => 'Article Common Path',
                        'links' => array( 'Configuration' )
                    ),
                )
            ));
        } else {
            $this->mega_menu_item_content( array(
                'id'        => 'eckb-mm-ap-links-tuning',
                'sections'  => array(
                    array(
                        'heading' => 'Article Common Path',
                        'links' => array( 'Configuration' )
                    ),
                )
            ));
        }

        echo '</div>';

		echo '<div class="epkb-close-mm">';
                echo '<span class="ep_icon_arrow_carrot_up"></span>';
        echo '</div>';
	}

	/**
	 * Display MAIN PAGE and ARTICLE PAGE Sidebar menu items on the right side of the Mega Menu
	 *
	 * @param array $args
	 */
	private function mega_menu_sidebar_links( $args = array() ) {

		echo '<ul class="' . ( empty($args['class']) ? '' : $args['class'] ) . '" id="' . $args['id'] . '">';

		$ix = 0;
		foreach( $args['core_links'] as $link ) {
			$class = $ix++ == 0 ? 'class="epkb-mm-active"' : '';
			$linkID = $args['id'] . '-' . str_replace(' ', '', strtolower( $link ) );
			echo '<li id="' . $linkID . '" ' . $class . '>' . $link . '</li>';
		}
		/* <li id="epkb-add-ons"> -- Add-ons --</li>
		if ( !empty( $args['add_on_links'] ) ){
			foreach( $args['add_on_links'] as $link ){
				$linkID = $args['id'].'-'.str_replace(' ', '', strtolower( $link ) );
				<li id="<?php echo $linkID; ?>">
					<?php echo $link; ?>
				</li>
			}
		}else{
			echo '<li id="epkb-add-ons"><a href="edit.php?post_type=epkb_post_type_11&page=epkb-add-ons">Get Add-ons</a></li>';
		}*/

		echo '</ul>';
	}

	/**
	 * Show content of a menu item (list of links on the right side)
	 *
	 * @param array $args
	 */
	private function mega_menu_item_content( $args = array() ) {

		echo '<div class="epkb-mm-links ' . ( empty($args['class']) ? '' : $args['class'] ) . '" id="' . $args['id'] . '-list' . '">';
		foreach( $args['sections'] as $section ) {

            if ( ! empty($section['exclude']) ) {
                continue;
            }

			echo '<section>' .
				'	<h3>' . ( empty($section['heading']) ? '' : $section['heading'] ) . '</h3>' .
			    '   <p>' . ( empty($section['info']) ? '' : $section['info'] ) .'</p>' .
				'	<ul>';

			foreach ( $section[ 'links'] as $link ) {
				$linkID = $args['id'] . '-' . str_replace( array( ' ', ':' ), '', strtolower($section['heading'] . '-' . $link ) );
				echo '<li id="' . $linkID . '">' . $link . '</li>';
			}

			echo '	</ul>' .
				'</section>';
		}
		echo '</div>';
	}

	private function mega_menu_item_custom_html_content( $args = array() ) {

		echo '<div class="epkb-mm-links ' . ( empty($args['class']) ? '' : $args['class'] ) . '" id="' . $args['id'] . '-list' . '">';
		foreach( $args['sections'] as $section ) {

			//if ( !empty( $section['heading'] ) ) {
			echo '<section>';
			echo '<h3>' . $section['heading'] . '</h3>';

			foreach ( $section[ 'form_elements'] as $html ) {
				echo '<div id="' . $html['id'] . '">';
				echo $html['html'];
				echo '</div>';
			}

			echo '</section>';
			//}

		}
		echo '</div>';
	}

    /**
     * Shows content of SETUP menu item on the left
     *
     * @param array $args
     */
	private function mega_menu_content_for_setup_option( $args = array() ) {

		echo '<div class="epkb-mm-links ' . ( empty($args['class']) ? '' : $args['class'] ) . '" id="' . $args['id'] . '-list' . '">';
		foreach( $args['sections'] as $section ) {

		    //if ( !empty( $section['heading'] ) ) {
			    echo '<section>';
			    echo '<h3>' . $section['heading'] . '</h3>';

			    foreach ( $section[ 'form_elements'] as $html ) {
				    echo '<div id="' . $html['id'] . '">';
				    echo $html['html'];
				    echo '</div>';
			    }

			    echo '</section>';
            //}

		}
		echo '</div>';
	}

	private function mega_menu_colors( $is_main_page=true ) {
		ob_start();	    ?>

        <div class="reset_colors" id="<?php echo $is_main_page ? 'main' : 'article'; ?>_page_reset_colors">
            <ul>
                <li class="config-col-12">Black / White</li>
                <li class="config-col-4">
                    <div class="color_palette black-white">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </li>
                <li class="config-col-8">
                    <ul class="epkb_rest_buttons">
                        <li><button type="button" value="black-white1">1</button></li>
                        <li><button type="button" value="black-white2">2</button></li>
                        <li><button type="button" value="black-white3">3</button></li>
                        <li><button type="button" value="black-white4">4</button></li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li class="config-col-12">Red</li>
                <li class="config-col-4">
                    <div class="color_palette red">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </li>
                <li class="config-col-8">
                    <ul class="epkb_rest_buttons">
                        <li><button type="button" value="red1">1</button></li>
                        <li><button type="button" value="red2">2</button></li>
                        <li><button type="button" value="red3">3</button></li>
                        <li><button type="button" value="red4">4</button></li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li class="config-col-12">Blue</li>
                <li class="config-col-4">
                    <div class="color_palette blue">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </li>
                <li class="config-col-8">
                    <ul class="epkb_rest_buttons">
                        <li><button type="button" value="blue1"> 1 </button></li>
                        <li><button type="button" value="blue2"> 2 </button></li>
                        <li><button type="button" value="blue3"> 3 </button></li>
                        <li><button type="button" value="blue4"> 4 </button></li>
                    </ul>
                </li>
            </ul>
            <ul>
                <li class="config-col-12">Green</li>
                <li class="config-col-4">
                    <div class="color_palette green">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </li>
                <li class="config-col-8">
                    <ul class="epkb_rest_buttons">
                        <li><button type="button" value="green1">1</button></li>
                        <li><button type="button" value="green2">2</button></li>
                        <li><button type="button" value="green3">3</button></li>
                        <li><button type="button" value="green4">4</button></li>
                    </ul>
                </li>
            </ul>
        </div>    <?php

		return ob_get_clean();
	}

	/**
	 * Display menu content for KB Template Choice
	 *
	 * @return string
	 */
	private function mega_menu_kb_templates() {

		ob_start();

		echo  $this->form->radio_buttons_vertical( array('name' => 'templates_for_kb_temp') + $this->feature_specs['templates_for_kb'] + array(
				'current'           => $this->kb_config['templates_for_kb'],
				'input_group_class' => 'config-col-12',
				'main_label_class'  => 'config-col-12',
				'input_class'       => '',
				'radio_class'       => 'config-col-12' ) );

        echo '<p><a href="http://www.echoknowledgebase.com/knowledge-base/kb-templates/" target="_blank">More about templates</a>';

		return ob_get_clean();
     }

	private function mega_menu_organize() {
        ob_start();
        echo '<p><strong>To Organize Categories and Articles:</strong></p>';
        echo '<p style="padding-left: 20px;">a) In the preview below, drag and drop categories and articles in any order </p>';
        echo '<p>   OR</p>';
        echo '<p style="padding-left: 20px;">b) In the configuration on the right, set chronological or alphabetical order</p>';
        return ob_get_clean();
	}


	/**************************************************************************************
	 *
	 *                   TOP PANEL
	 *
	 *************************************************************************************/

	/**
	 * Display top overview panel
	 */
	private function display_top_panel() {

        // display link to KB Main Page if any
		$link_output = EPKB_KB_Handler::get_first_kb_main_page_url( $this->kb_config );
		if ( ! empty($link_output) ) {
			$link_output = '<a href="' . $link_output . '" target="_blank"><div class="epkb-view ep_icon_external_link"></div></a>';
		}

        // for demo switch
		$checked = '';
		if ( isset($_REQUEST['epkb-demo']) || ( isset($_POST['epkb_demo_kb']) && $_POST['epkb_demo_kb'] == "true" ) ) {
			$checked = 'checked';
		}   ?>

		<div class="epkb-info-section epkb-kb-name-section">   <?php
			$this->display_list_of_kbs(); 			?>
		</div>
        <div class="epkb-info-section epkb-view">
            <?php echo $link_output; ?>
        </div>

		<div class="epkb-info-section epkb-info-main <?php echo $this->show_main_page ? '' : 'epkb-active-page'; ?>">
			<div class="overview-icon-container">
				<p>Overview</p>
				<div class="page-icon overview-icon ep_icon_data_report" id="epkb-config-overview"></div>
			</div>
		</div>

		<!--  MAIN PAGE BUTTONS -->
		<div class="epkb-info-section epkb-info-pages <?php echo $this->show_main_page ? 'epkb-active-page' : ''; ?>" id="epkb-main-page-button">
			<div class="page-icon-container">
				<p>Main Page</p>
				<div class="page-icon ep_icon_flow_chart" id="epkb-main-page"></div>
                <div id="epkb-user-flow-arrow" class="user_flow_arrow_icon  ep_icon_arrow_carrot_right"></div>
			</div>
		</div>

		<!--  ARTICLE PAGE BUTTONS -->
		<div class="epkb-info-section epkb-info-pages" id="epkb-article-page-button">
			<div class="page-icon-container">
				<p>Article Page</p>
				<div class="page-icon ep_icon_document" id="epkb-article-page"></div>
			</div>
		</div>

        <!--  DEMO SWITCH -->
        <div class="epkb-info-section epkb-demo-data-button">
            <div class="page-icon-container">
                <div class="epkb-data-switch">
                    <div class="switch-container">
                        <label class="switch">
                            <input id="epkb-layout-preview-data" type="checkbox" name="layout-preview-data" <?php echo $checked; ?>>
                            <div class="slider round"></div>
                            <div class="kb-name">Demo KB</div>
                            <div class="kb-demo">Current KB</div>
                        </label>
                    </div>
                </div>
            </div>
            <div class="ep_icon_info"></div>
        </div>

        <!--  SETTINGS BUTTONS -->
        <!--<div class="epkb-info-section epkb-mega-menu-toggle" id="epkb-settings-mega-menu-button">
            <div class="page-icon-container">
                <div class="epkb-setting-icon ep_icon_gear" id="epkb-settings-mega-menu"></div>
            </div>
        </div>-->
		<div class="epkb-info-section epkb-info-save" style="display:none;">			<?php
			$this->form->submit_button( array(
				'label'             => 'Save',
				'id'                => 'epkb_save_kb_config',
				'main_class'        => 'epkb_save_kb_config',
				'action'            => 'epkb_save_kb_config',
				'input_class'       => 'epkb-info-settings-button success-btn',
			) );

			$this->form->submit_button( array(
				'label'             => 'Cancel',
				'id'                => 'epkb_cancel_config',
				'main_class'        => 'epkb_cancel_config',
				'action'            => 'epkb_cancel_config',
				'input_class'       => 'epkb-info-settings-button error-btn',
			) );    ?>
		</div>		<?php
	}

	private function top_panel_demo_info(){ ?>
        <div class="epkb-demo-info-content hidden">
            <h5 class="option-info-title">Demo Data</h5>
            <p>The preview box below shows only a simplified version of the actual page. The preview can help you to visualize how changes to configuration will affect the page.</p>
            <p><strong>Current / Demo KB</strong>: You can switch to the <strong>Demo KB</strong> to see how populated Knowledge Base looks with a specific configuration. The Demo data is never saved to your KB.</p>
        </div>    <?php
    }

	/**************************************************************************************
	 *
	 *                   MAIN PANEL
	 *
	 *************************************************************************************/

	/**
	 * Display individual preview panels
	 */
	private function display_main_panel() {       ?>

		<div class="epkb-config-content" id="epkb-config-overview-content" <?php echo $this->show_main_page ? 'style="display: none;"' : ''; ?>>   <?php
			$this->display_overview();  	?>
		</div>

		<div class="epkb-config-content" id="epkb-main-page-content" <?php echo $this->show_main_page ? '' : 'style="display: none;"'; ?>>    <?php
			$this->display_kb_main_page_layout_preview();     ?>
		</div>

		<div class="epkb-config-content" id="epkb-article-page-content" style="display: none;">    <?php
			$this->display_article_page_layout_preview( true );     ?>
		</div>

		<input type="hidden" id="epkb_kb_id" value="<?php echo $this->kb_config['id']; ?>"/>   <?php
	}

	/**
	 * Display Overview Page
	 *
	 * @param array $articles_seq_data
	 * @param array $category_seq_data
	 */
	public function display_overview( $articles_seq_data=array(), $category_seq_data=array() ) {

		$kb_id = $this->kb_config['id'];

		$all_kb_terms      = EPKB_Categories_DB::get_kb_categories( $kb_id );
		$nof_kb_categories = $all_kb_terms === null ? 'unknown' : count( $all_kb_terms );
		$nof_kb_articles   = EPKB_Articles_DB::get_count_of_all_kb_articles( $kb_id );

		$kb_main_pages_url = '';
		$kb_main_pages_info = EPKB_KB_Handler::get_kb_main_pages( $this->kb_config );
		foreach( $kb_main_pages_info as $post_id => $post_info ) {
			$post_status = $post_info['post_status'] == 'Published' ? '' : ' (' . $post_info['post_status'] . ')';
			$kb_main_pages_url .= '  <li>' .	$post_info['post_title'] . $post_status . ' &nbsp;&nbsp;';
			$main_page_view_url = get_permalink( $post_id );
			$kb_main_pages_url .= '<a href="' . ( is_wp_error( $main_page_view_url ) ? '' : $main_page_view_url ) . '" target="_blank">View</a> ';
			$kb_main_pages_url .= ' &nbsp;&nbsp;<a href="' . get_edit_post_link( $post_id ) . '" target="_blank">' . esc_html__( 'Edit', 'echo-knowledge-base' ) . '</a></li>';
		}

		$kb_main_pages_url = empty($kb_main_pages_url) ? ' &nbsp None found' : $kb_main_pages_url;

		$upgrade_message = apply_filters( 'eckb_plugin_upgrade_message', '' );

		if ( ! empty($upgrade_message) ) { ?>

			<div class="callout callout_success epkb_upgrade_message">
				<h4>What's New</h4>     <?php
				echo $upgrade_message; ?>
				<button id="epkb_close_upgrade"><?php esc_html_e( 'Close', 'echo-knowledge-base' ); ?></button>
			</div>        <?php
		}			?>

		<div class="callout callout_info">
			<h4>Dashboard</h4>
            <div class="row">
                <div class="config-col-4">		            <?php
		            echo $this->form->text(  array(
				            'name' => 'kb_name_tmp', // used as placeholder to polute actual field in the sidebar <form>
				            'value' => isset($this->kb_config[ 'name' ]) ? $this->kb_config[ 'name' ] : $this->kb_config[ 'kb_name' ],  // TODO remove isset()
				            'input_group_class' => 'config-col-12',
				            'label_class' => 'config-col-3',
				            'input_class' => 'config-col-9'
			            ) + $this->feature_specs['kb_name'] );		            ?>
                </div>
                <div class="config-col-6">

                    <div class="config-col-3">			            <?php
			            $this->form->submit_button( array(
				            'label'             => 'Update',
				            'id'                => 'epkb_save_dashboard',
				            'main_class'        => 'epkb_save_dashboard',
				            'action'            => 'epkb_save_dashboard',
				            'input_class'       => 'epkb-info-settings-button'
			            ) );			            ?>
                    </div>
                    <div class="config-col-3">			            <?php
			            $this->form->submit_button( array(
				            'label'             => 'Cancel',
				            'id'                => 'epkb_cancel_dashboard',
				            'main_class'        => 'epkb_cancel_dashboard',
				            'action'            => 'epkb_cancel_dashboard',
				            'input_class'       => 'epkb-info-settings-button',
			            ) );			            ?>
                    </div>

                </div>
            </div>
		</div>  <?php

		echo EPKB_KB_Handler::get_kb_status_msg( $this->kb_config, $this->kb_config['kb_main_page_layout'], $articles_seq_data, $category_seq_data ); ?>

		<div class="callout callout_info">
			<h4>KB Main Page</h4>
			<p>To display a <strong>Knowledge Base Main page</strong>, add the following KB shortcode to any page: &nbsp;&nbsp;<strong>
					[<?php echo EPKB_KB_Handler::KB_MAIN_PAGE_SHORTCODE_NAME . ' id=' . $kb_id; ?>]</strong></p>
			<p><strong>Existing KB Main Page(s):</strong></p>
			<ul>
				<?php echo $kb_main_pages_url; ?>
			</ul>
		</div>

		<div class="callout callout_info">
			<h4>KB Categories</h4>
			<p><strong>KB Categories</strong> help you to organize KB articles into groups and hierarchies.</p>
			<ul>
				<li><a href="<?php echo admin_url('edit-tags.php?taxonomy=' . EPKB_KB_Handler::get_category_taxonomy_name( $kb_id ) .
				                                  '&post_type=' . EPKB_KB_Handler::get_post_type( $kb_id )); ?>">View all your Categories</a></li>
				<li>Total Categories: <?php echo $nof_kb_categories; ?></li>
			</ul>
		</div>

		<div class="callout callout_info">
			<h4>KB Articles</h4>
			<p><strong>KB article</strong> belongs to one or more KB categories or sub-categories.</p>
			<ul>
				<li><a href="<?php echo admin_url('edit.php?post_type=' . EPKB_KB_Handler::get_post_type( $kb_id )); ?>">View all your Articles</a></li>
				<li>Total Articles: <?php echo $nof_kb_articles; ?></li>
			</ul>

		</div>   <?php
	}

	/**
	 * Display the Main Page layout preview.
	 *
	 * @param bool $display
	 * @param array $articles_seq_data
	 * @param array $category_seq_data
	 * @return string
	 */
	public function display_kb_main_page_layout_preview( $display=true, $articles_seq_data=array(), $category_seq_data=array() ) {

		// retrieve KB preview using Current KB or Demo KB
		if ( isset($_REQUEST['epkb-demo']) || ( isset($_POST['epkb_demo_kb']) && $_POST['epkb_demo_kb'] == "true" ) ) {
			$demo_data = EPKB_KB_Demo_Data::get_category_demo_data( $this->kb_config['kb_main_page_layout'], $this->kb_config );
			$category_seq_data = $demo_data['category_seq'];
			$articles_seq_data = $demo_data['article_seq'];
		}

		$main_page_output = EPKB_Layouts_Setup::output_kb_page( $this->kb_config, true, $articles_seq_data, $category_seq_data );

		// setup test icons
		if ( $this->kb_config['kb_main_page_layout'] == EPKB_KB_Config_Layouts::GRID_LAYOUT &&
		     ( isset($_REQUEST['epkb-demo']) || ( isset($_POST['epkb_demo_kb']) && $_POST['epkb_demo_kb'] == "true" ) ) ) {
			$count = 2;
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_person', $main_page_output, $count );
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_shopping_cart', $main_page_output, $count );
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_money', $main_page_output, $count );
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_tag', $main_page_output, $count );
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_credit_card', $main_page_output, $count );
			$main_page_output = preg_replace( '/ep_icon_document/', 'ep_icon_building', $main_page_output, $count );
		}
		
		if ( $display ) {
			echo $main_page_output;
		}

		return $main_page_output;
	}

	/**
	 * Show Article Page preview
	 *
	 * @param bool $display
	 * @return mixed|string
	 */
	public function display_article_page_layout_preview( $display=false ) {

		$category_seq_data = array();
		$articles_seq_data = array();
		$is_demo = isset($_POST['epkb_demo_kb']) && $_POST['epkb_demo_kb'] == "true";

		// setup either current KB or demo KB data
		if ( $is_demo ) {
			$demo_data = EPKB_KB_Demo_Data::get_category_demo_data( $this->kb_config['kb_article_page_layout'], $this->kb_config );
			$category_seq_data = $demo_data['category_seq'];
			$articles_seq_data = $demo_data['article_seq'];
        } else {
            // $first_article = EPKB_Utilities::get_first_article( $category_seq_data, $articles_seq_data );
        }

        $temp_config = $this->kb_config;
        $temp_config['empty_article_content'] = EPKB_KB_Demo_Data::get_demo_article();

        $demo_article = new stdClass();
        $demo_article->ID = 0;
		$demo_article->post_title = 'Demo Article';
		$demo_article->post_content = '';
        $demo_article = new WP_Post( $demo_article );

		$temp_config[EPKB_Articles_Admin::KB_ARTICLES_SEQ_META] = $articles_seq_data;
		$temp_config[EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META] = $category_seq_data;
        $article_page_output = EPKB_Articles_Setup::get_article_content_and_features( $demo_article, EPKB_KB_Demo_Data::get_demo_article(), $temp_config );

        if ( EPKB_KB_Config_Layouts::is_article_page_displaying_sidebar( $this->kb_config['kb_article_page_layout'] ) ) {
            $article_page_output = EPKB_Articles_Setup::output_article_page_with_layout( $article_page_output, $temp_config, true, $is_demo, $articles_seq_data, $category_seq_data );
        }

        echo $display? $article_page_output : '';

		return $article_page_output;
	}


	/**************************************************************************************
	 *
	 *                   SIDEBARS: KB MAIN PAGE
	 *
	 *************************************************************************************/

    /**
     * Display SIDEBAR for given TOP icon - KB Main Page / Article Page
     */
    private function display_sidebar() {	    ?>

        <form id="epkb-config-config">

            <div class="epkb-sidebar-container" id="epkb-main-page-settings">
                <?php $this->display_kb_main_page_sections(); ?>
            </div>

            <div class="epkb-sidebar-container" id="epkb-article-page-settings">
                <?php $this->display_article_page_sections(); ?>
            </div>

            <div id='epkb-ajax-in-progress' style="display:none;">
                <?php esc_html__( 'Saving configuration', 'echo-knowledge-base' ); ?> <img class="epkb-ajax waiting" style="height: 30px;" src="<?php echo Echo_Knowledge_Base::$plugin_url . 'img/loading_spinner.gif'; ?>">
            </div>

            <div id="epkb-dialog-info-config" title="" style="display: none;">
                <p id="epkb-dialog-info-config-msg"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span></p>
            </div>

            <input type="text" name="kb_name" id="kb_name" value="<?php echo $this->kb_config['kb_name']; ?>" hidden>
	        <input type="text" name="kb_main_page_layout" id="kb_main_page_layout" value="<?php echo $this->kb_config['kb_main_page_layout']; ?>" hidden>
            <input type="text" name="kb_article_page_layout" id="kb_article_page_layout" value="<?php echo $this->kb_config['kb_article_page_layout']; ?>" hidden>
            <input type="text" name="templates_for_kb" id="templates_for_kb" value="<?php echo $this->kb_config['templates_for_kb']; ?>" hidden>

        </form>      <?php
    }

	/**
	 * Display all sidebar forms for MAIN PAGE
	 */
	private function display_kb_main_page_sections() {
        echo $this->get_main_page_templates_form();
        echo $this->get_main_page_order_form();
		echo $this->get_main_page_styles_form();
		echo $this->get_main_page_colors_form();
        echo $this->get_main_page_text_form();
	}

    /**
     * Generate form fields for the MAIN PAGE side bar
     */
    public function get_main_page_templates_form() {

        ob_start();     ?>

        <div class="epkb-config-sidebar" id="epkb-config-main-setup-sidebar">
            <div class="epkb-config-sidebar-options">                        <?php
                $feature_specs = EPKB_KB_Config_Specs::get_fields_specification( $this->kb_config['id'] );
                $form = new EPKB_KB_Config_Elements();

                $arg_bn_padding_top    = $feature_specs['templates_for_kb_padding_top'] + array( 'value' => $this->kb_config['templates_for_kb_padding_top'], 'current' => $this->kb_config['templates_for_kb_padding_top'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_bottom = $feature_specs['templates_for_kb_padding_bottom'] + array( 'value' => $this->kb_config['templates_for_kb_padding_bottom'], 'current' => $this->kb_config['templates_for_kb_padding_bottom'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_left   = $feature_specs['templates_for_kb_padding_left'] + array( 'value' => $this->kb_config['templates_for_kb_padding_left'], 'current' => $this->kb_config['templates_for_kb_padding_left'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_right  = $feature_specs['templates_for_kb_padding_right'] + array( 'value' => $this->kb_config['templates_for_kb_padding_right'], 'current' => $this->kb_config['templates_for_kb_padding_right'], 'text_class' => 'config-col-6' );

                $arg_bn_margin_top    = $feature_specs['templates_for_kb_margin_top'] + array( 'value' => $this->kb_config['templates_for_kb_margin_top'], 'current' => $this->kb_config['templates_for_kb_margin_top'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_bottom = $feature_specs['templates_for_kb_margin_bottom'] + array( 'value' => $this->kb_config['templates_for_kb_margin_bottom'], 'current' => $this->kb_config['templates_for_kb_margin_bottom'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_left   = $feature_specs['templates_for_kb_margin_left'] + array( 'value' => $this->kb_config['templates_for_kb_margin_left'], 'current' => $this->kb_config['templates_for_kb_margin_left'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_right  = $feature_specs['templates_for_kb_margin_right'] + array( 'value' => $this->kb_config['templates_for_kb_margin_right'], 'current' => $this->kb_config['templates_for_kb_margin_right'], 'text_class' => 'config-col-6' );

                $form->option_group( $feature_specs, array(
                    'info'              => array(),
                    'option-heading'    => 'Templates',
                    'class'             => 'eckb-mm-mp-links-setup-main-template',
                    'inputs'            => array(
	                    '0' => $form->multiple_number_inputs(
		                    array(
			                    'id'                => 'templates_for_kb_padding_group',
			                    'input_group_class' => '',
			                    'main_label_class'  => '',
			                    'input_class'       => '',
			                    'label'             => 'Padding( px )'
		                    ),
		                    array( $arg_bn_padding_top, $arg_bn_padding_bottom, $arg_bn_padding_left, $arg_bn_padding_right )
	                    ),
	                    '1' => $form->multiple_number_inputs(
		                    array(
			                    'id'                => 'templates_for_kb_margin_group',
			                    'input_group_class' => '',
			                    'main_label_class'  => '',
			                    'input_class'       => '',
			                    'label'             => 'Margin( px )'
		                    ),
		                    array( $arg_bn_margin_top, $arg_bn_margin_bottom , $arg_bn_margin_left, $arg_bn_margin_right )
	                    )
                    )
                )); ?>
            </div>
        </div>      <?php

        return ob_get_clean();
    }

    /**
	 * Generate form fields for the side bar
	 */
	public function get_main_page_order_form() {
		ob_start();	    ?>

		<div class="epkb-config-sidebar" id="epkb-config-ordering-sidebar" hidden>
			<div class="epkb-config-sidebar-options">            <?php

            $sequence_widets = array(
                '0' => $this->form->radio_buttons_vertical(
                    $this->feature_specs['categories_display_sequence'] +
                    array(
                        'id'        => 'front-end-columns',
                        'value'     => $this->kb_config['categories_display_sequence'],
                        'current'   => $this->kb_config['categories_display_sequence'],
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-12',
                        'input_class'       => 'config-col-12',
                        'radio_class'       => 'config-col-12'
                    )
                )
            );

            // Grid Layout does not show articles
            if ( $this->kb_main_page_layout != 'Grid' ) {
                $sequence_widets[1] = $this->form->radio_buttons_vertical(
                    $this->feature_specs['articles_display_sequence'] +
                    array(
                        'id'        => 'front-end-columns',
                        'value'     => $this->kb_config['articles_display_sequence'],
                        'current'   => $this->kb_config['articles_display_sequence'],
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-12',
                        'input_class'       => 'config-col-12',
                        'radio_class'       => 'config-col-12'
                    )
                );
            }

            $this->form->option_group( $this->feature_specs, array(
                'option-heading'    => 'Organize Categories and Articles',
                'class'             => 'eckb-mm-mp-links-organize--organize',
                'info' => array( 'categories_display_sequence', 'articles_display_sequence' ),
                'inputs' => $sequence_widets
            ));            ?>
            </div>
        </div>        <?php

		return ob_get_clean();
	}

	/**
	 * Generate form fields for the side bar
	 */
	public function get_main_page_styles_form() {
		ob_start();	    ?>

		<div class="epkb-config-sidebar" id="epkb-config-styles-sidebar">
			<div class="epkb-config-sidebar-options" id="epkb_style_sidebar_options">                <?php
				apply_filters( 'epkb_kb_main_page_style_settings', $this->kb_main_page_layout, $this->kb_config ); ?>
			</div>
		</div>      <?php

		return ob_get_clean();
	}

	/**
	 * Generate form fields for the side bar
	 */
	public function get_main_page_colors_form() {
		ob_start();	    ?>

		<div class="epkb-config-sidebar" id="epkb-config-colors-sidebar">
			<div class="epkb-config-sidebar-options">
				<?php apply_filters( 'epkb_kb_main_page_colors_settings', $this->kb_main_page_layout, $this->kb_config ); ?>
			</div>
		</div>			         <?php

		return ob_get_clean();
	}

	/**
	 * Generate form fields for the side bar
	 */
	public function get_main_page_text_form() {
		ob_start();	    ?>

		<div class="epkb-config-sidebar" id="epkb-config-text-sidebar">
			<div class="epkb-config-sidebar-options">
				<?php apply_filters( 'epkb_kb_main_page_text_settings', $this->kb_main_page_layout, $this->kb_config ); ?>
			</div>
		</div>			     <?php

		return ob_get_clean();
	}


	/**************************************************************************************
	 *
	 *                   SIDEBARS: ARTICLE PAGE
	 *
	 *************************************************************************************/

	private function display_article_page_sections() {
        echo $this->get_article_page_features_form();
        echo $this->get_article_page_styles_form();
        echo $this->get_article_page_colors_form();
        echo $this->get_article_page_text_form();
        echo $this->get_article_page_general_form();
	}

    /**
     * Generate form fields for the ARTICLE PAGE side bar
     */
    public function get_article_page_features_form() {

        ob_start();     ?>

        <div class="epkb-config-sidebar" id="epkb-config-article-features-sidebar">
            <div class="epkb-config-sidebar-options">                        <?php
                $feature_specs = EPKB_KB_Config_Specs::get_fields_specification( $this->kb_config['id'] );
                $form = new EPKB_KB_Config_Elements();


                // FETAURES - Back Navigation
                $arg_bn_padding_top    = $feature_specs['back_navigation_padding_top'] + array( 'value' => $this->kb_config['back_navigation_padding_top'], 'current' => $this->kb_config['back_navigation_padding_top'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_bottom = $feature_specs['back_navigation_padding_bottom'] + array( 'value' => $this->kb_config['back_navigation_padding_bottom'], 'current' => $this->kb_config['back_navigation_padding_bottom'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_left   = $feature_specs['back_navigation_padding_left'] + array( 'value' => $this->kb_config['back_navigation_padding_left'], 'current' => $this->kb_config['back_navigation_padding_left'], 'text_class' => 'config-col-6' );
                $arg_bn_padding_right  = $feature_specs['back_navigation_padding_right'] + array( 'value' => $this->kb_config['back_navigation_padding_right'], 'current' => $this->kb_config['back_navigation_padding_right'], 'text_class' => 'config-col-6' );

                $arg_bn_margin_top    = $feature_specs['back_navigation_margin_top'] + array( 'value' => $this->kb_config['back_navigation_margin_top'], 'current' => $this->kb_config['back_navigation_margin_top'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_bottom = $feature_specs['back_navigation_margin_bottom'] + array( 'value' => $this->kb_config['back_navigation_margin_bottom'], 'current' => $this->kb_config['back_navigation_margin_bottom'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_left   = $feature_specs['back_navigation_margin_left'] + array( 'value' => $this->kb_config['back_navigation_margin_left'], 'current' => $this->kb_config['back_navigation_margin_left'], 'text_class' => 'config-col-6' );
                $arg_bn_margin_right  = $feature_specs['back_navigation_margin_right'] + array( 'value' => $this->kb_config['back_navigation_margin_right'], 'current' => $this->kb_config['back_navigation_margin_right'], 'text_class' => 'config-col-6' );

                $form->option_group( $feature_specs, array(
                    'info'              => array('back_navigation_toggle', 'back_navigation_mode', 'back_navigation_text', 'back_navigation_text_color', 'back_navigation_bg_color',
                        'back_navigation_border_color', 'back_navigation_font_size', 'back_navigation_border', 'back_navigation_border_radius',
                        'back_navigation_border_radius', 'back_navigation_border_width'),
                    'option-heading'    => 'Back Navigation',
                    'class'             => 'eckb-mm-ap-links-features-features-backnavigation',
                    'inputs'            => array(
                        '0' => $form->checkbox( $feature_specs['back_navigation_toggle'] + array(
                                'value'             => $this->kb_config['back_navigation_toggle'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-2'
                            ) ),
                        '1' => $form->radio_buttons_vertical( $feature_specs['back_navigation_mode'] + array(
                                'value' => $this->kb_config['back_navigation_mode'],
                                'current'   => $this->kb_config['back_navigation_mode'],
                                'input_group_class' => 'config-col-12',
                                'main_label_class'  => 'config-col-4',
                                'input_class'       => 'config-col-8',
                                'radio_class'       => 'config-col-12'
                            ) ),
                        '2' => $form->text( $feature_specs['back_navigation_text'] + array(
                                'value'             => $this->kb_config['back_navigation_text'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                        '3' => $form->text( $feature_specs['back_navigation_text_color'] + array(
                                'value'             => $this->kb_config['back_navigation_text_color'],
                                'input_group_class' => 'config-col-12',
                                'class'             => 'ekb-color-picker',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5 ekb-color-picker'
                            ) ),
                        '4' => $form->text( $feature_specs['back_navigation_bg_color'] + array(
                                'value'             => $this->kb_config['back_navigation_bg_color'],
                                'input_group_class' => 'config-col-12',
                                'class'             => 'ekb-color-picker',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5 ekb-color-picker'
                            ) ),
                        '5' => $form->text( $feature_specs['back_navigation_border_color'] + array(
                                'value'             => $this->kb_config['back_navigation_border_color'],
                                'input_group_class' => 'config-col-12',
                                'class'             => 'ekb-color-picker',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5 ekb-color-picker'
                            ) ),
                        '6' => $form->text( $feature_specs['back_navigation_font_size'] + array(
                                'value' => $this->kb_config['back_navigation_font_size'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                        '7' => $form->radio_buttons_vertical( $feature_specs['back_navigation_border'] + array(
                                'value'             => $this->kb_config['back_navigation_border'],
                                'current'           => $this->kb_config['back_navigation_border'],
                                'input_group_class' => 'config-col-12',
                                'main_label_class'  => 'config-col-4',
                                'input_class'       => 'config-col-8',
                                'radio_class'       => 'config-col-12'
                            ) ),
                        '8' => $form->text( $feature_specs['back_navigation_border_radius'] + array(
                                'value' => $this->kb_config['back_navigation_border_radius'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                        '9' => $form->text( $feature_specs['back_navigation_border_width'] + array(
                                'value' => $this->kb_config['back_navigation_border_width'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),


                    )
                ));
                $form->option_group( $feature_specs, array(
                    'info'              => array(),
                    'option-heading'    => 'Back Navigation - Advanced',
                    'class'             => 'eckb-mm-ap-links-features-features-backnavigation',
                    'inputs'            => array(
	                    '0' => $form->multiple_number_inputs(
		                    array(
			                    'id'                => 'back_navigation_padding_group',
			                    'input_group_class' => '',
			                    'main_label_class'  => '',
			                    'input_class'       => '',
			                    'label'             => 'Padding( px )'
		                    ),
		                    array( $arg_bn_padding_top, $arg_bn_padding_bottom, $arg_bn_padding_left, $arg_bn_padding_right )
	                    ),
	                    '1' => $form->multiple_number_inputs(
		                    array(
			                    'id'                => 'back_navigation_margin_group',
			                    'input_group_class' => '',
			                    'main_label_class'  => '',
			                    'input_class'       => '',
			                    'label'             => 'Margin( px )'
		                    ),
		                    array( $arg_bn_margin_top, $arg_bn_margin_bottom, $arg_bn_margin_left, $arg_bn_margin_right )
	                    )
                    )
                ));


                // FETAURES - Comments
                $form->option_group( $feature_specs, array(
                    'info'              => array('articles_comments_global'),
                    'option-heading'    => 'Comments',
                    'class'             => 'eckb-mm-ap-links-features-features-comments',
                    'inputs'            => array(
                        '0' => $form->checkbox( $feature_specs['articles_comments_global'] + array(
                                'value'             => $this->kb_config['articles_comments_global'],
                                'current'           => $this->kb_config['articles_comments_global'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-3',
                                'input_class'       => 'config-col-9'	) ),
                    )
                ));


                // FETAURES - Breadcrumb
                $form->option_group( $feature_specs, array(
                    'info'              => array( 'breadcrumb_toggle', 'breadcrumb_icon_separator', 'breadcrumb_text_color', 'breadcrumb_description_text', 'breadcrumb_home_text'),
                    'option-heading'    => 'Breadcrumb',
                    'class'             => 'eckb-mm-ap-links-features-features-breadcrumb',
                    'inputs'            => array(
                        '0' => $form->checkbox( $feature_specs['breadcrumb_toggle'] + array(
                                'value'             => $this->kb_config['breadcrumb_toggle'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-2'
                            ) ),
                        '1' => $form->dropdown( $feature_specs['breadcrumb_icon_separator'] + array(
                                'value'             => $this->kb_config['breadcrumb_icon_separator'],
                                'current'           => $this->kb_config['breadcrumb_icon_separator'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                        '2' => $form->text( $feature_specs['breadcrumb_text_color'] + array(
                                'value'             => $this->kb_config['breadcrumb_text_color'],
                                'input_group_class' => 'config-col-12',
                                'class'             => 'ekb-color-picker',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5 ekb-color-picker'
                            ) ),
                        '3' => $form->text( $feature_specs['breadcrumb_description_text'] + array(
                                'value' => $this->kb_config['breadcrumb_description_text'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                        '4' => $form->text( $feature_specs['breadcrumb_home_text'] + array(
                                'value'             => $this->kb_config['breadcrumb_home_text'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-5'
                            ) ),
                    )
                ));


                // FETAURES - Breadcrumb - Advanced
                $arg_bc_top1 = $feature_specs['breadcrumb_padding_top'] + array( 'value' => $this->kb_config['breadcrumb_padding_top'], 'current' => $this->kb_config['breadcrumb_padding_top'], 'text_class' => 'config-col-6' );
                $arg_bc_btm2 = $feature_specs['breadcrumb_padding_bottom'] + array( 'value' => $this->kb_config['breadcrumb_padding_bottom'], 'current' => $this->kb_config['breadcrumb_padding_bottom'], 'text_class' => 'config-col-6' );
                $arg_bc_left3 = $feature_specs['breadcrumb_padding_left'] + array( 'value' => $this->kb_config['breadcrumb_padding_left'], 'current' => $this->kb_config['breadcrumb_padding_left'], 'text_class' => 'config-col-6' );
                $arg_bc_right4 = $feature_specs['breadcrumb_padding_right'] + array( 'value' => $this->kb_config['breadcrumb_padding_right'], 'current' => $this->kb_config['breadcrumb_padding_right'], 'text_class' => 'config-col-6' );

                $form->option_group( $feature_specs, array(
                    'info'              => array( 'breadcrumb_padding_top', 'breadcrumb_padding_bottom', 'breadcrumb_padding_left', 'breadcrumb_padding_right' ),
                    'option-heading'    => 'Breadcrumb - Advanced',
                    'class'             => 'eckb-mm-ap-links-features-features-breadcrumb',
                    'inputs'            => array(

	                    '0' => $form->multiple_number_inputs(
		                    array(
			                    'id'                => 'breadcrumb_padding_group',
			                    'input_group_class' => '',
			                    'main_label_class'  => '',
			                    'input_class'       => '',
			                    'label'             => 'Padding( px )'
		                    ),
		                    array( $arg_bc_top1, $arg_bc_btm2, $arg_bc_left3, $arg_bc_right4 )
	                    ),
                    )));


                // FETAURES - Tags
                /* $form->option_group( $feature_specs, array(
                    'info'              => array( 'tags_toggle' ),
                    'option-heading'    => 'Tags',
                    'class'             => 'eckb-mm-ap-links-features-features-tags',
                    'inputs'            => array(
                        '0' => $form->checkbox( $feature_specs['tags_toggle'] + array(
                                'value'             => $this->kb_config['tags_toggle'],
                                'input_group_class' => 'config-col-12',
                                'label_class'       => 'config-col-5',
                                'input_class'       => 'config-col-2'
                            ) ),
                ))); */     ?>
            </div>
        </div>      <?php

        return ob_get_clean();
    }

	/**
	 * Generate form fields for the ARTICLE PAGE side bar
	 */
	public function get_article_page_general_form() {

		ob_start();     ?>

		<div class="epkb-config-sidebar" id="epkb-config-article-general-sidebar">
			<div class="epkb-config-sidebar-options">
                <!-- ARTICLE COMMON PATH ( URL ) -->
                <div class="kb_articles_common_path_group" id="kb_articles_common_path_group">			   <?php
                    $this->display_articles_common_path();     ?>
                </div>
			</div>
		</div>      <?php

		return ob_get_clean();
	}

    /**
     * Generate form fields for the ARTICLE PAGE side bar
     *
     * @param string $no_output_text
     *
     * @return string
     */
	public function get_article_page_styles_form( $no_output_text='' ) {

        // if we have Sidebar on the Main Page then pretend the Article Page has just Article so that we don't get twice
        // these widgets
        if ( $this->kb_main_page_layout == EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT ) {
            return $no_output_text;
        }

        ob_start();        ?>

        <div class="epkb-config-sidebar" id="epkb-config-article-styles-sidebar">
            <div class="epkb-config-sidebar-options" id="epkb_style_sidebar_options">                <?php
                apply_filters( 'epkb_article_page_style_settings', $this->kb_article_page_layout, $this->kb_config ); ?>
            </div>
        </div>      <?php

        return ob_get_clean();
    }

    /**
     * Generate form fields for the ARTICLE PAGE side bar
     *
     * @param string $no_output_text
     *
     * @return string
     */
	public function get_article_page_colors_form( $no_output_text='' ) {

        // if we have Sidebar on the Main Page then pretend the Article Page has just Article so that we don't get twice
        // these widgets
        if ( $this->kb_main_page_layout == EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT ) {
            return $no_output_text;
        }

        ob_start();         ?>

		<div class="epkb-config-sidebar" id="epkb-config-article-colors-sidebar">
			<div class="epkb-config-sidebar-options">
				<?php apply_filters( 'epkb_article_page_colors_settings', $this->kb_article_page_layout, $this->kb_config ); ?>
			</div>
		</div>      <?php

		return ob_get_clean();
	}

    /**
     * Generate form fields for the ARTICLE PAGE side bar
     *
     * @param string $no_output_text
     *
     * @return string
     */
	public function get_article_page_text_form( $no_output_text='' ) {

        // if we have Sidebar on the Main Page then pretend the Article Page has just Article so that we don't get twice
        // these widgets
        if ( $this->kb_main_page_layout == EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT ) {
            return $no_output_text;
        }

        ob_start();     ?>

		<div class="epkb-config-sidebar" id="epkb-config-article-text-sidebar">
			<div class="epkb-config-sidebar-options">   <?php
				// for now always generate Text for Sidebar; once we have more article layouts pass in the actual article layout (an update config controller for Ajax)
				apply_filters( 'epkb_article_page_text_settings', EPKB_KB_Config_Layouts::SIDEBAR_LAYOUT, $this->kb_config ); ?>
			</div>
		</div>      <?php

		return ob_get_clean();
	}


	/**************************************************************************************
	 *
	 *                   OTHERS / SUPPORT FUNCTIONS
	 *
	 *************************************************************************************/

	private function display_list_of_kbs() {

		// TODO active from other tabs and the other way around

		if ( ! defined('EM' . 'KB_PLUGIN_NAME') ) {
			$kb_name = isset($this->kb_config[ 'name' ]) ? $this->kb_config[ 'name' ] : $this->kb_config[ 'kb_name' ];  // TODO remove isset()
			echo '<h1 class="epkb-kb-name">' . esc_html( $kb_name ) . '</h1>';
			return;
		}

		// output the list
		$list_output = '<select class="epkb-kb-name" id="epkb-list-of-kbs">';
		$all_kb_configs = epkb_get_instance()->kb_config_ojb->get_kb_configs();
		foreach ( $all_kb_configs as $one_kb_config ) {

			if ( $one_kb_config['status'] == EPKB_KB_Status::ARCHIVED ) {
				continue;
			}

			$kb_name = isset($one_kb_config[ 'name' ]) ? $one_kb_config[ 'name' ] : $one_kb_config[ 'kb_name' ];  // TODO remove isset()
			$active = ( $this->kb_config['id'] == $one_kb_config['id'] ? 'selected' : '' );
			$tab_url = 'edit.php?post_type=epkb_post_type_' . $one_kb_config['id'] . '&page=epkb-kb-configuration';

			$list_output .= '<option value="' . $one_kb_config['id'] . '" ' . $active . ' data-kb-admin-url=' . esc_url($tab_url) . '>' . esc_html( $kb_name ) . '</option>';
			$list_output .= '</a>';
		}


		$list_output .= '</select>';

		echo $list_output;
	}

	/**
	 * Show list of commmon paths for articles
	 *
	 * @return string
	 */
	public function display_articles_common_path() {

        $common_path = $this->kb_config['kb_articles_common_path'];

        // find if one of the KB Main Pages is selected; if not and we don't have custom path, select first one
        $selected_post_id = 0;
        $first_post_id = 0;
        $kb_main_pages_info = EPKB_KB_Handler::get_kb_main_pages( $this->kb_config );
        foreach ( $kb_main_pages_info as $post_id => $post_info ) {
            $first_post_id = empty($first_post_id) ? $post_id : $first_post_id;
            if ( $post_info['post_slug'] == $common_path ) {
                $selected_post_id = $post_id;
            }
        }

        $selected_post_id = empty($common_path) ? $first_post_id : $selected_post_id;

		$this->form->option_group( $this->feature_specs, array(
			'option-heading'    => 'Article Path ( URL )',
			'info'              => '<p>This is recommended for advanced users, support will be at a minimum for more information about
			                                       this feature read more information <a href="https://codex.wordpress.org/Glossary#Slug" target="_blank">here
													on wordpress.org</a></p>',
			'class'             => 'eckb-mm-ap-links-tuning-articlecommonpath-configuration',
			'inputs'            => array(
				'0'         => $this->common_path_kb_main_page_slug( $selected_post_id ),
				'1'         => $this->common_path_custom_slug( $common_path, $selected_post_id )
			)
		));
	}

    /**
     * Show list of commmon paths for articles
     *
     * @param $selected_post_id
     * @return string
     */
	public function common_path_kb_main_page_slug( $selected_post_id ) {
        $kb_main_pages_info = EPKB_KB_Handler::get_kb_main_pages( $this->kb_config );

		ob_start();	    ?>

		<div class="option-heading config-col-12">
			<p> KB Article URL:<br> &nbsp;&nbsp;&nbsp;website address / common path / KB article slug</p>
		</div>

		<h4 class="main_label config-col-12">Common path set to KB Main Page slug:</h4>
		<div class="radio-buttons-vertical config-col-12" id="">
			<ul>               <?php

				$ix = 0;
				foreach( $kb_main_pages_info as $post_id => $post_info ) {

                    $kb_home_slug = $post_info['post_slug'];

                    // for static pages we don't have KB Page slug
                   /* if ( 'page' == get_option( 'show_on_front' ) && $first_page_id = get_option( 'page_on_front' ) ) {
                        $kb_home_slug = '';
                    } */

					$checked1 = $post_id == $selected_post_id ? 'checked="checked" ' : '';
					$label = site_url() . '/<strong><a href="' . get_edit_post_link( $post_id ) . '" target="_blank">' . esc_attr($kb_home_slug) . "</a></strong>" .
                             ( empty($kb_home_slug) ? '' : '/' ) . '<span style="font-style:italic;">KB-article-slug</span>';    			?>

					<li class="config-col-12">
						<div class="input_container config-col-1">
							<input type="radio" name="kb_articles_common_path_rbtn"
							       id="<?php echo 'article_common_path_'.$ix; ?>"
							       value="<?php echo esc_attr($kb_home_slug); ?>"
								<?php echo $checked1; ?>  />
						</div>
						<label class="config-col-10" for="<?php echo 'article_common_path_'.$ix ?>">
							<?php echo $label ?>
						</label>
					</li>  		<?php

					$ix++;
				}

				if ( $ix == 0 ) {   ?>
					<li class="config-col-12">No KB Main Page found.</li>      <?php
				}     ?>

			</ul>
		</div>		<?php

		return ob_get_clean();
	}

    /**
     * Show custom path for articles common path
     *
     * @param $common_path
     * @param $selected_post_id
     * @return string
     */
	private function common_path_custom_slug( $common_path, $selected_post_id ) {

		ob_start();		?>

		<div class="kb_custom_slug kb_articles_common_path_group" id="kb_articles_common_path_group">
			<h4 class="main_label config-col-12">Common path set to custom slug:</h4>
			<div class="radio-buttons-vertical config-col-12" id="">
				<ul>   			<?php
                    $shared_path_input = empty($selected_post_id) ? $common_path : '';
                    $checked2 = empty($shared_path_input) ? '' : 'checked="checked" ';
                    $label = site_url() . '/' . ' <input type="text" name="kb_articles_common_path" id="kb_articles_common_path" autocomplete="off"
                                                                       value="' . esc_attr( $shared_path_input ) . '" placeholder="Enter slug here" maxlength="50"
                                                                        style="width: 250px;">/<span style="font-style:italic;">KB-article-slug</span>'; ?>
                    <li class="config-col-12">
                        <div class="input_container config-col-1">
                            <input type="radio" name="kb_articles_common_path_rbtn"
                                   id="<?php echo 'article_common_path_99'; ?>"
                                   value="path_custom_slug"
                                <?php echo $checked2; ?> />
                        </div>
                        <label class="config-col-10" for="<?php echo 'article_common_path_99' ?>">
                            <?php echo $label ?>
                        </label>
                    </li>

                    <li class="config-col-12" style="color:red;">For expert users only. Backup your site first. This can break your site navigation! Limited support available.</li>
				</ul>
			</div>
		</div>		<?php

		return ob_get_clean();
	}

}
