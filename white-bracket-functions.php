<?php

function get_white_bracket_option( $atts ) {
    $wb_atts = shortcode_atts( array(
        'option_name'   => '',
        'wpautop'       => 'yes'
    ), $atts );

    $wb_atts_check = strpos($wb_atts["option_name"], "wb_");

    if( $wb_atts_check !== false ):
    	$option_data = unserialize(get_option($wb_atts["option_name"]));
        if( $wb_atts["wpautop"] == 'no' ):
            $option_content = $option_data["option_text"];
        else:
            $option_content = wpautop($option_data["option_text"]);
        endif;
    else:
    	$option_content = wpautop('White Bracket option does not exist.');
    endif;

    return $option_content;
}
add_shortcode( 'wbo', 'get_white_bracket_option' );

function add_white_bracket_css_settings(){

    $h1_settings = get_option('white_bracket_setting_h1');
    $h2_settings = get_option('white_bracket_setting_h2');
    $h3_settings = get_option('white_bracket_setting_h3');
    $h4_settings = get_option('white_bracket_setting_h4');
    $h5_settings = get_option('white_bracket_setting_h5');
    $h6_settings = get_option('white_bracket_setting_h6');
    $p_settings = get_option('white_bracket_setting_p');
    $a_settings = get_option('white_bracket_setting_a');
    $li_settings = get_option('white_bracket_setting_li');

    $output = '<!-- ADDED BY WHITE BRACKET THEME OPTIONS -->'.PHP_EOL;
    $output .= '<style type="text/css">'.PHP_EOL;
        if($h1_settings):
            $output .= add_white_bracket_css( 'h1', $h1_settings );
        endif;
        if($h2_settings):
            $output .= add_white_bracket_css( 'h2', $h2_settings );
        endif;
        if($h3_settings):
            $output .= add_white_bracket_css( 'h3', $h3_settings );
        endif;
        if($h4_settings):
            $output .= add_white_bracket_css( 'h4', $h4_settings );
        endif;
        if($h5_settings):
            $output .= add_white_bracket_css( 'h5', $h5_settings );
        endif;
        if($h6_settings):
            $output .= add_white_bracket_css( 'h6', $h6_settings );
        endif;
        if($p_settings):
            $output .= add_white_bracket_css( 'p', $p_settings );
        endif;
        if($a_settings):
            $output .= add_white_bracket_css( 'a', $a_settings );
        endif;
        if($li_settings):
            $output .= add_white_bracket_css( 'li', $li_settings );
        endif;
    $output .= '</style>'.PHP_EOL;
    $output .= '<!-- ADDED BY WHITE BRACKET THEME OPTIONS -->'.PHP_EOL;

    if( $output != '<!-- ADDED BY WHITE BRACKET THEME OPTIONS --><style type="text/css"></style><!-- ADDED BY WHITE BRACKET THEME OPTIONS -->' ):
        echo $output;
    endif;

}
add_action('wp_head','add_white_bracket_css_settings');

function add_white_bracket_css( $tag, $setting ){

    $value = unserialize($setting);
    if( $value["font-size"] || $value["colour"] ):
        $font_size = $value["font-size"];
        if( stripos($font_size, 'px') > 0 ):
            $output = $tag.'{font-size:'.$value["font-size"].'; color:'.$value["colour"].';}'.PHP_EOL;
        else:
            $output = $tag.'{font-size:'.$value["font-size"].'px; color:'.$value["colour"].';}'.PHP_EOL;
        endif;
    else:
        $output = '';
    endif;

    return $output;
}

function add_white_bracket_google_analytics(){

    $google_analytics = get_option('white-bracket-google-analytics');

    $output = '<!-- ADDED BY WHITE BRACKET THEME OPTIONS -->'.PHP_EOL;
    $output .= "<script>".PHP_EOL;;
        $output .= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){".PHP_EOL;
        $output .= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),".PHP_EOL;
        $output .= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)".PHP_EOL;
        $output .= "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');".PHP_EOL;
        $output .= "".PHP_EOL;
        $output .= "ga('create', '".$google_analytics."', 'auto');".PHP_EOL;
        $output .= "ga('send', 'pageview');".PHP_EOL;
    $output .= "</script>".PHP_EOL;
    $output .= '<!-- ADDED BY WHITE BRACKET THEME OPTIONS -->'.PHP_EOL;

    echo $output;
}
$google_analytics = get_option('white-bracket-google-analytics');
if($google_analytics):
    add_action('wp_head','add_white_bracket_google_analytics');
endif;