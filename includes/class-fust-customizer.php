<?php
/**
 * Adds support for the customizer
 * 
 * @package iws.fust
 */
class FUST_Customizer
{

    /**
     * Setup customizer hook
     * 
     * @since 1.0.0
     */
    public static function setup_customizer( $wp_customize )
    {
        // self::general_info( $wp_customize );
        // self::front_page_options( $wp_customize );
        self::components_options( $wp_customize );
        
        global $registered_pages;

        foreach ($registered_pages as $page) {

            $page->add_to_customizer( $wp_customize );
        }

    }

    // public static function general_info( $wp_customize )
    // {
    //     // Sections
    //     $wp_customize->add_section('fust_general_options', array(
    //         'title'    => __('(FUST) Algemene Opties', 'fust'),
    //         'priority' => 1,
    //         'description' => 'Algemene opties voor de FUST website'
    //     ));


    //     // Navbar logo
    //     $wp_customize->add_setting('fust_navbar_logo', array(
    //         'default'    => get_template_directory_uri() . '/img/logo_wim_steenbakker.png',
    //         'capability' => 'edit_theme_options',
    //         'type'       => 'option'
    //     ));

    //     $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'fust_navbar_logo_control', array(
    //         'label'      => __('Navigatiebalk logo', 'fust'),
    //         'section'    => 'fust_general_options',
    //         'settings'   => 'fust_navbar_logo',
    //     )));

    //     // Email adress
    //     $wp_customize->add_setting('fust_email', array(
    //         'default'   => 'info@wimsteenbakker.nl',
    //         'capability' => 'edit_theme_options',
    //         'type' => 'option'
    //     ));

    //     $wp_customize->add_control('fust_email_text', array(
    //         'label'      => __('E-mail adress', 'fust'),
    //         'section'    => 'fust_general_options',
    //         'settings'   => 'fust_email',
    //     ));

    //     // Phone Number
    //     $wp_customize->add_setting('fust_phone', array(
    //         'default'   => '+31 6 4179 8895',
    //         'capability' => 'edit_theme_options',
    //         'type' => 'option'
    //     ));

    //     $wp_customize->add_control('fust_phone_text', array(
    //         'label'      => __('Telefoonnummer', 'fust'),
    //         'section'    => 'fust_general_options',
    //         'settings'   => 'fust_phone',
    //     ));

    //     // Header color opaque
    //     $wp_customize->add_setting('fust_header_color_opaque', array(
    //             'default'           => '#ffffff',
    //             'sanitize_callback' => 'sanitize_hex_color',
    //             'capability'        => 'edit_theme_options',
    //             'type'           => 'option',
        
    //         ));
        
    //     $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'fust_header_opaque_color_control', array(
    //         'label'    => __('Header kleur (doorzichtig)', 'fust'),
    //         'section'  => 'fust_general_options',
    //         'settings' => 'fust_header_color_opaque',
    //     )));

    //     // Header color non-opaqye
    //     $wp_customize->add_setting('fust_header_color', array(
    //         'default'           => '#ffffff',
    //         'sanitize_callback' => 'sanitize_hex_color',
    //         'capability'        => 'edit_theme_options',
    //         'type'           => 'option',
    
    //     ));
    
    //     $wp_customize->add_control( new WP_Customize_Color_Control($wp_customize, 'fust_header_color_control', array(
    //         'label'    => __('Header kleur', 'fust'),
    //         'section'  => 'fust_general_options',
    //         'settings' => 'fust_header_color',
    //     )));


    //     // News cards design selector
    //     $wp_customize->add_setting('fust_news_design', array(
    //         'default'    => 'default',
    //         'capability' => 'edit_theme_options',
    //         'type'       => 'option',
    
    //     ));
    
    //     $wp_customize->add_control('fust_news_design_control', array(
    //         'label'      => __('News design', 'fust'),
    //         'section'    => 'fust_general_options',
    //         'settings'   => 'fust_news_design',
    //         'type'       => 'select',
    //         'choices' => array(
    //             'default' => __( 'Polaroid' ),
    //             'pola_simple' => __( 'Polaroid Simple' ),
    //             'simple' => __( 'Simple' ),
    //             'wide' => __( 'Wide' ),
    //         ),
    //     ));


    //     // Footer Text
    //     $wp_customize->add_setting('fust_footer', array(
    //         'default'   => 'Voor meer informatie, neem contact op met FUST.',
    //         'capability' => 'edit_theme_options',
    //         'type' => 'option'
    //     ));

    //     $wp_customize->add_control('fust_footer_text', array(
    //         'label'      => __('Footer Info Text', 'fust'),
    //         'section'    => 'fust_general_options',
    //         'settings'   => 'fust_footer'
    //     ));

    //     // ==========================
    //     // Selective Refreshes
    //     // ==========================

    //     // Email partial
    //     $wp_customize->selective_refresh->add_partial('fust_refresh_email_footer', array(
    //         'selector' => 'span#footer-email', // You can also select a css class
    //         'settings' => array( 'fust_email' ),
    //         'render_callback' => function() { echo get_option('fust_email'); }
    //     ));

    //     $wp_customize->selective_refresh->add_partial('fust_refresh_phone_footer', array(
    //         'selector' => 'span#phone', // You can also select a css class
    //         'settings' => array( 'fust_phone' ),
    //         'render_callback' => function() { echo get_option('fust_phone'); }
    //     ));

    //     // Footer Info
    //     // $wp_customize->selective_refresh->add_partial('fust_refresh_footer_info_text', array(
    //     //     'selector' => 'p#footerinfo', // You can also select a css class
    //     //     'settings' => array( 'fust_footer' ),
    //     //     'render_callback' => function() { echo get_option('fust_footer'); }
    //     // ));

    // } 

    public static function components_options( $wp_customize )
    {
        
        $wp_customize->add_section('fust_components', array(
            'title'    => __('[FUST] Edit components', 'fust'),
            'priority' => 2,
            'description' => 'Edit the main components.'
        ));

        // Good-old components
        foreach (glob( FUST_THEME_DIR . '/includes/customizer/components/*' ) as $file) {
                
            $cm = explode('/', $file);

            if( count($cm) > 0 )
            {

                $filer = $cm[ count( $cm ) - 1 ];

                $filen = explode('.', $filer);

                if( count( $filen ) > 0 )
                {
                    $c_name = $filen[0];

                    include_once $file;

                    $c = 'FUST_Components_' . $c_name;

                    if( class_exists( $c ) )
                    {

                        if( method_exists($c, 'customizer') )
                        {

                            $display = $c_name;

                            if( property_exists($c, 'display') )
                            {
                                $display = $c::$display;
                            }

                            $wp_customize->add_setting('def_seperator_' . $c_name, array(
                                'default'   => null,
                                'capability' => 'edit_theme_options',
                                'type' => 'option'
                            ));

                            $wp_customize->add_control( new Seperator_Custom_Control($wp_customize, 'def_seperator_' . $c_name . '_control', array(
                                'label'    => __('', 'fust'),
                                'section'  => 'fust_components',
                                'settings' => 'def_seperator_' . $c_name,
                                'display' => $display
                            )));
                            // echo $c_name;
                            $c::customizer( $wp_customize, '' );
                        }
                    }
                    
                }

            }

        }

           

    }

}