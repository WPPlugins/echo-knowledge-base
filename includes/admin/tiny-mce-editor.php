<?php  if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Adds our button and JavaScript to allow user to quickly and easily insert shortcode with all options configured.
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 */

/**
 * If on admin page then add scripts
 */
function epkb_add_editor_buttons() {
	// Hook into the TinyMCE WYSIWYG
	if ( current_user_can( 'edit_posts' ) &&  current_user_can( 'edit_pages' ) ) 	{
		add_filter( 'mce_external_plugins', 'epkb_add_buttons' );
		add_filter( 'mce_buttons', 'epkb_register_buttons' );
	}
}
add_action('admin_init', 'epkb_add_editor_buttons');

/**
 * This method will run on the mce_external_plugins filter,
 * and we will use this to add the Javascript file we need to add our shortcode content in the editor
 *
 * @param   array $plugin_array contains JS files
 * @uses    plugin_dir_url()       Gets the plugin directory
 * @return  array                Returns an array of linked files
 */
function epkb_add_buttons( $plugin_array ) {

	// only if we have Multiple Knowledge Base add-on then ask for KB shortcode ID
	if ( defined( 'EKB_MKB_PLUGIN_NAME' )  ) {
		$plugin_array['epkb_shortcodes'] = Echo_Knowledge_Base::$plugin_url . 'js/shortcode-tinymce-button-dialog.js';
	} else {
		$plugin_array['epkb_shortcodes'] = Echo_Knowledge_Base::$plugin_url . 'js/shortcode-tinymce-button.js';
	}

	return $plugin_array;
}

/**
 * The register buttons method is going to run on the mce_buttons filter,
 * this method has a parameter which is an array of buttons that are being applied to the editor.
 *
 * @param     array $buttons that will show in the editor toolbar
 * @uses      array_push()      Push one or more elements onto the end of array
 * @return    array             Returns an array of added elements
 */
function epkb_register_buttons( $buttons ) {
	array_push( $buttons, 'separator', 'epkb_shortcodes' );
	return $buttons;
}
