<?php
/**
 * @package 1x
 */

/**
 * Function to cache a page on first time load on frontend once page loads,
 * then it will store page data on static file and after that server page from that static file.
 *
 * @return void
 */
function cache_page_on_first_load() {
	if ( ! is_admin() ) {
		$page_url   = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		$cache_file = get_home_path() . '/cache1/' . md5( $page_url ) . '.html';
		if ( ! file_exists( $cache_file ) ) {
			ob_start();
			get_header();
			the_content();
			get_footer();
			$output = ob_get_contents();
			file_put_contents( $cache_file, $output );
			ob_end_clean();
			echo $output;
			exit;
			// ob_end_clean();
		}

		if ( file_exists( $cache_file ) ) {
			// Replace readfile with appropriate WordPress filesystem method
			readfile( $cache_file );
			exit;
		}
	}
}

/**
 * Function for optimizing the database for fast queries.
 *
 * @return void
 */
function optimize_database() {
	global $wpdb;
	$wpdb->query( 'OPTIMIZE TABLE wp_posts, wp_postmeta, wp_comments, wp_commentmeta, wp_users, wp_options, wp_term_relationships, wp_term_taxonomy, wp_terms' );
}

/**
 * The function optimize_db_query_response() is a function that optimizes the database query response time.
 * It does this by setting the global variable max_allowed_packet to 1073741824.
 * This variable controls the maximum size of a packet that can be sent or received by the MySQL server.
 * By setting it to a higher value, the function allows the MySQL server to send and receive larger packets,
 * which can improve the performance of database queries.
 *
 * @return void
 */
function optimize_db_query_response() {
	global $wpdb;
	$wpdb->query( 'SET GLOBAL max_allowed_packet=1073741824' );
}

/**
 * Function to save database query result in database for when same query occurs then it will return from saved database query result.
 *
 * @return void
 */
function save_db_query_result() {
	global $wpdb;
	$query      = $wpdb->last_query;
	$result     = $wpdb->get_results( $query );
	$query_hash = md5( $query );
	$wpdb->query( "INSERT INTO wp_db_query_results (query_hash, result) VALUES ('$query_hash', '$result')" );
}

/**
 * Function to check if the same query before query execution.
 *
 * @param array  $posts The posts.
 * @param object $query The query object.
 * @return array
 */
function check_same_query_posts_pre_query( $posts, $query ) {
	$post_data          = $posts;
	$wpdbQuery_instance = $query;
	$query              = $wpdbQuery_instance->query;
	if ( $query ) {
		$query_hash = md5( $query );
		$result     = $wpdbQuery_instance->get_results( "SELECT * FROM wp_db_query_results WHERE query_hash = '$query_hash'" );
		if ( $result ) {
			$post_data = $result;
		}
	}
	return array( $post_data, $wpdbQuery_instance );
}

// Add filter to posts_pre_query hook.
add_filter( 'posts_pre_query', 'check_same_query_posts_pre_query', 10, 2 );
