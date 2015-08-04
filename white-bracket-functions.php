<?php

function get_white_bracket_option( $atts ) {
    $wb_atts = shortcode_atts( array(
        'option_name' => ''
    ), $atts );

    $wb_atts_check = strpos($wb_atts["option_name"], "wb_");

    if( $wb_atts_check !== false ):
    	$option_data = unserialize(get_option($wb_atts["option_name"]));
    	$option_content = wpautop($option_data["option_text"]);
    else:
    	$option_content = wpautop('White Bracket option does not exist.');
    endif;

    return $option_content;
}
add_shortcode( 'wbo', 'get_white_bracket_option' );