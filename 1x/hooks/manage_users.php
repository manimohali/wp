<?php
/**
 * Function to assign upload files capability to `customer` role.
 *
 *  @package YourPackageName
 *
 * @return array Modified capabilities.
 */


add_filter( 'user_has_cap', 'my_user_has_cap_1', 10, 3 );

/**
 * Undocumented function
 *
 * @param [type] $allcaps
 * @param [type] $cap
 * @param [type] $args
 * @return $allcaps
 */
function my_user_has_cap_1( $allcaps, $cap, $args ) {
	if ( in_array( 'customer', $allcaps, true ) ) {
		$allcaps['upload_files'] = true;
	}
	return $allcaps;
}
