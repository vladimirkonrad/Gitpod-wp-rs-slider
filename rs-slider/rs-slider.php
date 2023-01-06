<?php

/**
 * Plugin Name: RS Slider
 * Plugin URI: https://www.wordpress.org/rs-slider
 * Description: Realy Simple Slider
 * Version: 1.0
 * Requires at least: 5.6
 * Author: Vladimir Konrad
 * Author URI: https://vladimirkonrad.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rs-slider
 * Domain Path: /languages
 */

 /*
RS Slider is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
RS Slider is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with RS Slider. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if( ! defined( 'ABSPATH') ){
    exit;
}

if( ! class_exists( 'RS_Slider' ) ){
    class RS_Slider{
        function __construct(){
            $this->define_constants();

            $this->load_textdomain();

            require_once( RS_SLIDER_PATH . 'functions/functions.php' );

            add_action( 'admin_menu', array( $this, 'add_menu' ) );

            require_once( RS_SLIDER_PATH . 'post-types/class.rs-slider-cpt.php' );
            $RS_Slider_Post_Type = new RS_Slider_Post_Type();

            require_once( RS_SLIDER_PATH . 'class.rs-slider-settings.php' );
            $RS_Slider_Settings = new RS_Slider_Settings();

            require_once( RS_SLIDER_PATH . 'shortcodes/class.rs-slider-shortcode.php' );
            $RS_Slider_Shortcode = new RS_Slider_Shortcode();

            add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 999 );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts') );
        }

        public function define_constants(){
            define( 'RS_SLIDER_PATH', plugin_dir_path( __FILE__ ) );
            define( 'RS_SLIDER_URL', plugin_dir_url( __FILE__ ) );
            define( 'RS_SLIDER_VERSION', '1.0.0' );
        }

        public static function activate(){
            update_option( 'rewrite_rules', '' );
        }

        public static function deactivate(){
            flush_rewrite_rules();
            unregister_post_type( 'rs-slider' );
        }

        public static function uninstall(){

            delete_option( 'rs_slider_options' );

            $posts = get_posts(
                array(
                    'post_type' => 'rs-slider',
                    'number_posts'  => -1,
                    'post_status'   => 'any'
                )
            );

            foreach( $posts as $post ){
                wp_delete_post( $post->ID, true );
            }
        }

        public function load_textdomain(){
            load_plugin_textdomain(
                'rs-slider',
                false,
                dirname( plugin_basename( __FILE__ ) ) . '/languages/'
            );
        }

        public function add_menu(){
            add_menu_page(
                esc_html__( 'RS Slider Options', 'mv-slider' ),
                'RS Slider',
                'manage_options',
                'rs_slider_admin',
                array( $this, 'rs_slider_settings_page' ),
                'dashicons-images-alt2'
            );

            add_submenu_page(
                'rs_slider_admin',
                esc_html__( 'Manage Slides', 'rs-slider' ),
                esc_html__( 'Manage Slides', 'rs-slider' ),
                'manage_options',
                'edit.php?post_type=rs-slider',
                null,
                null
            );

            add_submenu_page(
                'rs_slider_admin',
                esc_html__( 'Add New Slide', 'rs-slider' ),
                esc_html__( 'Add New Slide', 'rs-slider' ),
                'manage_options',
                'post-new.php?post_type=rs-slider',
                null,
                null
            );

        }

        public function rs_slider_settings_page(){
            if( ! current_user_can( 'manage_options' ) ){
                return;
            }

            if( isset( $_GET['settings-updated'] ) ){
                add_settings_error( 'rs_slider_options', 'rs_slider_message', esc_html__( 'Settings Saved', 'rs-slider' ), 'success' );
            }
            
            settings_errors( 'rs_slider_options' );

            require( RS_SLIDER_PATH . 'views/settings-page.php' );
        }

        public function register_scripts(){
            wp_register_script( 'rs-slider-main-jq', RS_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', array( 'jquery' ), RS_SLIDER_VERSION, true );
            wp_register_style( 'rs-slider-main-css', RS_SLIDER_URL . 'vendor/flexslider/flexslider.css', array(), RS_SLIDER_VERSION, 'all' );
            wp_register_style( 'rs-slider-style-css', RS_SLIDER_URL . 'assets/css/frontend.css', array(), RS_SLIDER_VERSION, 'all' );
        }

        public function register_admin_scripts(){
            global $typenow;
            if( $typenow == 'rs-slider'){
                wp_enqueue_style( 'rs-slider-admin', RS_SLIDER_URL . 'assets/css/admin.css' );
            }
        }

    }
}

if( class_exists( 'RS_Slider' ) ){
    register_activation_hook( __FILE__, array( 'RS_Slider', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'RS_Slider', 'deactivate' ) );
    register_uninstall_hook( __FILE__, array( 'RS_Slider', 'uninstall' ) );

    $rs_slider = new RS_Slider();
} 
