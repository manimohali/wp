<?php

/**
 * Plugin Name: 1x
 * Plugin URI: https://github.com/1x/1x
 * Description: 1x is a simple plugin that adds a 1x option to the image sizes dropdown in the WordPress media uploader.
 * Version: 1.0.0
 * Author: 1x
 * Author URI: https://github.com/1x
 * License: MIT
 * License URI: https://opensource.org/licenses/MIT
 */

 
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



 define('JWTPBM_PLUGIN_DIR', __DIR__);
 define('JWTPBM_PLUGIN_URL', plugin_dir_url(__FILE__));
 define('JWTPBM_PLUGIN_BASENAME', plugin_basename(__FILE__));
 define('JWTPBM_PLUGIN_VERSION', '1.0.0');

register_activation_hook( __FILE__, 'activate_jwtpbm_webhooks' );
register_deactivation_hook( __FILE__, 'deactivate_jwtpbm_webhooks' );


 /**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jwtpbm-activator.php
 */
function activate_jwtpbm_webhooks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jwtpbm-activator.php';
	JWTPBM_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jwtpbm-deactivator.php
 */
function deactivate_jwtpbm_webhooks() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jwtpbm-deactivator.php';
	JWTPBM_Deactivator::deactivate();
}


if ( defined( 'WP_CLI' ) && WP_CLI ) {
    include_once JWTPBM_PLUGIN_DIR . '/cli/index.php';
}

/** included api file **/
include JWTPBM_PLUGIN_DIR.'/api/index.php';

/** included hooks file **/
include_once JWTPBM_PLUGIN_DIR . '/hooks/index.php';

/** included cron file **/
include_once JWTPBM_PLUGIN_DIR . '/cron/index.php';
