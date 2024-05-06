<?php 


# function to  cache a page on first time load on frontend once page loads, then it will  store page data on static file and after that serer page from that static file.
function cache_page_on_first_load() {
    if (!is_admin()) {
        $page_url = $_SERVER['REQUEST_URI'];
        $cache_file = get_home_path() . '/cache1/' . md5($page_url) . '.html';
        if (!file_exists($cache_file)) {
            ob_start();
            get_header();
            the_content();
            get_footer();
            $output = ob_get_contents();
            file_put_contents($cache_file, $output);
            ob_end_clean();
            echo $output;
            exit;
            // ob_end_clean();
        }

        if (file_exists($cache_file)) {
            readfile($cache_file);
            exit;
        }
    }
}
// add_action('template_redirect', 'cache_page_on_first_load',1);


# function for optimize database for fast query
function optimize_database() {
    global $wpdb;
    $wpdb->query("OPTIMIZE TABLE wp_posts, wp_postmeta, wp_comments, wp_commentmeta, wp_users, wp_options, wp_term_relationships, wp_term_taxonomy, wp_terms");
}
// add_action('wp_footer', 'optimize_database');


/**
 * The function optimize_db_query_response() is a function that optimizes the database query response time. It does this by setting the global variable max_allowed_packet to 1073741824. This variable controls the maximum size of a packet that can be sent or received by the MySQL server. By setting it to a higher value, the function allows the MySQL server to send and receive larger packets, which can improve the performance of database queries.
 * @return void
 */
function optimize_db_query_response() {
    global $wpdb;
    $wpdb->query("SET GLOBAL max_allowed_packet=1073741824");
}
// add_action('wp_footer', 'optimize_db_query_response');



# function to save db query result in db for when same query occars then it will return from saved db query result.
function save_db_query_result() {
    global $wpdb;
    $query = $wpdb->last_query;
    $result = $wpdb->get_results($query);
    $query_hash = md5($query);
    $wpdb->query("INSERT INTO wp_db_query_results (query_hash, result) VALUES ('$query_hash', '$result')");
}


// add_action('wp_footer', 'save_db_query_result');


# function to check if same query before `query execution use hook `
// function check_same_query() {
//     global $wpdb;
//     $query = $wpdb->last_query;
//     $query_hash = md5($query);
//     $result = $wpdb->get_results("SELECT * FROM wp_db_query_results WHERE query_hash = '$query_hash'");
//     if ($result) {
//         return $result;
//     } else {
//         return false;
//     }
// }


## list of db query filter hooks
// add_filter('pre_query', 'check_same_query');
// add_filter('wp_footer', 'save_db_query_result');
// add_filter('wp_footer', 'optimize_db_query_response');
// add_filter('wp_footer', 'optimize_database');
// add_filter('template_redirect', 'cache_page_on_first_load');



function check_same_query_posts_pre_query($posts, $query) {
    #check is admin or has plugin update capability then return
    // if (is_admin() || current_user_can('update_plugins')) {
    //     return $posts;
    // }

    $post_data = $posts;
    $wpdbQuery_instance = $query;
    $query = $wpdbQuery_instance->query;
    #check query in db
    if ($query) {
        $query_hash = md5($query);
        $result = $wpdbQuery_instance->get_results("SELECT * FROM wp_db_query_results WHERE query_hash = '$query_hash'");
        if ($result) {
            $post_data = $result;
        }
    }
    return [$post_data, $wpdbQuery_instance];
}


# list of plugin_loaded hooks
// add_action( 'muplugins_loaded', 'check_same_query_init' );
function check_same_query_init() {
    add_filter('posts_pre_query', 'check_same_query_posts_pre_query' . 10, 2);
}
