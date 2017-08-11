<?php

/**
 * Setup links and information on Plugins WordPress page
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */


/**
 * Adds various links for plugin on the Plugins page displayed on the left
 *
 * @param   array $links contains current links for this plugin
 * @return  array returns an array of links
 */
function epkb_add_plugin_action_links ( $links ) {
	$my_links = array(
		'Configuration'  => '<a href="' . admin_url('edit.php?post_type=epkb_post_type_1&page=epkb-kb-configuration') . '">' . esc_html__( 'Configuration', 'echo-knowledge-base' ) . '</a>',
		'Docs'      => '<a href="http://www.echoknowledgebase.com/documentation/" target="_blank">' . esc_html__( 'Docs', 'echo-knowledge-base' ) . '</a>',
		'Support'   => '<a href="https://www.echoplugins.com/contact-us/?inquiry-type=technical&plugin_type=knowledge-base">' . esc_html__( 'Support', 'echo-knowledge-base' ) . '</a>'
	);

	return array_merge( $my_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename(Echo_Knowledge_Base::$plugin_file), 'epkb_add_plugin_action_links', 10, 2 );

/**
 * Add info about plugin on the Plugins page displayed on the right.
 *
 * @param $links
 * @param $file
 * @return array
 */
function epkb_add_plugin_row_meta($links, $file) {
	if ( $file != 'echo-knowledge-base/echo-knowledge-base.php' ) {
		return $links;
	}

	$links[] = '<a href="' . admin_url( 'index.php?page=epkb-welcome-page&tab=get-started' ) . '">' . esc_html__( 'Getting Started', 'echo-knowledge-base' ) . '</a>';
	$links[] = '<a href="' . admin_url( 'index.php?page=epkb-welcome-page' ) . '">' . esc_html__( "What's New", 'echo-knowledge-base' ) . '</a>';
	return $links;
}
add_filter( 'plugin_row_meta', 'epkb_add_plugin_row_meta', 10, 2 );
