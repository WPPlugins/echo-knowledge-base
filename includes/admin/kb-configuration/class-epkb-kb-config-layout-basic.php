<?php

/**
 * Lists settings, default values and display of BASIC layout.
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */
class EPKB_KB_Config_Layout_Basic {

    const LAYOUT_NAME = 'Basic';
    const CATEGORY_LEVELS = 3;

    // styles available for this layout
    const LAYOUT_STYLE_1 = EPKB_KB_Config_Layouts::KB_DEFAULT_LAYOUT_STYLE;
    const LAYOUT_STYLE_2 = 'Boxed';
    const LAYOUT_STYLE_3 = 'Style3';

    // search box styles available for this layout
    const SEARCH_BOX_LAYOUT_STYLE_1 = 'Basic';
    const SEARCH_BOX_LAYOUT_STYLE_2 = 'todo1';
    const SEARCH_BOX_LAYOUT_STYLE_3 = 'todo2';
    const SEARCH_BOX_LAYOUT_STYLE_4 = 'todo4';


    /**
     * Defines KB configuration for this theme.
     * ALL FIELDS ARE MANDATORY by default ( otherwise use 'mandatory' => 'false' )
     *
     * @return array with both basic and theme-specific configuration
     */
    public static function get_fields_specification() {

        $config_specification = array(
        );

        return $config_specification;
    }

