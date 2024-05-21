<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Bail if WP-CLI is not present.
if ( ! defined( 'WP_CLI' ) ) {
	return;
}

// Only load if the class doesn't exist.
if ( ! class_exists( 'WP_CLI_1x_Command' ) ) {

	/**
	 * WP CLI Commands for My 1x Application.
	 *
	 * @extends WP_CLI_Command
	 */
	class WP_CLI_1x_Command extends WP_CLI_Command {
		/**
		 * Undocumented function
		 */
		public function __construct() {
			// $this->varnish_purge = new VarnishPurger();
		}


		/**
		 *  wp 1x updateDb  <options>
		 *  #Options:
		 *  arg1
		 *  arg2
		 */
		public function updateDb( $args, $assoc_args ) {
			WP_CLI::log( '======= Update DB Start  =======' );

			$args = array_map( 'trim', $args );
			$args = array_map( 'esc_attr', $args );

			WP_CLI::log( print_r( $args, true ) );
		} // End updateDb.




		/**
		 * Runs a `wp 1x debug` if you want to debug .
		 *
		 * ## EXAMPLES
		 *
		 *      wp 1x debug
		 *
		 *      wp 1x debug http://example.com/wp-content/themes/twentyeleventy/style.css
		 */
		public function debug( $args, $assoc_args ) {
			/***
			 *
			 *  $  wp 1x debug -tp=jh  --testing="testing good"
			 *  Example: print_r($assoc_args)
			 *          Array([testing] => testing good)
			 *
			 * $  wp 1x debug "https://google.com/1" "https://google.com/2"  --testing="testing good"
			 * */

			WP_CLI::log( 'Log start ' );
			// Set the URL/path.
			if ( ! empty( $args ) ) {
				list($url1, $url2) = $args;
			}

			WP_CLI::log( 'Log End  ' );
			WP_CLI::log( print_r( $args, true ) );
			WP_CLI::log( print_r( $assoc_args, true ) );

			WP_CLI::log( '=============== Flag value ============' );

			// $flag_val = WP_CLI\Utils\get_flag_value( $assoc_args, 'testing',25 );

			WP_CLI::log( $url1 );
			WP_CLI::log( $url2 );

			// WP_CLI::log( sprintf( __( 'Proxy Cache Purge development mode is currently %s.', 'varnish-http-purge' ), "Debugging State....") );
			// WP_CLI::error( sprintf( __( '%s is not a valid subcommand for development mode.', 'varnish-http-purge' ), "Test...." ) );
			// WP_CLI::success( sprintf( __( 'Proxy Cache Purge development mode has been %s.', 'varnish-http-purge' ), "Debugging State...." ) );

			// if ( WP_CLI\Utils\get_flag_value( $assoc_args, 'include-headers' ) ) {

			// }

			// WP_CLI::error( $preflight['message'] );

			// WP_CLI\Utils\format_items( $format, $items, array( 'name', 'status', 'message' ) );
		} // End Debug.
	}
}



/****
 *
 * $format = ( isset( $assoc_args['format'] ) ) ? $assoc_args['format'] : 'table';

 * WP_CLI::error( $preflight['message'] );
 *
 *
 * $items = array(array(
			'key'   => 'foo',
			'value'  => 'bar',
		));
	WP_CLI\Utils\format_items( 'table', $items, array( 'key', 'value' ) );
*/


WP_CLI::add_command( '1x', 'WP_CLI_1x_Command' );



/**
 * Flags: –quiet
 *
 *  WP_CLI\Utils\get_flag_value( $assoc_args, $flag, $default = null )
 *  if ( WP_CLI\Utils\get_flag_value( $assoc_args, 'include-grep' )
 *  isset( $assoc_args['quiet'] )
 * */
