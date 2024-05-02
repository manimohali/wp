<?php

class JWTPBM_Posts {

    public function __construct() {
    }

    public function get_posts(WP_REST_Request $request) {

        $page            = is_null($request->get_param('page')) ? 1 : $request->get_param('page');
        $per_page        = is_null($request->get_param('per_page')) ? 10 : $request->get_param('per_page');
        $post_type       = is_null($request->get_param('post_type')) ? 'post' : trim($request->get_param('post_type'));
        $post_status     = is_null($request->get_param('post_status')) ? 'publish' : trim($request->get_param('post_status'));

        $offset = ($page - 1) * $per_page;

        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'paged'          => $page,
            'post_status'    => $post_status,
        );

        try {
            $custom_posts = new WP_Query($args);
            if (!$custom_posts->have_posts()) {
                return new WP_Error('post_not_found', 'Posts not found.', array('status' => 404));
            }

            return new WP_REST_Response(['total_pages' => $custom_posts->max_num_pages, 'current_page' => $page, 'next_page' => $page + 1, 'prev_page' => $page - 1, 'total_posts' => $custom_posts->found_posts, 'data' => $custom_posts->posts], 200);
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            $error_code = $e->getCode();
            return new WP_Error('unknow_error',  $error_message, array('status' => $error_code));
        }
    }

    public function get_post(WP_REST_Request $request) {
        $id = $request->get_param('id');
        $post = get_post($id);

        if (!$post) {
            return new WP_Error('post_not_found', 'Post not found.', array('status' => 404));
        }

        return new WP_REST_Response($post, 200);
    }

    
    public function get_posts_by_category(WP_REST_Request $request) {
        $term_id         = $request->get_param('term_id');
        $page            = is_null($request->get_param('page')) ? 1 : $request->get_param('page');
        $per_page        = is_null($request->get_param('per_page')) ? 10 : $request->get_param('per_page');
        $taxonomy       = is_null($request->get_param('taxonomy')) ? 'product_cat' : trim($request->get_param('taxonomy'));
        $post_type       = is_null($request->get_param('post_type')) ? 'post' : trim($request->get_param('post_type'));
        // $post_status     = is_null($request->get_param('post_status')) ? 'publish' : trim($request->get_param('post_status'));
        $post_status     = 'publish';

        $offset = ($page - 1) * $per_page;

        $args = array(
            'post_type'      => $post_type,
            'posts_per_page' => $per_page,
            'offset'         => $offset,
            'paged'          => $page,
            'post_status'    => $post_status,
            'tax_query' => array(
                array(
                    'taxonomy' => $taxonomy ,
                    'field'    => 'term_id',
                    'terms'    => array($term_id),
                ),
            ),
        );

        try {
            $custom_posts = new WP_Query($args);
            if (!$custom_posts->have_posts()) {
                return new WP_Error('post_not_found', 'Posts not found.', array('status' => 404));
            }

            $posts =  array_map(function ($post) {
                $post_data = new stdClass();
                $post_data->ID = $post->ID;
                $post_data->post_title = $post->post_title;
                $post_data->post_date_gmt = $post->post_date_gmt;
                $post_data->post_modified_gmt = $post->post_modified_gmt;
                $post_data->post_title = $post->post_title;
                return $post_data;
            }, $custom_posts->posts);

            return new WP_REST_Response(['total_pages' => $custom_posts->max_num_pages, 'current_page' => $page, 'next_page' => $page + 1, 'prev_page' => $page - 1, 'total_posts' => $custom_posts->found_posts, 'data' => $posts], 200);
        } catch (Exception $e) {
            $error_message = $e->getMessage();
            $error_code = $e->getCode();
            return new WP_Error('unknow_error',  $error_message, array('status' => $error_code));
        }
    }
}
