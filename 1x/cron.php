<?php 

# creating cron run every 15 minutes and update up_option table key name `my_test_cron_key` 
// if (!wp_next_scheduled('my_test_cron_key')) {
//     wp_schedule_event(time(), 15, 'my_test_cron_key');

//     function my_test_cron_function() {

        
//         update_option('my_test_cron_key', );

//     }
// }


# create a schedule cron every 10 min 
// add_action( 'my_test_cron_hook', 'my_test_cron_function' );

// if ( ! wp_next_scheduled( 'my_test_cron_hook' ) ) {
//     wp_schedule_event( time(), 'ten_minutes', 'my_test_cron_hook' );
// }

// function my_test_cron_function() {
//     // Your cron job logic here
//     // Example: update an option
//     update_option( 'my_cron_option', 'Cron job executed successfully!' );
// }



add_action( 'my_hookname', 'my_function' );

function my_function() {
    wp_mail( 'hello@example.com', 'WP Crontrol', 'WP Crontrol rocks!' );
}



/** 
 * $ wp cron event list
 * $ Sudo crontab -e
 * 
 * Enter content `0 13 * * * /var/www/public_html/cron.php >/dev/null
 * */
