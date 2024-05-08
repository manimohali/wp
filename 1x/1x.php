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

register_activation_hook(__FILE__, 'activate_jwtpbm_webhooks');
register_deactivation_hook(__FILE__, 'deactivate_jwtpbm_webhooks');


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-jwtpbm-activator.php
 */
function activate_jwtpbm_webhooks() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-jwtpbm-activator.php';
    JWTPBM_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-jwtpbm-deactivator.php
 */
function deactivate_jwtpbm_webhooks() {
    require_once plugin_dir_path(__FILE__) . 'includes/class-jwtpbm-deactivator.php';
    JWTPBM_Deactivator::deactivate();
}


if (defined('WP_CLI') && WP_CLI) {
    include_once JWTPBM_PLUGIN_DIR . '/cli/index.php';
}

/** included api file **/
include JWTPBM_PLUGIN_DIR . '/api/index.php';

/** included hooks file **/
include_once JWTPBM_PLUGIN_DIR . '/hooks/index.php';

/** included cron file **/
include_once JWTPBM_PLUGIN_DIR . '/cron/index.php';

add_action('admin_menu', 'custom_menu_page_2');

function custom_menu_page_2() {
    add_menu_page(
        'Sortable Menu', // Page title
        'Sortable Menu', // Menu title
        'manage_options', // Capability
        'sortable-menu', // Menu slug
        'sortable_menu_page', // Callback function
        'dashicons-menu', // Icon
        30 // Position
    );
}

function sortable_menu_page() {
    $cat_data_arr =[];
        $args = array(
            'taxonomy' => 'product_cat',
            // 'orderby'  => 'include',
            //'include'  => $term_ids,
            'hide_empty' => false
        );

        $product_categories = get_terms($args);
        foreach ($product_categories as $category) {
            $category->name = str_replace('&amp;', '&', $category->name);
            $cat_data_arr[] = ['id' => $category->term_id, 'text' => $category->name];
        }


    
?>
  <!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script> -->


<select id="mySelect" style="width: 300px;">
        <option value="">Select an option...</option>
</select>
    <!-- <div class="wrap">
        <div class="dd" id="nestable">
            <ol class="dd-list">
            </ol>
        </div>
    </div> -->

    <!-- <div class="wrap">
        <div class="dd" id="nestable">
            <ol class="dd-list">
                <li class="dd-item" data-id="1">
                    <div class="dd-handle">Item 1</div>
                </li>
                <li class="dd-item" data-id="2">
                    <div class="dd-handle">Item 2</div>
                </li>
                <li class="dd-item" data-id="3">
                    <div class="dd-handle">Item 3</div>
                    <ol class="dd-list">
                        <li class="dd-item" data-id="4">
                            <div class="dd-handle">Sub Item 1</div>
                        </li>
                        <li class="dd-item" data-id="5">
                            <div class="dd-handle">Sub Item 2</div>
                        </li>
                    </ol>
                </li>
                <li class="dd-item" data-id="6">
                    <div class="dd-handle">Item 4</div>
                </li>
            </ol>
        </div>
    </div> -->


    <div class="dd" id="nestable">
            <ol class="dd-list">
                <li class="dd-item" data-id="1">
                    <div class="dd-handle">
                        <label >Item 1</label>
                        <div class="content">
                            <p>Content 1</p>
                        </div>
                    </div>
                </li>
                <li class="dd-item" data-id="2">
                    <div class="dd-handle">
                        <label > <span class="item_name">Item 2</span> <a class="item-edit" id="edit-2800139" href="javascript:void(0)">Edit</a></label>
                        <div class="content">
                            <p>Content 2</p>
                        </div>
                    </div>
                </li>
            </ol>
    </div>
    

    


<script>
    //   $( "#accordion" ).accordion({
    //   collapsible: true
    // });

    <?php echo "var cat_data_arr = " . json_encode($cat_data_arr) . ";"; ?>

    function insertcateory(parent,data) {
        parent.find('.dd-list').append(`<li class="dd-item" data-id="${data.id}"><div class="dd-handle"> ${data.text}</div></li>`);
    }
    (function($) {
        $(document).ready(function() {
            $('#nestable').nestable();

            $('#mySelect').select2({
                data: cat_data_arr
            });

            $('#mySelect').on('select2:select', function(e) {
                let id = e.params.data.id;
                let text = e.params.data.text;
                insertcateory($('#nestable'),{id:id,text:text}) 
            });

            // $('.dd-handle label').click(function() {
            //     $(this).next('.content').toggle();
            // });

            // $( "#accordion" ).accordion();

        });

    })(jQuery);
</script>


<?php
}

// Save menu order
add_action('wp_ajax_save_menu_order', 'save_menu_order_callback');

function save_menu_order_callback() {
    if (isset($_POST['order'])) {
        $order = $_POST['order'];
    }
    wp_die(); // Always include this line to end AJAX requests properly
}
