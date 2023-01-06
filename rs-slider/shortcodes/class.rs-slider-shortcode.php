<?php 

if( ! class_exists('RS_Slider_Shortcode')){
    class RS_Slider_Shortcode{
        public function __construct(){
            add_shortcode( 'rs_slider', array( $this, 'add_shortcode' ) );
        }

        public function add_shortcode( $atts = array(), $content = null, $tag = '' ){

            $atts = array_change_key_case( (array) $atts, CASE_LOWER );

            extract( shortcode_atts(
                array(
                    'id' => '',
                    'orderby' => 'date'
                ),
                $atts,
                $tag
            ));

            if( !empty( $id ) ){
                $id = array_map( 'absint', explode( ',', $id ) );
            }
            
            ob_start();
            require( RS_SLIDER_PATH . 'views/rs-slider_shortcode.php' );
            wp_enqueue_script( 'rs-slider-main-jq' );
            wp_enqueue_style( 'rs-slider-main-css' );
            wp_enqueue_style( 'rs-slider-style-css' );
            rs_slider_options();
            return ob_get_clean();
        }
    }
}