    /**
     * Return HTML for settings controlling the Layout style
     *
     * @param $kb_page_layout
     * @param $kb_config
     * @return String $kb_main_page_layout
     */
    public static function get_kb_config_style( $kb_page_layout, $kb_config ) {

        if ( $kb_page_layout != self::LAYOUT_NAME ) {
            return $kb_page_layout;
        }

        $feature_specs = EPKB_KB_Config_Specs::get_fields_specification( $kb_config['id'] );
        $form = new EPKB_KB_Config_Elements();

        //Arg1 / Arg2  for text_and_select_fields_horizontal
        $arg1 = $feature_specs['section_body_height'] + array( 'value' => $kb_config['section_body_height'], 'current' => $kb_config['section_body_height'], 'input_group_class' => 'config-col-6', 'input_class' => 'config-col-12' );
        $arg2 = $feature_specs['section_box_height_mode'] + array( 'value'    => $kb_config['section_box_height_mode'], 'current'  => $kb_config['section_box_height_mode'], 'input_group_class' => 'config-col-6', 'input_class' => 'config-col-12' );

        //Advanced Settings
        $arg1_search_box_padding_vertical   = $feature_specs['search_box_padding_top'] + array( 'value' => $kb_config['search_box_padding_top'], 'current' => $kb_config['search_box_padding_top'], 'text_class' => 'config-col-6' );
        $arg2_search_box_padding_vertical   = $feature_specs['search_box_padding_bottom'] + array( 'value' => $kb_config['search_box_padding_bottom'], 'current' => $kb_config['search_box_padding_bottom'], 'text_class' => 'config-col-6' );
        $arg1_search_box_padding_horizontal = $feature_specs['search_box_padding_left'] + array( 'value' => $kb_config['search_box_padding_left'], 'current' => $kb_config['search_box_padding_left'], 'text_class' => 'config-col-6' );
        $arg2_search_box_padding_horizontal = $feature_specs['search_box_padding_right'] + array( 'value' => $kb_config['search_box_padding_right'], 'current' => $kb_config['search_box_padding_right'], 'text_class' => 'config-col-6' );
        $arg1_search_box_margin_vertical = $feature_specs['search_box_margin_top'] + array( 'value' => $kb_config['search_box_margin_top'], 'current' => $kb_config['search_box_margin_top'], 'text_class' => 'config-col-6' );
        $arg2_search_box_margin_vertical = $feature_specs['search_box_margin_bottom'] + array( 'value' => $kb_config['search_box_margin_bottom'], 'current' => $kb_config['search_box_margin_bottom'], 'text_class' => 'config-col-6' );

        $arg1_box_border = $feature_specs['section_border_radius'] + array( 'value' => $kb_config['section_border_radius'], 'current' => $kb_config['section_border_radius'], 'text_class' => 'config-col-6' );
        $arg2_box_border = $feature_specs['section_border_width'] + array( 'value' => $kb_config['section_border_width'], 'current' => $kb_config['section_border_width'], 'text_class' => 'config-col-6' );

        $arg1_section_head_padding_vertical = $feature_specs['section_head_padding_top'] + array( 'value' => $kb_config['section_head_padding_top'], 'current' => $kb_config['section_head_padding_top'], 'text_class' => 'config-col-6' );
        $arg2_section_head_padding_vertical = $feature_specs['section_head_padding_bottom'] + array( 'value' => $kb_config['section_head_padding_bottom'], 'current' => $kb_config['section_head_padding_bottom'], 'text_class' => 'config-col-6' );
        $arg1_section_head_padding_horizontal = $feature_specs['section_head_padding_left'] + array( 'value' => $kb_config['section_head_padding_left'], 'current' => $kb_config['section_head_padding_left'], 'text_class' => 'config-col-6' );
        $arg2_section_head_padding_horizontal = $feature_specs['section_head_padding_right'] + array( 'value' => $kb_config['section_head_padding_right'], 'current' => $kb_config['section_head_padding_right'], 'text_class' => 'config-col-6' );

        $arg1_section_body_padding_vertical = $feature_specs['section_body_padding_top'] + array( 'value' => $kb_config['section_body_padding_top'], 'current' => $kb_config['section_body_padding_top'], 'text_class' => 'config-col-6' );
        $arg2_section_body_padding_vertical = $feature_specs['section_body_padding_bottom'] + array( 'value' => $kb_config['section_body_padding_bottom'], 'current' => $kb_config['section_body_padding_bottom'], 'text_class' => 'config-col-6' );
        $arg1_section_body_padding_horizontal = $feature_specs['section_body_padding_left'] + array( 'value' => $kb_config['section_body_padding_left'], 'current' => $kb_config['section_body_padding_left'], 'text_class' => 'config-col-6' );
        $arg2_section_body_padding_horizontal = $feature_specs['section_body_padding_right'] + array( 'value' => $kb_config['section_body_padding_right'], 'current' => $kb_config['section_body_padding_right'], 'text_class' => 'config-col-6' );

	    $article_spacing_arg1 = $feature_specs['article_list_margin'] +  array(
			    'value'             => $kb_config['article_list_margin'],
			    'id'                => 'article_list_margin',
			    'input_group_class' => 'config-col-12',
			    'label_class'       => 'config-col-5',
			    'input_class'       => 'config-col-3'
		    );
	    $article_spacing_arg2 = $feature_specs['article_list_spacing'] +  array(
			    'value'             => $kb_config['article_list_spacing'],
			    'id'                => 'article_list_spacing',
			    'input_group_class' => 'config-col-12',
			    'label_class'       => 'config-col-5',
			    'input_class'       => 'config-col-3'
		    );

	    $search_input_input_arg1 = $feature_specs['search_box_input_width'] + array(
			    'value'             => $kb_config['search_box_input_width'],
			    'input_group_class' => 'config-col-12',
			    'label_class'       => 'config-col-6',
			    'input_class'       => 'config-col-2'

		    );
	    $search_input_input_arg2 = $feature_specs['search_input_border_width'] + array(
			    'value' => $kb_config['search_input_border_width'],
			    'input_group_class' => 'config-col-12',
			    'label_class'       => 'config-col-6',
			    'input_class'       => 'config-col-2'
		    );


        // SEACH BOX - Layout
        $form->option_group( $feature_specs, array(
            'info' => array( 'search_layout' ),
            'option-heading' => 'Search Layout',
            'class'        => 'eckb-mm-mp-links-tuning-searchbox-layout',
            'inputs' => array(
                '0' => $form->dropdown( $feature_specs['search_layout'] + array(
                        'value' => $kb_config['search_layout'],
                        'current' => $kb_config['search_layout'],
                        'label_class' => 'config-col-3',
                        'input_class' => 'config-col-7'
                    ) )
            )
        ));

        // SEACH BOX - Advanced Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'search_box_padding_top', 'search_box_padding_bottom', 'search_box_padding_left', 'search_box_padding_right', 'search_box_margin_top',
                             'search_box_margin_bottom', 'search_box_input_width', 'search_input_border_width' ),
            'option-heading' => 'Search Box - Advanced Style',
            'class'        => 'eckb-mm-mp-links-tuning-searchbox-advanced',
            'inputs' => array(
	            '0' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'search_box_padding',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Search Box Padding( px )'
		            ),
		            array( $arg1_search_box_padding_vertical, $arg2_search_box_padding_vertical ,$arg1_search_box_padding_horizontal, $arg2_search_box_padding_horizontal )
	            ),
	            '1' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'search_box_margin',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Search Box Margin( px )'
		            ),
		            array( $arg1_search_box_margin_vertical, $arg2_search_box_margin_vertical )
	            ),
	            '2' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'search_box_input_width_group',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Search Box Input ( % ) ( px )'
		            ),
		            array( $search_input_input_arg1, $search_input_input_arg2 )
	            ),

         )));

        // CONTENT - Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'width', 'nof_columns', 'section_font_size' ),
            'option-heading' => 'Content - Style',
            'class'          => 'eckb-mm-mp-links-tuning-content-style',
            'inputs' => array(
                '0' => $form->dropdown( $feature_specs['width'] + array(
                        'value' => $kb_config['width'],
                        'current' => $kb_config['width'],
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-3',
                        'label_class' => 'config-col-5',
                        'input_class' => 'config-col-4'
                    ) ),
                '1' => $form->radio_buttons_horizontal( $feature_specs['nof_columns'] + array(
                        'id'        => 'front-end-columns',
                        'value'     => $kb_config['nof_columns'],
                        'current'   => $kb_config['nof_columns'],
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-5',
                        'input_class'       => 'config-col-6',
                        'radio_class'       => 'config-col-3'
                    ) ),
                '2' => $form->dropdown( $feature_specs['section_font_size'] + array(
                        'value' => $kb_config['section_font_size'],
                        'current' => $kb_config['section_font_size'],
                        'input_group_class' => 'config-col-12',
                        'label_class' => 'config-col-5',
                        'input_class' => 'config-col-4'
                    ) )
        )));

        // LIST OF ARTICLES - Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'nof_articles_displayed', 'expand_articles_icon', 'section_body_height', 'section_box_height_mode' ),
            'option-heading' => 'List of Articles - Style',
            'class'        => 'eckb-mm-mp-links-tuning-listofarticles-style',
            'inputs' => array(
                '0' => $form->text( $feature_specs['nof_articles_displayed'] + array(
                        'value' => $kb_config['nof_articles_displayed'],
                        'input_group_class' => 'config-col-12',
                        'label_class' => 'config-col-5',
                        'input_class' => 'config-col-2'
                    ) ),
                '1' => $form->dropdown( $feature_specs['expand_articles_icon'] + array(
                        'value' => $kb_config['expand_articles_icon'],
                        'current' => $kb_config['expand_articles_icon'],
                        'input_group_class' => 'config-col-12',
                        'label_class' => 'config-col-5',
                        'input_class' => 'config-col-4'
                    ) ),
                '2' => $form->text_and_select_fields_horizontal( array(
                        'id'                => 'list_height',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-5',
                        'label'             => 'Articles List Height',
                        'input_class'       => 'config-col-6',
                        'info'              => 'List of articles can have one of the following heights: unrestricted height ("Not Fixed"), at least # px ("Minimum") or at most # px ("Maximum")'
                    ), $arg1, $arg2 )
            )
        ));

        // LIST OF ARTICLES - Advanced Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'article_list_margin', 'article_list_spacing', 'section_article_underline', 'section_body_padding_top', 'section_body_padding_bottom', 'section_body_padding_left', 'section_body_padding_right'),
            'option-heading' => 'List of Articles - Advanced Style',
            'class'        => 'eckb-mm-mp-links-tuning-listofarticles-advanced',
            'inputs' => array(

	            '0' => $form->checkbox( $feature_specs['section_article_underline'] + array(
			            'value'             => $kb_config['section_article_underline'],
			            'id'                => 'section_article_underline',
			            'input_group_class' => 'config-col-12',
			            'label_class'       => 'config-col-5',
			            'input_class'       => 'config-col-2'
		            ) ),
	            '1' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'article_list_group',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Article Spacing ( px )'
		            ),
		            array( $article_spacing_arg1, $article_spacing_arg2 )
	            ),
	            '2' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'section_body_padding',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Section Padding( px )'
		            ),
		            array( $arg1_section_body_padding_vertical, $arg2_section_body_padding_vertical, $arg1_section_body_padding_horizontal, $arg2_section_body_padding_horizontal)
	            ),
            )
        ));

        // CATEGORIES - Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'section_head_alignment', 'section_divider', 'section_divider_thickness' ),
            'option-heading' => 'Categories - Style',
            'class'        => 'eckb-mm-mp-links-tuning-categories-style',
            'inputs' => array(
                    '0' => $form->dropdown( $feature_specs['section_head_alignment'] + array(
                        'value' => $kb_config['section_head_alignment'],
                        'current' => $kb_config['section_head_alignment'],
                        'input_group_class' => 'config-col-12',
                        'label_class'       => 'config-col-5',
                        'input_class'       => 'config-col-3'
                        ) ),
                    '1' => $form->checkbox( $feature_specs['section_divider'] + array(
                        'value'             => $kb_config['section_divider'],
                        'input_group_class' => 'config-col-12',
                        'label_class'       => 'config-col-5',
                        'input_class'       => 'config-col-2'
                        ) ),
                    '2' => $form->text( $feature_specs['section_divider_thickness'] + array(
                        'value'             => $kb_config['section_divider_thickness'],
                        'input_group_class' => 'config-col-12',
                        'label_class'       => 'config-col-5',
                        'input_class'       => 'config-col-2'
                    ) ),
                    '3' => $form->checkbox( $feature_specs['section_desc_text_on'] + array(
                            'value'             => $kb_config['section_desc_text_on'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-5',
                            'input_class'       => 'config-col-2'
                        ) ),
            )
        ));

        // CATEGORIES - Advanced Style
        $form->option_group( $feature_specs, array(
            'info' => array( 'section_box_shadow', 'section_border_radius', 'section_border_width', 'section_head_padding_top', 'section_head_padding_bottom', 'section_head_padding_left', 'section_head_padding_right' ),
            'option-heading' => 'Categories - Advanced Style',
            'class'        => 'eckb-mm-mp-links-tuning-categories-advanced',
            'inputs' => array(
	            '0' => $form->dropdown( $feature_specs['section_box_shadow'] + array(
			            'value'             => $kb_config['section_box_shadow'],
			            'current'           => $kb_config['section_box_shadow'],
			            'input_group_class' => 'config-col-12',
			            'label_class'       => 'config-col-5',
			            'input_class'       => 'config-col-6'
		            ) ),
	            '1' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'section_head_padding',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Section Head Padding( px )'
		            ),
		            array( $arg1_section_head_padding_vertical, $arg2_section_head_padding_vertical, $arg1_section_head_padding_horizontal, $arg2_section_head_padding_horizontal  )
	            ),

	            '2' => $form->multiple_number_inputs(
		            array(
			            'id'                => 'box_border',
			            'input_group_class' => '',
			            'main_label_class'  => '',
			            'input_class'       => '',
			            'label'             => 'Border Box( px )'
		            ),
		            array(  $arg1_box_border, $arg2_box_border  )
	            ),
            )
        ));

        return $kb_page_layout;
    }

    /**
     * Return HTML for settings controlling the Layout colors
     *
     * @param $kb_page_layout
     * @param $kb_config
     * @return String $kb_main_page_layout
     */
    public static function get_kb_config_colors( $kb_page_layout, $kb_config ) {

        if ( $kb_page_layout != self::LAYOUT_NAME ) {
            return $kb_page_layout;
        }

        $feature_specs = EPKB_KB_Config_Specs::get_fields_specification( $kb_config['id'] );
        $form = new EPKB_KB_Config_Elements();

        $arg1_input_text_field = $feature_specs['search_text_input_background_color'] + array( 'value' => $kb_config['search_text_input_background_color'], 'current' => $kb_config['search_text_input_background_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_input_text_field = $feature_specs['search_text_input_border_color'] + array( 'value' => $kb_config['search_text_input_border_color'], 'current' => $kb_config['search_text_input_border_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg1_button = $feature_specs['search_btn_background_color'] + array( 'value' => $kb_config['search_btn_background_color'], 'current' => $kb_config['search_btn_background_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_button = $feature_specs['search_btn_border_color'] + array( 'value' => $kb_config['search_btn_border_color'], 'current' => $kb_config['search_btn_border_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );

        $arg1_category_box_heading = $feature_specs['section_head_font_color'] + array( 'value' => $kb_config['section_head_font_color'], 'current' => $kb_config['section_head_font_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_category_box_heading = $feature_specs['section_head_background_color'] + array( 'value' => $kb_config['section_head_background_color'], 'current' => $kb_config['section_head_background_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );

        $arg1_sub_category = $feature_specs['section_category_font_color'] + array( 'value' => $kb_config['section_category_font_color'], 'current' => $kb_config['section_category_font_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_sub_category = $feature_specs['section_category_icon_color'] + array( 'value' => $kb_config['section_category_icon_color'], 'current' => $kb_config['section_category_icon_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );

        $arg1_article_list = $feature_specs['section_body_background_color'] + array( 'value' => $kb_config['section_body_background_color'], 'current' => $kb_config['section_body_background_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_article_list = $feature_specs['section_border_color'] + array( 'value' => $kb_config['section_border_color'], 'current' => $kb_config['section_border_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );

        $arg1_articles = $feature_specs['article_font_color'] + array( 'value' => $kb_config['article_font_color'], 'current' => $kb_config['article_font_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );
        $arg2_articles = $feature_specs['article_icon_color'] + array( 'value' => $kb_config['article_icon_color'], 'current' => $kb_config['article_icon_color'], 'class' => 'ekb-color-picker', 'text_class' => 'config-col-6' );



        // SEARCH BOX - Colors
        $form->option_group( $feature_specs, array(
            'info'              => array( 'search_title_font_color', 'search_background_color', 'search_text_input_background_color', 'search_text_input_border_color', 'search_btn_background_color',
                                          'search_btn_border_color'),
            'option-heading'    => 'Search Box - Colors',
            'class'             => 'eckb-mm-mp-links-tuning-searchbox-colors',
            'inputs' => array(
                '0' => $form->text( $feature_specs['search_title_font_color'] + array(
                        'value'             => $kb_config['search_title_font_color'],
                        'input_group_class' => 'config-col-12',
                        'class'             => 'ekb-color-picker',
                        'label_class'       => 'config-col-4',
                        'input_class'       => 'config-col-8 ekb-color-picker'
                    ) ),
                '1' => $form->text( $feature_specs['search_background_color'] + array(
                        'value' => $kb_config['search_background_color'],
                        'input_group_class' => 'config-col-12',
                        'class'             => 'ekb-color-picker',
                        'label_class'       => 'config-col-4',
                        'input_class'       => 'config-col-8 ekb-color-picker'
                    ) ),
                '2' => $form->text_fields_horizontal( array(
                        'id'                => 'input_text_field',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Input Text Field'
                ), $arg1_input_text_field, $arg2_input_text_field ),
                '3' => $form->text_fields_horizontal( array(
                        'id'                => 'button',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Button'
                ), $arg1_button, $arg2_button ) )
        ));

        // CONTENT - Colors
        $form->option_group( $feature_specs, array(
            'info'              => array( 'background_color' ),
            'option-heading'    => 'Content - Colors',
            'class'             => 'eckb-mm-mp-links-tuning-content-colors',
            'inputs'            => array(
                '0' => $form->text( $feature_specs['background_color'] + array(
                        'value' => $kb_config['background_color'],
                        'input_group_class' => 'config-col-12',
                        'class'             => 'ekb-color-picker',
                        'label_class'       => 'config-col-4',
                        'input_class'       => 'config-col-8 ekb-color-picker'
                    ) ) )
        ));

        // LIST OF ARTICLES - Colors
        $form->option_group( $feature_specs, array(
            'info'              => array( 'section_body_background_color', 'section_border_color', 'article_font_color', 'article_icon_color'),
            'option-heading'    => 'List of Articles - Colors',
            'class'             => 'eckb-mm-mp-links-tuning-listofarticles-colors',
            'inputs'            => array(
                '0' => $form->text_fields_horizontal( array(
                        'id'                => 'article_list',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Article List'
                ), $arg1_article_list, $arg2_article_list ),
                '1' => $form->text_fields_horizontal( array(
                        'id'                => 'articles',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Articles'
                ), $arg1_articles, $arg2_articles )
            )
        ));

        // CATEGORIES - Colors
        $form->option_group( $feature_specs, array(
            'info'              => array( 'section_category_font_color', 'section_category_icon_color', 'section_divider_color', 'section_head_font_color', 'section_head_background_color', 'section_head_description_font_color' ),
            'option-heading'    => 'Categories - Colors',
            'class'             => 'eckb-mm-mp-links-tuning-categories-colors',
            'inputs'            => array(
                '0' => $form->text_fields_horizontal( array(
                        'id'                => 'sub_category',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Sub-category'
                ), $arg1_sub_category, $arg2_sub_category ),
                '1' => $form->text( $feature_specs['section_divider_color'] + array(
                        'value' => $kb_config['section_divider_color'],
                        'class'             => 'ekb-color-picker',
                        'input_group_class' => 'config-col-12',
                        'label_class'       => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker'
                    ) ),
                '2' => $form->text_fields_horizontal( array(
                        'id'                => 'category_box_heading',
                        'input_group_class' => 'config-col-12',
                        'main_label_class'  => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker',
                        'label'             => 'Category Box Heading'
                    ), $arg1_category_box_heading, $arg2_category_box_heading ),
                '3' => $form->text( $feature_specs['section_head_description_font_color'] + array(
                        'value'             => $kb_config['section_head_description_font_color'],
                        'class'             => 'ekb-color-picker',
                        'input_group_class' => 'config-col-12',
                        'label_class'       => 'config-col-4',
                        'input_class'       => 'config-col-7 ekb-color-picker'
                    ) )
            )
        ));
        
        return $kb_page_layout;
    }

    /**
     * Return HTML for settings controlling the Layout Text
     *
     * @param $kb_page_layout
     * @param $kb_config
     * @return String $kb_page_layout
     */
    public static function get_kb_config_text( $kb_page_layout, $kb_config ) {

        if ( $kb_page_layout != self::LAYOUT_NAME ) {
            return $kb_page_layout;
        }

        $feature_specs = EPKB_KB_Config_Specs::get_fields_specification( $kb_config['id'] );
        $form = new EPKB_KB_Config_Elements();

        $form->option_group( $feature_specs, array(
            'info' => array('search_title', 'search_box_hint', 'search_button_name', 'search_results_msg', 'no_results_found', 'min_search_word_size_msg'),
            'option-heading' => 'Search Box - Text',
            'class'        => 'eckb-mm-mp-links-alltext-text-searchbox eckb-mm-mp-links-tuning-searchbox-text',
            'inputs' => array(
                '0' => $form->text( $feature_specs['search_title'] +
                    array( 'value' => $kb_config['search_title'], 'current' => $kb_config['search_title'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'   ) ),
                '1' => $form->text( $feature_specs['search_box_hint'] +
                    array( 'value' => $kb_config['search_box_hint'], 'current' => $kb_config['search_box_hint'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'   ) ),
                '2' => $form->text( $feature_specs['search_button_name'] +
                    array( 'value' => $kb_config['search_button_name'], 'current' => $kb_config['search_button_name'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '3' => $form->text( $feature_specs['search_results_msg'] +
                    array( 'value' => $kb_config['search_results_msg'], 'current' => $kb_config['search_results_msg'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '4' => $form->text( $feature_specs['no_results_found'] +
                    array( 'value' => $kb_config['no_results_found'], 'current' => $kb_config['no_results_found'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'   ) ),
                '5' => $form->text( $feature_specs['min_search_word_size_msg'] +
                    array( 'value' => $kb_config['min_search_word_size_msg'], 'current' => $kb_config['min_search_word_size_msg'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'   ) )
            )
        ));

        $form->option_group( $feature_specs, array(
            'info' => array('category_empty_msg', 'non_existent_category_name', 'default_category_name'),
            'option-heading'    => 'Categories - Text',
            'class'             => 'eckb-mm-mp-links-alltext-text-categories eckb-mm-mp-links-tuning-categories-text',
            'inputs' => array(
                '1' => $form->text( $feature_specs['category_empty_msg'] +
                    array( 'value' => $kb_config['category_empty_msg'], 'current' => $kb_config['category_empty_msg'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '2' => $form->text( $feature_specs['non_existent_category_name'] +
                    array( 'value' => $kb_config['non_existent_category_name'], 'current' => $kb_config['non_existent_category_name'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '3' => $form->text( $feature_specs['default_category_name'] +
                    array( 'value' => $kb_config['default_category_name'], 'current' => $kb_config['default_category_name'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) )
            )
        ));
        
        $form->option_group( $feature_specs, array(
            'info'  => array('collapse_articles_msg', 'show_all_articles_msg', 'sample_article_title', 'empty_article_content'),
            'option-heading'    => 'Articles - Text',
            'class'             => 'eckb-mm-mp-links-alltext-text-articles eckb-mm-mp-links-tuning-listofarticles-text',
            'inputs' => array(
                '1' => $form->text( $feature_specs['collapse_articles_msg'] +
                    array( 'value' => $kb_config['collapse_articles_msg'], 'current' => $kb_config['collapse_articles_msg'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '2' => $form->text( $feature_specs['show_all_articles_msg']
                    + array( 'value' => $kb_config['show_all_articles_msg'], 'current' => $kb_config['show_all_articles_msg'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '3' => $form->text( $feature_specs['sample_article_title'] +
                    array( 'value' => $kb_config['sample_article_title'], 'current' => $kb_config['sample_article_title'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
                '4' => $form->text( $feature_specs['empty_article_content'] +
                    array( 'value' => $kb_config['empty_article_content'], 'current' => $kb_config['empty_article_content'],
                            'input_group_class' => 'config-col-12',
                            'label_class'       => 'config-col-3',
                            'input_class'       => 'config-col-9'       ) ),
            )
        ));

        return $kb_page_layout;
    }

    /**
     * Return colors set based on selected layout and colors
     *
     * @param $colors_set
     * @param $layout_name
     * @param $set_name
     *
     * @return array
     */
    public static function get_colors_set( $colors_set, $layout_name, $set_name ) {

        if ( $layout_name != self::LAYOUT_NAME ) {
            return $colors_set;
        }

        switch( $set_name ) {
            case EPKB_KB_Config_Layouts::KB_DEFAULT_COLORS_STYLE:
                return self::colors_set_1(
                    array(
                        'base_color'            => '#827a74',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'black-white2':
                return self::colors_set_2(
                    array(
                        'base_color'            => '#827a74',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'black-white3':
                return self::colors_set_3(
                    array(
                        'base_color'            => '#827a74',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'black-white4':
                return self::colors_set_4(
                    array(
                        'base_color'            => '#827a74',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#5b5551',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'blue1':
                return self::colors_set_1(
                    array(
                        'base_color'            => '#4F92CA',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'blue2':
                return self::colors_set_2(
                    array(
                        'base_color'            => '#4F92CA',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'blue3':
                return self::colors_set_3(
                    array(
                        'base_color'            => '#4F92CA',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'blue4':
                return self::colors_set_4(
                    array(
                        'base_color'            => '#4F92CA',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#2f5779',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'green1':
                return self::colors_set_1(
                    array(
                        'base_color'            => '#00cc99',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'green2':
                return self::colors_set_2(
                    array(
                        'base_color'            => '#00cc99',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'green3':
                return self::colors_set_3(
                    array(
                        'base_color'            => '#00cc99',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'green4':
                return self::colors_set_4(
                    array(
                        'base_color'            => '#00cc99',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#008e6b',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'red1':
                return self::colors_set_1(
                    array(
                        'base_color'            => '#CC0000',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'red2':
                return self::colors_set_2(
                    array(
                        'base_color'            => '#CC0000',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'red3':
                return self::colors_set_3(
                    array(
                        'base_color'            => '#CC0000',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            case 'red4':
                return self::colors_set_4(
                    array(
                        'base_color'            => '#CC0000',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#8e0000',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
            default:
                return self::colors_set_1(
                    array(
                        'base_color'            => '#827a74',
                        'text_color_dark'       => '#868686',
                        'text_color_light'      => '#b3b3b3',
                        'bg_color_dark'         => '#686868',
                        'bg_color_light'        => '#FFFFFF',
                        'border_color_dark'     => '#CCCCCC',
                        'border_color_light'    => '#F1F1F1'
                    )
                );
                break;
        }
    }

    private static function colors_set_1( $args = array() ) {

        //Color Variables
        $args = self::add_defaults_color_sets( $args );

        return array(
            //General
            'background_color'                      =>  $args['bg_color_light'],

            //Search Box
            'search_title_font_color'               =>  $args['search_title_font_color'],
            'search_background_color'               =>  $args['base_color'],
            'search_text_input_background_color'    =>  $args['bg_color_light'],
            'search_text_input_border_color'        =>  $args['border_color_light'],
            'search_btn_background_color'           =>  $args['bg_color_dark'],
            'search_btn_border_color'               =>  $args['border_color_light'],

            //Articles Listed In Category Box
            'section_head_font_color'               =>  $args['base_color'],
            'section_head_background_color'         =>  $args['bg_color_light'],
            'section_head_description_font_color'   =>  $args['text_color_light'],
            'section_body_background_color'         =>  $args['bg_color_light'],
            'section_border_color'                  =>  $args['border_color_dark'],
            'section_divider_color'                 =>  $args['border_color_dark'],
            'section_category_font_color'           =>  $args['text_color_dark'],
            'section_category_icon_color'           =>  $args['text_color_dark'],
            'article_font_color'                    =>  $args['text_color_light'],
            'article_icon_color'                    =>  $args['text_color_light']
        );
    }//colors_set_1

    private static function colors_set_2( $args = array() ) {

        //Color Variables
        $args = self::add_defaults_color_sets( $args );

        return array(
            //General
            'background_color'                      =>  $args['bg_color_light'],

            //Search Box
            'search_title_font_color'               =>  $args['search_title_font_color'],
            'search_background_color'               =>  $args['base_color'],
            'search_text_input_background_color'    =>  $args['bg_color_light'],
            'search_text_input_border_color'        =>  $args['border_color_light'],
            'search_btn_background_color'           =>  $args['bg_color_dark'],
            'search_btn_border_color'               =>  $args['border_color_light'],

            //Articles Listed In Category Box
            'section_head_font_color'               =>  $args['text_color_dark'],
            'section_head_background_color'         =>  $args['bg_color_light'],
            'section_head_description_font_color'   =>  $args['base_color'],
            'section_body_background_color'         =>  $args['bg_color_light'],
            'section_border_color'                  =>  $args['border_color_dark'],
            'section_divider_color'                 =>  $args['border_color_dark'],
            'section_category_font_color'           =>  $args['text_color_dark'],
            'section_category_icon_color'           =>  $args['base_color'],
            'article_font_color'                    =>  $args['base_color'],
            'article_icon_color'                    =>  $args['base_color']
        );
    }//colors_set_2

    private static function colors_set_3( $args = array() ) {

        //Color Variables
        $args = self::add_defaults_color_sets( $args );

        return array(
            //General
            'background_color'                      =>  $args['bg_color_light'],

            //Search Box
            'search_title_font_color'               =>  $args['search_title_font_color'],
            'search_background_color'               =>  $args['base_color'],
            'search_text_input_background_color'    =>  $args['bg_color_light'],
            'search_text_input_border_color'        =>  $args['border_color_light'],
            'search_btn_background_color'           =>  $args['bg_color_dark'],
            'search_btn_border_color'               =>  $args['border_color_light'],

            //Articles Listed In Category Box
            'section_head_font_color'               =>  $args['base_color'],
            'section_head_background_color'         =>  $args['bg_color_light'],
            'section_head_description_font_color'   =>  $args['text_color_light'],
            'section_body_background_color'         =>  $args['bg_color_light'],
            'section_border_color'                  =>  $args['border_color_dark'],
            'section_divider_color'                 =>  $args['base_color'],
            'section_category_font_color'           =>  $args['base_color'],
            'section_category_icon_color'           =>  $args['base_color'],
            'article_font_color'                    =>  $args['text_color_dark'],
            'article_icon_color'                    =>  $args['text_color_dark']
        );
    }//colors_set_3

    private static function colors_set_4( $args = array() ) {

        //Color Variables
        $args = self::add_defaults_color_sets( $args );

        return array(
            //General
            'background_color'                      =>  $args['bg_color_light'],

            //Search Box
            'search_title_font_color'               =>  $args['search_title_font_color'],
            'search_background_color'               =>  $args['base_color'],
            'search_text_input_background_color'    =>  $args['bg_color_light'],
            'search_text_input_border_color'        =>  $args['border_color_light'],
            'search_btn_background_color'           =>  $args['bg_color_dark'],
            'search_btn_border_color'               =>  $args['border_color_light'],

            //Articles Listed In Category Box
            'section_head_font_color'               =>  '#ffffff',
            'section_head_background_color'         =>  $args['base_color'],
            'section_head_description_font_color'   =>  '#ffffff',
            'section_body_background_color'         =>  $args['bg_color_light'],
            'section_border_color'                  =>  $args['bg_color_dark'],
            'section_divider_color'                 =>  $args['bg_color_dark'],
            'section_category_font_color'           =>  $args['base_color'],
            'section_category_icon_color'           =>  $args['base_color'],
            'article_font_color'                    =>  $args['text_color_dark'],
            'article_icon_color'                    =>  $args['base_color']
        );
    }//colors_set_4

    // Color set used as default backup values
    private static function add_defaults_color_sets( array $input_array ) {

        $defaults = array(
            'search_title_font_color'   => 'ffffff',
            'base_color'                => '000000',
            'text_color_dark'           => '000000',
            'text_color_light'          => '000000',
            'bg_color_dark'             => '000000',
            'bg_color_light'            => '000000',
            'border_color_dark'         => '000000',
            'border_color_light'        => '000000',
            'section_head_background_color'  => '000000',
        );
        return array_merge( $defaults, $input_array );
    }

    /**
     * Return Style set based on selected layout
     *
     * @param $style_set
     * @param $layout_name
     * @param $set_name
     *
     * @return array
     */
    public static function get_style_set( $style_set, $layout_name, $set_name ) {

        if ( $layout_name != self::LAYOUT_NAME ) {
            return $style_set;
        }

        switch( $set_name) {
            case self::LAYOUT_STYLE_2:
                return self::get_style_2_set();
                break;
            case self::LAYOUT_STYLE_3:
                return self::get_style_3_set();
                break;
            case self::LAYOUT_STYLE_1:
            default:
                return self::get_style_1_set();
                break;
        }
    }

    private static function get_style_1_set() {
        return array(
            //General
            'width'                         =>  'epkb-boxed',
            'section_font_size'             =>  'section_medium_font',
            'nof_articles_displayed'        =>  8,
            'expand_articles_icon'          =>  'ep_icon_arrow_carrot_right',
            'section_body_height'           =>  350,
            'section_box_height_mode'       =>  'section_no_height',

            'nof_columns'                   =>  'three-col',

            //Search Box
            'search_layout'                 =>  'epkb-search-form-1',
            'search_input_border_width'     =>  1,

            //Advanced Configuration

            // - Section
            'section_box_shadow'            =>  'no_shadow',
            'section_border_width'          =>  '0',
            'section_border_radius'         =>  '4',

            // - Section Head
            'section_head_alignment'        =>  'left',
            'section_divider'               =>  'on',
            'section_divider_thickness'     =>  1,
            'section_head_padding_top'      =>  10,
            'section_head_padding_bottom'   =>  10,
            'section_head_padding_left'     =>  10,
            'section_head_padding_right'    =>  0,

            // - Section Body
            'article_list_margin'           =>  10,
            'article_list_spacing'          =>  8,
            'section_article_underline'     =>  'on',
            'section_body_padding_top'      =>  4,
            'section_body_padding_bottom'   =>  4,
            'section_body_padding_left'     =>  4,
            'section_body_padding_right'    =>  4,

	        //Features
	   /*     'back_navigation_toggle'         => 'on',
	        'back_navigation_mode'           => 'navigate_browser_back',
	        'back_navigation_text_color'     => '#666666',
	        'back_navigation_bg_color'       => '#ffffff',
	        'back_navigation_border_color'   => '#dcdcdc',
	        'back_navigation_font_size'      => '16',
	        'back_navigation_border'         => 'solid',
	        'back_navigation_border_radius'  => '3',
	        'back_navigation_border_width'   => '1',
	        'back_navigation_margin_top'     => '4',
	        'back_navigation_margin_bottom'  => '4',
	        'back_navigation_margin_left'    => '4',
	        'back_navigation_margin_right'   => '4',
	        'back_navigation_padding_top'    => '4',
	        'back_navigation_padding_bottom' => '4',
	        'back_navigation_padding_left'   => '4',
	        'back_navigation_padding_right'  => '4', */
        );
    }

    private static function get_style_2_set() {
        return array(
            //General
            'width'                         =>  'epkb-boxed',
            'section_font_size'             =>  'section_medium_font',
            'nof_articles_displayed'        =>  8,
            'expand_articles_icon'          =>  'ep_icon_arrow_carrot_right',
            'section_body_height'           =>  350,
            'section_box_height_mode'       =>  'section_no_height',

            'nof_columns'                   =>  'three-col',

            //Search Box
            'search_layout'                 =>  'epkb-search-form-1',
            'search_input_border_width'     =>  1,

            //Advanced Configuration

            // - Section
            'section_box_shadow'            =>  'no_shadow',
            'section_border_width'          =>  '1',
            'section_border_radius'         =>  '4',

            // - Section Head
            'section_head_alignment'        =>  'center',
            'section_divider'               =>  'on',
            'section_divider_thickness'     =>  1,
            'section_head_padding_top'      =>  10,
            'section_head_padding_bottom'   =>  10,
            'section_head_padding_left'     =>  4,
            'section_head_padding_right'    =>  4,

            // - Section Body
            'article_list_margin'           =>  10,
            'article_list_spacing'          =>  8,
            'section_article_underline'     =>  'on',
            'section_body_padding_top'      =>  4,
            'section_body_padding_bottom'   =>  4,
            'section_body_padding_left'     =>  4,
            'section_body_padding_right'    =>  4,

	        //Features
	       /* 'back_navigation_toggle'         => 'on',
	        'back_navigation_mode'           => 'navigate_browser_back',
	        'back_navigation_text_color'     => '#666666',
	        'back_navigation_bg_color'       => '#ffffff',
	        'back_navigation_border_color'   => '#dcdcdc',
	        'back_navigation_font_size'      => '16',
	        'back_navigation_border'         => 'solid',
	        'back_navigation_border_radius'  => '3',
	        'back_navigation_border_width'   => '1',
	        'back_navigation_margin_top'     => '4',
	        'back_navigation_margin_bottom'  => '4',
	        'back_navigation_margin_left'    => '4',
	        'back_navigation_margin_right'   => '4',
	        'back_navigation_padding_top'    => '4',
	        'back_navigation_padding_bottom' => '4',
	        'back_navigation_padding_left'   => '4',
	        'back_navigation_padding_right'  => '4', */
        );
    }
    
    //Not used
    private static function get_style_3_set() {
        return array(
            //Articles Listed In Category Box
            'section_border_width'          => '1',

	        //Features
	       /* 'back_navigation_toggle'         => 'on',
	        'back_navigation_mode'           => 'navigate_browser_back',
	        'back_navigation_text_color'     => '#666666',
	        'back_navigation_bg_color'       => '#ffffff',
	        'back_navigation_border_color'   => '#dcdcdc',
	        'back_navigation_font_size'      => '16',
	        'back_navigation_border'         => 'solid',
	        'back_navigation_border_radius'  => '3',
	        'back_navigation_border_width'   => '1',
	        'back_navigation_margin_top'     => '4',
	        'back_navigation_margin_bottom'  => '4',
	        'back_navigation_margin_left'    => '4',
	        'back_navigation_margin_right'   => '4',
	        'back_navigation_padding_top'    => '4',
	        'back_navigation_padding_bottom' => '4',
	        'back_navigation_padding_left'   => '4',
	        'back_navigation_padding_right'  => '4', */
        );
    }

    /**
     * Return search box Style set based on selected layout
     *
     * @param $style_set
     * @param $layout_name
     * @param $set_name
     *
     * @return array
     */
    public static function get_search_box_style_set( $style_set, $layout_name, $set_name ) {

        if ( $layout_name != self::LAYOUT_NAME ) {
            return $style_set;
        }

        switch( $set_name) {
            case self::SEARCH_BOX_LAYOUT_STYLE_2:
                return self::get_search_box_style_2_set();
                break;
            case self::SEARCH_BOX_LAYOUT_STYLE_3:
                return self::get_search_box_style_3_set();
                break;
            case self::SEARCH_BOX_LAYOUT_STYLE_4:
                return self::get_search_box_style_4_set();
                break;
            case self::SEARCH_BOX_LAYOUT_STYLE_1:
            default:
                return self::get_search_box_style_1_set();
                break;
        }
    }

    private static function get_search_box_style_1_set() {
        return array(

            //Layout
            'search_layout'                 =>  'epkb-search-form-1',
            //Padding
            'search_box_padding_top'        =>  40,
            'search_box_padding_bottom'     =>  40,
            'search_box_padding_left'       =>  0,
            'search_box_padding_right'      =>  0,
            //Margin
            'search_box_margin_top'         =>  40,
            'search_box_margin_bottom'      =>  40,
            'search_box_margin_left'        =>  0,
            'search_box_margin_right'       =>  0,
            //Search Input Width
            'search_box_input_width'        =>  80,

            //Search Input Border Width
            'search_input_border_width'     =>  1

        );
    }

    private static function get_search_box_style_2_set() {
        return array(
            //Layout
            'search_layout'                 =>  'epkb-search-form-1',
            //Padding
            'search_box_padding_top'        =>  40,
            'search_box_padding_bottom'     =>  40,
            'search_box_padding_left'       =>  0,
            'search_box_padding_right'      =>  0,
            //Margin
            'search_box_margin_top'         =>  40,
            'search_box_margin_bottom'      =>  40,
            'search_box_margin_left'        =>  0,
            'search_box_margin_right'       =>  0,
            //Search Input Width
            'search_box_input_width'        =>  80,

            //Search Input Border Width
            'search_input_border_width'     =>  1
        );
    }

    private static function get_search_box_style_3_set() {
        return array(
            //Layout
            'search_layout'                 =>  'epkb-search-form-1',
            //Padding
            'search_box_padding_top'        =>  40,
            'search_box_padding_bottom'     =>  40,
            'search_box_padding_left'       =>  0,
            'search_box_padding_right'      =>  0,
            //Margin
            'search_box_margin_top'         =>  40,
            'search_box_margin_bottom'      =>  40,
            'search_box_margin_left'        =>  0,
            'search_box_margin_right'       =>  0,
            //Search Input Width
            'search_box_input_width'        =>  80,

            //Search Input Border Width
            'search_input_border_width'     =>  1
        );
    }

    private static function get_search_box_style_4_set() {
        return array(
            //Layout
            'search_layout'                 =>  'epkb-search-form-1',
            //Padding
            'search_box_padding_top'        =>  40,
            'search_box_padding_bottom'     =>  40,
            'search_box_padding_left'       =>  0,
            'search_box_padding_right'      =>  0,
            //Margin
            'search_box_margin_top'         =>  40,
            'search_box_margin_bottom'      =>  40,
            'search_box_margin_left'        =>  0,
            'search_box_margin_right'       =>  0,
            //Search Input Width
            'search_box_input_width'        =>  80,

            //Search Input Border Width
            'search_input_border_width'     =>  1
        );
    }
}
