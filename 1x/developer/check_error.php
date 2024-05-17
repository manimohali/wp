<?php

// function check_batch_limit($limit) {
// $total = 100;
// if ($total > $limit) {
// return new WP_Error('woocommerce_rest_request_entity_too_large', sprintf(__('Unable to accept more than %s items for this request.', 'woocommerce'), $limit), array('status' => 413));
// }

// return true;
// }


// $$response = array();
// try {

// $_response = check_batch_limit(50);
// if (is_wp_error($_response)) {
// $response['create'][] = array(
// 'id'    => 0,
// 'error' => array(
// 'code'    => $_response->get_error_code(),
// 'message' => $_response->get_error_message(),
// 'data'    => $_response->get_error_data(),
// ),
// );
// }
// } catch (\Exception $e) {
// $response =  $e->getMessage();
// }



// print_r($response);
// echo "vkvkfbb";
