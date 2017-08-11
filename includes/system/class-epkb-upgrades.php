<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Check if plugin upgrade to a new version requires any actions like database upgrade
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */
class EPKB_Upgrades {

	public function __construct() {
        // will run after plugin is updated but not always like front-end rendering
		add_action( 'admin_init', array( 'EPKB_Upgrades', 'update_plugin_version' ) );
        add_filter( 'eckb_plugin_upgrade_message', array( 'EPKB_Upgrades', 'display_upgrade_message' ) );
        add_action( 'eckb_remove_upgrade_message', array( 'EPKB_Upgrades', 'remove_upgrade_message' ) );
	}

    /**
     * If necessary run plugin database updates
     */
    public static function update_plugin_version() {

        $last_version = EPKB_Utilities::get_wp_option( 'epkb_version', null );

        // if plugin is up-to-date then return
        if ( ! empty($last_version) && version_compare( $last_version, Echo_Knowledge_Base::$version, '>=' ) ) {
            return;
        }

		// since we need to upgrade this plugin, on the Overview Page show an upgrade message
	    EPKB_Utilities::save_wp_option( 'epkb_show_upgrade_message', true, true );

        // upgrade the plugin
        self::invoke_upgrades( $last_version );

        // update the plugin version
        $result = EPKB_Utilities::save_wp_option( 'epkb_version', Echo_Knowledge_Base::$version, true );
        if ( is_wp_error( $result ) ) {
	        // TODO log it
            return;
        }

        flush_rewrite_rules( false );   // TODO remove when all on LAY 1.1.0
    }

    /**
     * Invoke each database update as necessary.
     *
     * @param $last_version
     */
    private static function invoke_upgrades( $last_version ) {

        // update all KBs
        $all_kb_configs = epkb_get_instance()->kb_config_ojb->get_kb_configs();
        foreach ( $all_kb_configs as $kb_config ) {

            if ( version_compare( $last_version, '3.0.0', '<' ) ) {
                self::upgrade_to_v210( $kb_config );
            }

            if ( version_compare( $last_version, '3.0.0', '<' ) ) {
                self::upgrade_to_v220( $kb_config );
            }

            // store the updated KB data
            epkb_get_instance()->kb_config_ojb->update_kb_configuration( $kb_config['id'], $kb_config );
        }
    }

    private static function upgrade_to_v210( &$kb_config ) {
        if ( isset($kb_config['expand_articles_icon']) && substr($kb_config['expand_articles_icon'], 0, strlen('ep_' )) !== 'ep_' ) {
            $kb_config['expand_articles_icon'] = str_replace( 'icon_plus-box', 'ep_icon_plus_box', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'icon_plus', 'ep_icon_plus', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'arrow_triangle-right', 'ep_icon_right_arrow', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'arrow_carrot-right_alt2', 'ep_icon_arrow_carrot_right_circle', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'arrow_carrot-right', 'ep_icon_arrow_carrot_right', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'icon_folder-add_alt', 'ep_icon_folder_add', $kb_config['expand_articles_icon'] );
            $kb_config['expand_articles_icon'] = str_replace( 'ep_ep_', 'ep_', $kb_config['expand_articles_icon'] );
        }
        if ( $kb_config['expand_articles_icon'] == 'ep_icon_arrow_carrot_right_alt2' ) {
            $kb_config['expand_articles_icon'] = 'ep_icon_arrow_carrot_right';
        }
    }

    private static function upgrade_to_v220( &$kb_config ) {
        if ( empty($kb_config['templates_for_kb']) ) {
            $kb_config['templates_for_kb'] = 'current_theme_templates';
        }

        if ( $kb_config['kb_main_page_layout'] == 'Sidebar' ) {
            $kb_config['kb_article_page_layout'] = 'Sidebar';
        }
    }

    /**
     * Show upgrade message on Overview Page.
     *
     * @param $output
     * @return string
     */
	public static function display_upgrade_message( $output ) {

		if ( EPKB_Utilities::get_wp_option( 'epkb_show_upgrade_message', false ) ) {
			$i18_what_is_new_link = '<a href="' . admin_url( 'index.php?page=epkb-welcome-page' ) . '" target="_blank">' .
			                        esc_html__( 'here', 'echo-knowledge-base' ) . '</a>';
			$plugin_name = '<strong>' . __('Knowledge Base', 'echo-knowledge-base') . '</strong>';
			$output .= '<p>' . $plugin_name . ' ' . sprintf( esc_html( _x( 'plugin was updated to version %s. Check out new features and improvements %s ',
									' version number, link to what is new page', 'echo-knowledge-base' ) ),
									Echo_Knowledge_Base::$version, $i18_what_is_new_link ) . '</p>';
		}

		return $output;
	}
    
    public static function remove_upgrade_message() {
        delete_option('epkb_show_upgrade_message');
    }
}
