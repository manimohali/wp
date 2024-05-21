<?php
/**
 * @package 1x
 * @version 1.0.0
 */


class JWTPBM_WP_Options {

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function __construct() {
	}

	/**
	 * Undocumented function
	 *
	 * @param WP_REST_Request $request
	 * @return WP_REST_Response
	 */
	public function head_categories( WP_REST_Request $request ) {
		$response                       = array();
		$custom_campaign_selected_pages = get_option( 'custom_shop_page_cats', '' );
		if ( empty( $custom_campaign_selected_pages ) || is_null( $custom_campaign_selected_pages ) || trim( $custom_campaign_selected_pages ) === '' ) {
			$term_ids = array();
		} else {
			$term_ids = explode( ',', $custom_campaign_selected_pages );
		}

		if ( count( $term_ids ) > 0 ) {
			$args = array(
				'taxonomy'   => 'product_cat',
				'orderby'    => 'include',
				'include'    => $term_ids,
				'hide_empty' => false,
			);

			$product_categories = get_terms( $args );
			foreach ( $product_categories as $category ) {
				$category_id              = $category->term_id;
				$category_name            = $category->name;
				$response[ $category_id ] = array(
					'id'   => $category_id,
					'name' => $category_name,
				);
			}

			// Reorder same as the `$term_ids` array
			$response = array_map(
				function ( $key ) use ( $response ) {
					return $response[ $key ];
				},
				$term_ids
			);

			return new WP_REST_Response( $response, 200 );
		}
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $key
	 * @param [type] $value
	 * @return boolean
	 */
	public function update_option( $key, $value ) {
		return update_option( $key, $value );
	}
}
