<?php 

if( ! class_exists( 'RS_Slider_Settings' )){
    class RS_Slider_Settings{

        public static $options;

        public function __construct(){
            self::$options = get_option( 'rs_slider_options' );
            add_action( 'admin_init', array( $this, 'admin_init') );
        }

        public function admin_init(){
            
            register_setting( 'rs_slider_group', 'rs_slider_options', array( $this, 'rs_slider_validate' ) );

            add_settings_section(
                'rs_slider_main_section',
                esc_html__( 'How does it work?', 'rs-slider' ),
                null,
                'rs_slider_page1'
            );

            add_settings_section(
                'rs_slider_second_section',
                esc_html__( 'Other Plugin Options', 'rs-slider' ),
                null,
                'rs_slider_page2'
            );

            add_settings_field(
                'rs_slider_shortcode',
                esc_html__( 'Shortcode', 'rs-slider' ),
                array( $this, 'rs_slider_shortcode_callback' ),
                'rs_slider_page1',
                'rs_slider_main_section'
            );

            add_settings_field(
                'rs_slider_title',
                esc_html__( 'Slider Title', 'rs-slider' ),
                array( $this, 'rs_slider_title_callback' ),
                'rs_slider_page2',
                'rs_slider_second_section',
                array(
                    'label_for' => 'rs_slider_title'
                )
            );

            add_settings_field(
                'rs_slider_bullets',
                esc_html__( 'Display Bullets', 'rs-slider' ),
                array( $this, 'rs_slider_bullets_callback' ),
                'rs_slider_page2',
                'rs_slider_second_section',
                array(
                    'label_for' => 'rs_slider_bullets'
                )
            );

            add_settings_field(
                'rs_slider_style',
                esc_html__( 'Slider Style', 'rs-slider' ),
                array( $this, 'rs_slider_style_callback' ),
                'rs_slider_page2',
                'rs_slider_second_section',
                array(
                    'items' => array(
                        'style-1',
                        'style-2'
                    ),
                    'label_for' => 'rs_slider_style'
                )
                
            );
        }

        public function rs_slider_shortcode_callback(){
            ?>
            <span><?php esc_html_e( 'Use the shortcode [rs_slider] to display the slider in any page/post/widget', 'rs-slider' ); ?></span>
            <?php
        }

        public function rs_slider_title_callback( $args ){
            ?>
                <input 
                type="text" 
                name="rs_slider_options[mv_slider_title]" 
                id="rs_slider_title"
                value="<?php echo isset( self::$options['rs_slider_title'] ) ? esc_attr( self::$options['rs_slider_title'] ) : ''; ?>"
                >
            <?php
        }
        
        public function rs_slider_bullets_callback( $args ){
            ?>
                <input 
                    type="checkbox"
                    name="rs_slider_options[rs_slider_bullets]"
                    id="rs_slider_bullets"
                    value="1"
                    <?php 
                        if( isset( self::$options['rs_slider_bullets'] ) ){
                            checked( "1", self::$options['rs_slider_bullets'], true );
                        }    
                    ?>
                />
                <label for="rs_slider_bullets"><?php esc_html_e( 'Whether to display bullets or not', 'rs-slider' ); ?></label>
                
            <?php
        }

        public function rs_slider_style_callback( $args ){
            ?>
            <select 
                id="rs_slider_style" 
                name="rs_slider_options[mv_slider_style]">
                <?php 
                foreach( $args['items'] as $item ):
                ?>
                    <option value="<?php echo esc_attr( $item ); ?>" 
                        <?php 
                        isset( self::$options['rs_slider_style'] ) ? selected( $item, self::$options['rs_slider_style'], true ) : ''; 
                        ?>
                    >
                        <?php echo esc_html( ucfirst( $item ) ); ?>
                    </option>                
                <?php endforeach; ?>
            </select>
            <?php
        }

        public function rs_slider_validate( $input ){
            $new_input = array();
            foreach( $input as $key => $value ){
                switch ($key){
                    case 'rs_slider_title':
                        if( empty( $value )){
                            add_settings_error( 'rs_slider_options', 'rs_slider_message', esc_html__( 'The title field can not be left empty', 'rs-slider' ), 'error' );
                            $value = esc_html__( 'Please, type some text', 'rs-slider' );
                        }
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                    default:
                        $new_input[$key] = sanitize_text_field( $value );
                    break;
                }
            }
            return $new_input;
        }

    }
}

