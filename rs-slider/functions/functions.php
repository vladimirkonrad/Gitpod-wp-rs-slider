<?php
if( ! function_exists( 're_slider_get_placeholder_image' )){
    function rs_slider_get_placeholder_image(){
        return "<img src='" . MV_SLIDER_URL . "assets/images/default.jpg' class='img-fluid wp-post-image' />";
    }
}

if( ! function_exists( 'rs_slider_options' )){
    function rs_slider_options(){
        $show_bullets = isset( RS_Slider_Settings::$options['rs_slider_bullets'] ) && RS_Slider_Settings::$options['rs_slider_bullets'] == 1 ? true : false;

        wp_enqueue_script( 'rs-slider-options-js', MV_SLIDER_URL . 'vendor/flexslider/flexslider.js', array( 'jquery' ), RS_SLIDER_VERSION, true );
        wp_localize_script( 'rs-slider-options-js', 'SLIDER_OPTIONS', array(
            'controlNav' => $show_bullets
        ) );
    }
}

// ovde dopisati 