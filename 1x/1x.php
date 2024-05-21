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
 * Text Domain: 1x
 * Domain Path: /languages
 * @package 1x
 */


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);



define( 'JWTPBM_PLUGIN_DIR', __DIR__ );
define( 'JWTPBM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'JWTPBM_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'JWTPBM_PLUGIN_VERSION', '1.0.0' );

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

/** included api file */
require JWTPBM_PLUGIN_DIR . '/api/index.php';

/** included hooks file */
require_once JWTPBM_PLUGIN_DIR . '/hooks/index.php';

/** included cron file */
require_once JWTPBM_PLUGIN_DIR . '/cron/index.php';

/**
 * custom_menu_page_2
 *
 * @return void
 */
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
add_action( 'admin_menu', 'custom_menu_page_2' );


/**
 * sortable_menu_page
 *
 * @return void
 */
function sortable_menu_page() {
	$cat_data_arr = array();
	$args         = array(
		'taxonomy'   => 'product_cat',
		// 'orderby'  => 'include',
		// 'include'  => $term_ids,
		'hide_empty' => false,
	);

	$product_categories = get_terms( $args );
	foreach ( $product_categories as $category ) {
		$category->name = str_replace( '&amp;', '&', $category->name );
		$cat_data_arr[] = array(
			'id'   => $category->term_id,
			'text' => $category->name,
		);
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
					<label> <span class="item_name">Item 1</span> <button class="item-edit">Edit</button></label>
					<div class="content">
						<p>Content 1</p>
					</div>
				</div>
			</li>
			<li class="dd-item" data-id="2">
				<div class="dd-handle">
					<label> <span class="item_name">Item 2</span> <button class="item-edit">Edit</button></label>
					<div class="content">
						<p>Content 2</p>
					</div>
				</div>
			</li>
		</ol>
	</div>





	<script>
		window.jwtpbm_caterories = {};
		window.jwtpbm_caterories.data = [];
		window.jwtpbm_caterories.currentIndex = 0;


		function jwtpbm_debounce(func, wait) {
			let timeout;
			return function executedFunction(...args) {
				const later = () => {
					clearTimeout(timeout);
					func(...args);
				};

				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		}


		function input_field_key_change(e) {
			console.log(e.target.value);
		}

		function jwtpbm_generate_html(type = 'text', key = 'undefined_key', label = ' Untittled', value = '') {
			let field_data = {
				type: type,
				key: key,
				label: label,
				value: value
			};
			switch (type) {

				case 'text': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
				
						<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">
							<label> ${label} </label>
							<input type="text" name="${key}" value="${value}" />
						</div>
					</div>
				`;
				}
				case 'textarea': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
					<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">

						<label> ${label} </label>
						<textarea>${value}</textarea>
						</div>
					</div>
				`;
				}
				case 'number': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
					<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">

						<label> ${label} </label>
						<input type="number" value="${value}" />
						</div>
					</div>
				`;
				}
				case 'image': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
					<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">

						<label> ${label} </label>
						<input type="file" accept="image/*" />
						</div>
					</div>
				`;
				}
				case 'video': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
					<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">

						<label> ${label} </label>
						<input type="file" accept="video/*" />
						</div>
					</div>
				`;
				}
				case 'audio': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
						<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>

						<div class="field">
							<label> ${label} </label>
							<input type="file" accept="audio/*" />
						</div>
					</div>
				`;
				}
				case 'file': {
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
					<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>
						<div class="field">
							<label> ${label} </label>
							<input type="file" />
						</div>
					</div>
				`;
				}
				default:
					return `
					<div class="common_field_data" ${JSON.stringify(field_data)}> 
						<div class="field">
							<label> Enter key  </label>
							<input class="input_field_key" type="text" onchange="jwtpbm_debounce(input_field_key_change, 200)" placeholder="Enter ${key }"  value="${value}" />
						</div>

						<div class="field">
							<label> ${label} </label>
							<input type="text" value="${value}" />
						</div>
					</div>
				`;
			}
		}

		function jwtpbm_add_category(daparent, data) {
			let new_data = data;
			let data_id = window.jwtpbm_caterories.currentIndex + 1;
			let parent = daparent;

			new_data.id = data.id ?? null;
			new_data.post_type = data.post_type ?? null;
			new_data.title = data.post_type ?? 'Untitled';
			new_data.taxonomy = data.taxonomy ?? null;
			new_data.isTaxonomy = data.isTaxonomy ?? false;
			new_data.isPostType = data.isPostType ?? false;

			let add_new_el_html = `
						<div class="setting_div"> 
							<button class="plus_btn"> + </button>
								<div class="setting_div_content">
									<ul>
										<li class="add_input_filed" type="text" > Add Text </li>
										<li class="add_input_filed" type="textarea"> Add Textarea </li>
										<li class="add_input_filed" type="number" > Add Number </li>
									</ul>
								</div>
						</div>
					`;

			let setting_data_html = `
								<div class="setting_data_content"> </div>
							`;


			let html = `
					<li class="dd-item" data-id="${data_id}">
						<div class="dd-handle">
							<label> <span class="item_name">${data.title}</span> <button class="item-edit">Edit</button></label>
							<div class="content" item-data="${JSON.stringify(new_data)}">
								${add_new_el_html}
								${setting_data_html}
							</div>
						</div>
					</li>
				`;




			parent.find('.dd-list').append(html);
			window.jwtpbm_caterories.data[data_id] = new_data;
			console.log(window.jwtpbm_caterories);

		}

		<?php echo 'var cat_data_arr = ' . wp_json_encode( $cat_data_arr ) . ';'; ?>

			(function($) {
				$(document).ready(function() {
					var nestable = $('#nestable').nestable();

					$('#mySelect').select2({
						data: cat_data_arr
					});

					$('#mySelect').on('select2:select', function(e) {
						let id = e.params.data.id;
						let text = e.params.data.text;
						jwtpbm_add_category($('#nestable'), {
							id: id,
							title: text
						})
					});

					$('#nestable').on('click', '.dd-handle .item-edit', function() {
						$(this).closest('.dd-handle').toggleClass('height-auto');
					});


					$('#nestable').on('click', '.dd-handle .add_input_filed', function() {
						// debugger;
						let type  = $(this).attr('type');
						let html = jwtpbm_generate_html(type);
						$(this).closest('.content').find('.setting_data_content').prepend(html);
					});

					// used to get the serialized data
					// window.JSON.stringify($('#nestable').nestable().nestable('serialize'))


				});

			})(jQuery);
	</script>

	<style>

.common_field_data {
	margin: 10px 5px;
}

.setting_div_content {
	background: aquamarine;
	padding: 14px 10px;
	margin-top: 15px;
	border-radius: 5px;
}

.setting_div_content ul li {
	display: block;
	border: 1px solid gray;
	padding: 5px 10px;
	border-radius: 4px;
	color: #000;
	background: #fff;
	cursor: pointer;
}

.setting_div_content ul li:hover {
	background: #ddf4fb;
}


		.content {
			display: none;
		}

		.dd-handle.height-auto .content {
			display: block;
		}

		.dd-handle label {
			width: 100%;
			display: block;
		}

		.dd-handle .item-edit {
			background: deepskyblue;
			width: 40px;
			display: inline-block;
			text-align: center;
			color: #000;
			padding: 0px 0px;
		}

		.dd-handle span.item_name {
			display: inline-block;
			width: calc(99.4% - 40px);
		}

		.height-auto {
			height: auto;
		}

		.display-none {
			display: none;
		}
	</style>

	<?php
}


/**
 * Save menu order callback
 *
 * @return void
 */
function save_menu_order_callback() {
	if ( isset( $_POST['order'] ) ) {
		$order = $_POST['order'];
	}
	wp_die(); // Always include this line to end AJAX requests properly
}
add_action( 'wp_ajax_save_menu_order', 'save_menu_order_callback' );
