<?php 

//Assigning  upload files capability to `customer` role
add_filter( 'user_has_cap', 'my_user_has_cap_1', 10, 3 );
function my_user_has_cap_1( $allcaps, $caps, $args ) {
    if(in_array('customer', $allcaps)){
        $allcaps['upload_files'] = true;
    }
    return $allcaps;
}
