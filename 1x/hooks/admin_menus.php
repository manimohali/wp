<?php
add_action('admin_menu', 'jwtpbm_add_admin_menu');
function jwtpbm_add_admin_menu() {
    add_menu_page(
        'Page title 1',
        'Menu Title 1',
        'manage_options',
        'page-title-slug-1',
        'page_title_slug_1_page_content',
        'dashicons-admin-generic',
        6
    );
}

function page_title_slug_1_page_content() {
?>
    <div class="wrap"><h1> <?php echo get_admin_page_title(); ?></div>
    <div class="wrap">
        

            <?php
            if (isset($_POST['custom_shop_page_cats'])) {
                $custom_shop_page_cats  = trim($_POST['custom_shop_page_cats']) == '' ? '' : trim($_POST['custom_shop_page_cats']);
                update_option('custom_shop_page_cats', $custom_shop_page_cats, false);
                echo "<div class='notice notice-success is-dismissible'><p>Category updated Successfuly</p></div>";
            }

            wp_enqueue_style('jquery-ui_css-css', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css', array(), '9999');
            wp_enqueue_style('select2-css', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '9999');
            wp_enqueue_script('select2-js', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '1.10.24', true);

            $product_categories = get_terms('product_cat', array('orderby' => 'name', 'hide_empty' => false));
            $select2_html = '';
            $text_JS_all_product_cat_array = [];
            $select2_html = '<select id="example_page_select" multiple="multiple" style="width: 300px">';

            foreach ($product_categories as $category) {
                $category_id   = $category->term_id;
                $category_name = $category->name;
                $select2_html .= "<option value='{$category_id}'>{$category_name}</option>";
                $text_JS_all_product_cat_array[$category_id] = $category_name;
            }
            $select2_html .= " </select>";
            $text_JS_all_product_cats = " var all_product_cats = " . json_encode($text_JS_all_product_cat_array) . ";";



            ?>

            <div class="wrap">
                <h2></h2>
            </div>



            <div class="wrap wraper_bg">
                <div class="row">
                    <label style="display: block;font-weight: bold;margin-bottom: 5px;">Select a Product Category</label>
                    <?php echo $select2_html;  ?>
                </div>



                <form id="sortable-categories-form" method="post">
                    <ul id="sortable-categories">
                        <?php
                        $custom_campaign_selected_pages = get_option('custom_shop_page_cats', '');
                        if (empty($custom_campaign_selected_pages) || is_null($custom_campaign_selected_pages) || trim($custom_campaign_selected_pages) == '') {
                            $custom_campaign_selected_pages = '';
                        } else {
                            $term_ids = explode(',', $custom_campaign_selected_pages);
                        }

                        // $term_ids = array(477, 441); 
                        if ($custom_campaign_selected_pages != '') {
                            $args = array(
                                'taxonomy' => 'product_cat',
                                'orderby'  => 'include',
                                'include'  => $term_ids,
                                'hide_empty' => false
                            );

                            $product_categories = get_terms($args);
                            // Loop through the product categories
                            foreach ($product_categories as $category) {
                                $category_id   = $category->term_id;
                                $category_name = $category->name;
                                echo "<li data-category-id='{$category_id}'> <span style='display: inline-block;width: 50%;'>{$category_name}</span><button type='button' onclick='deleteCategory(this)' class='button button-link-delete'> Delete </button></li>";
                            }
                        }

                        ?>
                    </ul>

                    <input type="hidden" name="custom_shop_page_cats" value="[]">
                    <button class="button button-primary" type="button" id="save-Category-btn">Save Category</button>
                </form>

            </div>



            <script>
                <?php echo $text_JS_all_product_cats; ?>

                jQuery('#example_page_select').select2({
                    placeholder: 'Select a Product Category',
                    width: '200px',
                });


                jQuery('#sortable-categories').sortable();


                jQuery('#example_page_select').on('select2:select', function(e) {
                    let selectedValue = e.params.data.id;
                    let selectedText = e.params.data.text;
                    addNewCategory(selectedValue, selectedText)
                    jQuery('#example_page_select').val([]);
                    jQuery('#example_page_select').trigger('change');
                });


                function addNewCategory(id, name) {
                    let html = `<li data-category-id="${id}" class="ui-sortable-handle" style=""><span style="display: inline-block;width: 50%;">${name}</span> <button type='button' onclick='deleteCategory(this)' class='button button-link-delete'> Delete </button></li>`;
                    jQuery(`#sortable-categories`).prepend(html);

                }

                function deleteCategory(instance) {
                    debugger;
                    jQuery(instance).closest('li').remove();
                }

                jQuery('#save-Category-btn').on('click', function() {
                    var all_selected_cats = [];
                    jQuery(`#sortable-categories li`).each(function(i, v) {
                        let cat_id = jQuery(v).attr('data-category-id');
                        all_selected_cats.push(cat_id);
                    });
                    jQuery(`input[name="custom_shop_page_cats"]`).val(all_selected_cats).trigger('change');
                    jQuery(this).closest('form').submit();
                })
            </script>

            <style>
                li.ui-sortable-handle:before {
                    background: url(https://code.jquery.com/ui/1.13.2/themes/base/images/ui-icons_444444_256x240.png);
                    width: 16px;
                    height: 16px;
                    content: "";
                    display: inline-block;
                    vertical-align: middle;
                    margin-top: -0.25em;
                    position: relative;
                    text-indent: -99999px;
                    overflow: hidden;
                    background-repeat: no-repeat;
                    background-position: -128px -48px;
                }

                li.ui-sortable-handle:after {
                    content: '';
                    background: transparent;
                    height: 37px;
                    display: block;
                    position: relative;
                    border: 1px solid #9E9E9E;
                    width: 60%;
                    margin-top: -35px;
                    border-radius: 4px;
                }

                li.ui-sortable-handle span {
                    padding-top: 6px;
                }

                .wraper_bg.wrap {
                    background: #fff;
                    padding: 20px 25px;
                    border-radius: 4px;
                }
            </style>

    </div>
<?php
}
