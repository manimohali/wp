<?php 


/** 
 * $ wp cron event list
 * $ Sudo crontab -e
 * 
 * Enter content `0 13 * * * /var/www/public_html/cron.php >/dev/null
 * */


// Custom cron schedules 

 
 add_filter( 'cron_schedules', 'custom_register_cron_schedules' );

 function custom_register_cron_schedules( $schedules ) {
     $schedules['minutely'] = array(
         'interval' => MINUTE_IN_SECONDS, 
         'display' => __( 'One Minute' )
     );
 
     $schedules['per_6_hours'] = array(
         'interval' => 6 * HOUR_IN_SECONDS,
         'display' => __( 'Every 6 Hours' )
     );
 
     return $schedules;
 }
 
 


// Schedule the cron job to run every minute.
if (!wp_next_scheduled('my_custom_cron_job')) {
    // wp_schedule_event(time(), 'minutely', 'my_custom_cron_job');
}


// Hook the function to the cron job.
add_action('my_custom_cron_job', 'my_custom_cron_function');
// Function to execute when the cron job runs.
function my_custom_cron_function() {
    global $wpdb;

    // Check if the table exists.
    $table_name = $wpdb->prefix . 'my_custom_crontable';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        // Create the table if it doesn't exist.
        $sql = "CREATE TABLE $table_name (
            id INT NOT NULL AUTO_INCREMENT,
            number INT NOT NULL,
            PRIMARY KEY (id)
        )";
        $wpdb->query($sql);
    }

    // Insert a new row into the table.
    $wpdb->insert($table_name, array('number' => time()));
}



/** Run cron job once */
add_action( 'custom_single_cron_job_hook', 'custom_single_cron_job_function' );
function custom_single_cron_job_function() {
    // Your code here
}

