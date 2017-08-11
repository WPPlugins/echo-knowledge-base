<?php

// Exit if accessed directly
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit;


/**
 * Uninstall this plugin
 *
 */


flush_rewrite_rules(false);

/** Delete plugin options */
// TODO FUTURE have option to delete settings
//delete_option( 'epkb_config_' );
//delete_option( 'epkb_settings' );
delete_option( 'epkb_show_welcome_header' );
delete_option( 'epkb_version' );
delete_option( 'epkb_error_log' );
delete_option( 'epkb_flush_rewrite_rules' );
