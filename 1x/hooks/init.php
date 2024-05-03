<?php

/**
 * @package JWTPBM
 * @version 1.0
 **/


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'JWTPBM_init');
function JWTPBM_init() {

    // Clear the scheduled event
    // wp_clear_scheduled_hook( 'my_custom_cron_job' );

     // Schedule the event to run every minute
    if (!wp_next_scheduled('my_custom_cron_job')) {
        wp_schedule_event(time(), 'minutely', 'my_custom_cron_job');
    }

      // Schedule the event to run after 24 hours
    if ( ! wp_next_scheduled( 'custom_single_cron_job_hook' ) ) {
        wp_schedule_single_event( time() + 24 * 60 * 60, 'custom_single_cron_job_hook' );
    }

}

