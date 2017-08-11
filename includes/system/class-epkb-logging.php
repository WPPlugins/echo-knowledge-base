<?php

/**
 * Log errors into a database table for later analysis
 *
 * @copyright   Copyright (C) 2017, Echo Plugins
 * @license http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_Logging {

	const LOGGING_OPTION_NAME =  'epkb_error_log';
	const MAX_NOF_LOGS_STORED = 10;

	/**
	 * Print safely message with content of a variable
	 *
	 * @param $error_message
	 * @param $variable
	 * @param null $wp_error
	 */
	public static function add_log_var( $error_message, $variable, $wp_error=null  ) {
		// do not log anything if not in the back-end or not logged in as an admin
		if ( ! self::can_log_message() ) {
			return;
		}

		// switch $variable and $wp_error if caller switched them by mistake
		$wp_error_tmp = is_wp_error( $variable ) ? $variable : $wp_error;
        $wp_error = is_wp_error( $wp_error_tmp ) ? $wp_error_tmp : null;
		$variable = is_wp_error( $variable ) ? ( is_wp_error( $wp_error ) ? '' : $wp_error ) : $variable;

        $variable_msg = empty($variable) ? '[]' : ': ' . EPKB_Utilities::get_variable_string( $variable );

		self::add_log( $error_message . $variable_msg, $wp_error );
	}

	/**
	 * Add a new log entry to the log stored in the WP options table
	 *
	 * @param $error_message
	 * @param WP_Error $wp_error
	 */
	public static function add_log( $error_message, $wp_error=null ) {

		// do not log anything if not in the back-end or not logged in as an admin
		if ( ! self::can_log_message() ) {
			return;
		}

		if ( $wp_error instanceof  WP_Error ) {
			$error_message .= ' WP Error: ' . $wp_error->get_error_message();
		}

		$error_message = trim( sanitize_text_field( $error_message ) );
		if ( empty($error_message) ) {
			return;
		}

		// retrieve current logs
		$error_log = self::get_logs();

		// prepare error message
		$error_message = EPKB_Utilities::substr( $error_message, 0, 200);
		$serialized_error_message = serialize( $error_message ); //serialize(base64_encode( $error_message ) );
		$unserialized_error_message = unserialize( $serialized_error_message ); //base64_decode(unserialize( $serialized_error_message ) );
		if ($unserialized_error_message != $error_message) {
			$error_message = "can't serialize error message:" . preg_replace('/[^A-Za-z0-9\-]/', '.', $error_message);
		}

		// prepare error stack trace
		$stack_trace = self::generateStackTrace();


		// add new error log entry but remove oldest one if more than max
		$error_log[] = array( date("Y-m-d H:i:s"), $error_message, $stack_trace );

		if ( count($error_log) > self::MAX_NOF_LOGS_STORED ) {
			array_shift($error_log);
		}

		// save the error log
		update_option( self::LOGGING_OPTION_NAME, $error_log, false );
	}

	/**
	 * Get stored logs
	 *
	 * @return array|false return logs or false if logs cannot be serialized
	 */
	public static function get_logs() {
		$logs = EPKB_Utilities::get_wp_option( self::LOGGING_OPTION_NAME, array(), true );
		$logs = is_array($logs) ? $logs : array();
		return $logs;
	}

	/**
	 * Remove stored logs
	 */
	public static function reset_logs() {
		delete_option( self::LOGGING_OPTION_NAME );
	}

	/**
	 * Do not log anything if not in the back-end or not logged in as an admin
     *
	 * @return bool
	 */
	private static function can_log_message() {
		return false; // TODO FUTURE ( current_user_can( 'manage_options' ) || defined('EP_TESTING') ) ;
	}

	public static function generateStackTrace()
	{
		$msg = "\n\tStack Trace:\n";
		$stackMsg = "";
		foreach(debug_backtrace() as $trace) {

			$file = (isset($trace['file']) ? $trace['file'] : '');
			$file = EPKB_Utilities::substr($file, 1);

			$function = (isset($trace['function']) ? $trace['function'] : '[unknown]');

			$line = (isset($trace['line']) ? $trace['line'] : '');
			$line = "\t" . $file . ' - ' . $function . '():' . $line . "\n";

			if (strpos($line, 'generateStackTrace') !== false ) {
				continue;
			}

			$stackMsg .= $line;
		}

		$stackMsg = (empty($stackMsg) ? '' : $msg . $stackMsg);
		$stackMsg = str_replace('\\', '/', $stackMsg);
		$stackMsg = EPKB_Utilities::substr( $stackMsg, 0, 200);

		$serialized_stackMsg = serialize( $stackMsg ); //serialize(base64_encode( $stackMsg ) );
		$unserialized_stackMsg = unserialize( $serialized_stackMsg ); //base64_decode(unserialize( $serialized_stackMsg ) );
		if ($unserialized_stackMsg != $stackMsg) {
			$stackMsg = "can't serialize stacktrace:" . preg_replace('/[^A-Za-z0-9\-]/', '.', $stackMsg);
		}

		return $stackMsg;
	}

}