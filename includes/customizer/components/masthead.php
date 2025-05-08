<?php

/**
 * The about me short component class.
 * 
 * (Documentation:)
 * Every component class must have the following naming scheme FUST_Components_{filename (no .php)}, 
 * otherwise FUST won't recognize the class.
 * 
 * The class must have two STATIC methods (they MUST be declared static, otherwise it won't work),
 * - customizer ( $wp_customize, $section_id ), adds customizer controls to the section (through the ID)
 * - render () 
 */

// Always check for class existence
if( ! class_exists( 'FUST_Components_masthead' ) ):
class FUST_Components_masthead
{

    /**
     * The display name for the element
     * 
     * @var string
     * 
     * @static
     * 
     * @since 0.1.0
     */
    public static $display = 'Banner';

    /**
     * Set up the customizer settings & controls for the element.
     * 
     * @param WP_Customize $wp_customize    The WP Customizer instance.
     * @param string       $section_id      The ID of the section.
     * 
     * @since 0.1.0
     */
    public static function customizer( $wp_customize, $active_callback = '' )
    {
        // Banner title
        $wp_customize->add_setting('fust_masthead_title', array(
            'default'   => 'More than just your student union.',
            'capability' => 'edit_theme_options',
            'type' => 'option',
            'active_callback' => $active_callback
        ));

        $wp_customize->add_control('fust_masthead_title_text', array(
            'label'      => __('Banner title', 'fust'),
            'section'    => 'fust_components',
            'settings'   => 'fust_masthead_title',
            'active_callback' => $active_callback
        ));

        // Banner subtitle
        $wp_customize->add_setting('fust_masthead_subtitle', array(
            'default'   => 'Representing all students that study and/or live in Tilburg. By involving not only the students of Tilburg but also the associations focused on students we ensure that Tilburg will live up to all its potential as a student city. F.U.S.T. as a union ensures that there is always a place where students feel safe to voice their opinion and be themselves.',
            'capability' => 'edit_theme_options',
            'type' => 'option',
            'active_callback' => $active_callback
        ));

        $wp_customize->add_control('fust_masthead_subtitle_text', array(
            'label'      => __('Banner subtitle', 'fust'),
            'section'    => 'fust_components',
            'settings'   => 'fust_masthead_subtitle',
            'type' => 'textarea',
            'active_callback' => $active_callback
        ));

        // Banner picture
        $wp_customize->add_setting('fust_masthead_picture', array(
            'default'           => 'https://placehold.co/800x600?text=FUST&font=Open+Sans',
            'capability'        => 'edit_theme_options',
            'type'           => 'option',
            'active_callback' => $active_callback
    
        ));
    
        $wp_customize->add_control( new WP_Customize_Image_Control($wp_customize, 'fust_masthead_picture_control', array(
            'label'    => __('Banner picture', 'fust'),
            'section'  => 'fust_components',
            'settings' => 'fust_masthead_picture',
            'active_callback' => $active_callback
        )));

        // Banner button page
        $wp_customize->add_setting('fust_masthead_cta_page', array(
            'default'   => '',
            'capability' => 'edit_theme_options',
            'type' => 'option',
            'active_callback' => $active_callback
        ));

        $wp_customize->add_control('fust_masthead_cta_page_control', array(
            'label'      => __('Banner button page', 'fust'),
            'section'    => 'fust_components',
            'settings'   => 'fust_masthead_cta_page',
            'type'       => 'dropdown-pages',
            'active_callback' => $active_callback
        ));

        // Banner button text
        $wp_customize->add_setting('fust_masthead_cta_button_text', array(
            'default'   => 'Join us now',
            'capability' => 'edit_theme_options',
            'type' => 'option',
            'active_callback' => $active_callback
        ));

        $wp_customize->add_control('fust_masthead_cta_button_text_control', array(
            'label'      => __('Banner button text', 'fust'),
            'section'    => 'fust_components',
            'settings'   => 'fust_masthead_cta_button_text',
            'active_callback' => $active_callback
        ));


        // "Selective refresh": Show the edit icons
        $wp_customize->selective_refresh->add_partial('fust_refresh_masthead_title', array(
            'selector' => 'h1.masthead-title',
            'settings' => array( 'fust_masthead_title' ),
            'render_callback' => function() { echo get_option('fust_masthead_title'); },
            'active_callback' => $active_callback
        ));

        $wp_customize->selective_refresh->add_partial('fust_refresh_masthead_subtitle', array(
            'selector' => 'p.masthead-subtitle',
            'settings' => array( 'fust_masthead_subtitle' ),
            'render_callback' => function() { echo get_option('fust_masthead_subtitle'); },
            'active_callback' => $active_callback
        ));

        $wp_customize->selective_refresh->add_partial('fust_refresh_masthead_picture', array(
            'selector' => 'div.masthead-figure',
            'settings' => array( 'fust_masthead_picture' ),
            'render_callback' => function() { echo get_option('fust_masthead_picture'); },
            'active_callback' => $active_callback
        ));

        $wp_customize->selective_refresh->add_partial('fust_refresh_masthead_cta_text', array(
            'selector' => '#masthead-cta-button',
            'settings' => array( 'fust_masthead_cta_button_text' ),
            'render_callback' => function() { echo get_option('fust_masthead_cta_button_text'); },
            'active_callback' => $active_callback
        ));
    }

    /**
     * Render the events
     * 
     * @since 0.1.0
     */
    public static function render()
    {
        ?>
        <section class="masthead">
            <div class="section-content">
                <div class="masthead-content">
                    <h1 class="title masthead-title"><?= get_option('fust_masthead_title', 'More than just your student union.'); ?></h1>
                    <p class="masthead-subtitle">
                    <?= get_option('fust_masthead_subtitle', 'Representing all students that study and/or live in Tilburg. By involving not only the students of Tilburg but also the associations focused on students we ensure that Tilburg will live up to all its potential as a student city. F.U.S.T. as a union ensures that there is always a place where students feel safe to voice their opinion and be themselves.'); ?>
                    </p>
                    <div><a id="masthead-cta-button" href="<?= get_the_permalink(get_option('fust_masthead_cta_page')) ?>" class="button outline white"><?= get_option('fust_masthead_cta_button_text', 'Join us now')?></a></div>
                </div>
                <div class="masthead-figure">
                    <img src="<?= get_option('fust_masthead_picture', 'https://placehold.co/800x600?text=FUST&font=Open+Sans') ?>" alt="">
                    <div class="graphic"></div>
                </div>
            </div>
            <svg class="masthead-transition" width="1920" height="57" viewBox="0 0 1920 57" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0L1920 55H0V0Z" fill="white" fill-opacity="0.3"/>
                <path d="M0 17L1920 57H0V17Z" fill="white"/>
            </svg>
        </section>
        <?php
    }

}

endif;