<?php

/**
 * Handle saving feature settings.
 */
class EPKB_Settings_Controller {

	public function __construct() {
		add_action( 'wp_ajax_epkb_save_settings', array( $this, 'save_settings' ) );
		add_action( 'wp_ajax_nopriv_epkb_save_settings', array( $this, 'user_not_logged_in' ) );
		add_action( 'wp_ajax_epkb_send_feedback', array( $this, 'send_feedback' ) );
		add_action( 'wp_ajax_nopriv_epkb_send_feedback', array( $this, 'user_not_logged_in' ) );
		add_action( 'wp_ajax_epkb_close_welcome_header', array( $this, 'close_welcome_header' ) );
	}

	/**
	 * Triggered when user submits Save to plugin settings. Saves the updated settings into the database
	 */
	public function save_settings() {

		// verify that the request is authentic
		if ( ! isset( $_REQUEST['_wpnonce_epkb_save_settings'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce_epkb_save_settings'], '_wpnonce_epkb_save_settings' ) ) {
			EPKB_Utilities::ajax_show_error_die(__( 'Settings not saved. First refresh your page', 'echo-knowledge-base' ));
		}

		// ensure user has correct permissions
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			EPKB_Utilities::ajax_show_error_die(__( 'You do not have permission to edit this knowledge base', 'echo-knowledge-base' ));
		}

		// retrieve current plugin settings
		$orig_settings = epkb_get_instance()->settings_obj->get_settings();
		
		// retrieve user input
		$field_specs = EPKB_Settings_Specs::get_fields_specification();
		$form = empty($_POST['form']) ? '' : $_POST['form'];
		$form_fields = EPKB_Utilities::sanitize_form( $form );
		$input_handler = new EPKB_Input_Filter();
		$new_settings = $input_handler->retrieve_form_fields( $form_fields, $field_specs, $orig_settings );

		// sanitize and save settings in the database. see EPKB_Settings_DB class
		$result = epkb_get_instance()->settings_obj->update_settings( $new_settings );
		if ( is_wp_error( $result ) ) {
			/* @var $result WP_Error */
			$message = $result->get_error_data();
			if ( empty($message) ) {
				EPKB_Utilities::ajax_show_error_die( $result->get_error_message(), __( 'Could not save the new configuration', 'echo-knowledge-base' ));
			} else {
				EPKB_Utilities::ajax_show_error_die( $this->generate_error_summary( $message ), 'Configuration NOT saved due to following problems:' );
			}
		}

		// some settings require page reload
		$reload = $this->is_page_reload( $orig_settings, $new_settings, $field_specs);

		// we are done here
		EPKB_Utilities::ajax_show_info_die( $reload ? __( 'reload Settings saved. PAGE WILL RELOAD NOW.', 'echo-knowledge-base' ) : __( 'Settings saved', 'echo-knowledge-base' ) );
	}

	private function is_page_reload( $orig_settings, $new_settings, $spec ) {

		$diff = EPKB_Utilities::diff_two_dimentional_arrays( $new_settings, $orig_settings );
		foreach( $diff as $key => $value ) {
			if ( ! empty($spec[$key]['reload']) ) {
				return true;
			}
		}

		return false;
	}

	private function generate_error_summary( $errors ) {

		$output = '';

		if ( empty( $errors ) || ! is_array( $errors )) {
			return $output . __( 'unknown error', 'echo-knowledge-base' ) . ' (544)';
		}

		$output .= '<ol>';
		foreach( $errors as $error ) {
			$output .= '<li>' . wp_kses( $error, array('strong' => array('style' => array()),'div' => array('style' => array()),'p' => array()) ) . '</li>';
		}
		$output .= '</ol>';

		return $output;
	}

	/**
	 * Triggered when user submits feedback. Send email to the Echo Plugin team.
	 */
	public function send_feedback() {

		// verify that request is authentic
		if ( ! isset( $_REQUEST['_wpnonce_epkb_send_feedback'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce_epkb_send_feedback'], '_wpnonce_epkb_send_feedback' ) ) {
			EPKB_Utilities::ajax_show_error_die(__( 'Refresh your page', 'echo-knowledge-base' ));
		}

		// ensure user has correct permissions
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			EPKB_Utilities::ajax_show_error_die(__( 'You do not have permission.', 'echo-knowledge-base' ));
		}

		// retrieve user input
		$user_email = sanitize_email( $_POST['email'] );
		$user_email = empty($user_email) ? '[email missing]' : substr( $user_email, 0, 50 );
		$user_name = sanitize_text_field( $_POST['name'] );
		$user_name = empty($user_name) ? '[name missing]' : substr( $user_name, 0, 50 );
		$user_feedback = sanitize_text_field( $_POST['feedback'] );
		$user_feedback = empty($user_feedback) ? '[user feedback missing]' : substr( $user_feedback, 0, 1000 );

		// send feedback
		$api_params = array(
			'epkb_action'       => 'epkb_process_user_feedback',
			'user_email' 	    => $user_email,
			'user_name' 	    => $user_name,
			'user_feedback'	    => $user_feedback, // the name of our product in EDD
			'plugin_name'       => 'Echo Knowledge Base'
		);

		// Call the API
		$response = wp_remote_post(
			esc_url_raw( add_query_arg( $api_params, 'https://www.echoplugins.com' ) ),
			array(
				'timeout'   => 15,
				'body'      => $api_params,
				'sslverify' => false
			)
		);
		if ( is_wp_error( $response ) ) {
			EPKB_Utilities::ajax_show_error_die(__( 'Please contact us at: https://www.echoplugins.com/contact-us/', 'echo-knowledge-base' ), 'An error occurred' );
		}

		// we are done here
		EPKB_Utilities::ajax_show_info_die( __( 'Feedback sent. We will get back to you in a day or two. Thank you!', 'echo-knowledge-base' ) );
	}

	/**
	 * Record that user closed the welcome header or update message on the settings page
	 */
	public function close_welcome_header() {
		delete_option('epkb_show_welcome_header');
	}

	public function user_not_logged_in() {
		EPKB_Utilities::ajax_show_error_die( '<p>You are not logged in. Refresh your page and log in.</p>', 'Cannot save your changes' );
	}
}