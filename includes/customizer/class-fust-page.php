<?php
/**
 * Represents a page template within the customizer
 * 
 * @since 0.1.0
 */
class FUST_Customizer_Page
{

    /**
     * The template ID of the file
     * 
     * @var string
     * 
     * @since 0.1.0
     */
    public $template_id = '';

    /**
     * The custom name of the page
     * 
     * @var string
     * 
     * @since 0.1.0
     */
    public $custom_name = '';

    /**
     * The ID of the page type (Must be made up)
     * 
     * @var string
     * 
     * @since 0.1.0
     */
    public $page_id;

    /**
     * The default sections.
     * 
     * @var array
     * 
     * @since 0.1.0
     */
    public $default_sections = [];

    /**
     * The check function checking for the right context
     * 
     * @var callback
     * 
     * @since 0.1.0
     */
    public $check_function;

    /**
     * @param string $template_id   The filename of the template to use with the customizer
     * 
     * @param string $page_id       The ID of the page (Must be unique and made up).
     * 
     * @param string $custom_name   The name of the page to be used within the customizer
     * 
     * @param callback $check_function  (Options) Custom callback for context scanning, used on front page.
     *                                  Defaults to the page_template check callback
     * 
     * @return self
     */
    public function __construct( $template_id, $page_id, $custom_name, $check_function = null )
    {
        // Set the template ID
        $this->template_id = $template_id;

        // Set the custom name
        $this->custom_name = $custom_name;

        // Set the page ID
        $this->page_id = $page_id;

        $this->check_function = $check_function;
    }

    /**
     * Gets the sections from the options
     * 
     * @since 0.1.0
     * 
     * @return array
     */
    public function get_sections()
    {

        $o = $this->default_sections;

        return $o;

    }

    /**
     * The default check function
     * 
     * @since 0.1.0
     */
    public function default_check_function()
    {
        return is_page_template($this->template_id);
    }

    /**
     * Sets up the customizer for this page
     * 
     * @param WP_Customizer $wp_customizer  The instance of the customizer.
     * 
     * @return void
     */
    public function add_to_customizer( $wp_customize )
    {   
        $sections = $this->get_sections();

        // var_dump( $sections );

        if( ! $this->check_function )
        {
            $function = array( $this, 'default_check_function' );
        } else $function = $this->check_function;

        if( count( $sections ) > 0 ) {
            $components = [];
            $default_sections = [];

            foreach ($sections as $section) {
            
                if( isset( $section['type'] ) )
                {
                    $type = $section['type'];

                    switch( $type )
                    {
                        case 'component':
                            array_push($components, $section);
                        break;

                        case 'default':
                            array_push($default_sections, $section);
                        break;

                        default:
                            // Do nothing
                        break;
                    }
                }

            }

            if( count( $default_sections ) > 0 )
            {
                // Add the section
                $wp_customize->add_section('fust_autopage_' . $this->page_id, array(
                    'title'    => __('(FUST) '. $this->custom_name . ' pagina aanpassen', 'fust'),
                    'priority' => 2,
                    'description' => 'Opties voor de ' . $this->custom_name,
                    'active_callback' => $function
                ));

                $i = 0;

                foreach ($default_sections as $sect) {

                    $sect_type = isset( $sect['s'] ) ? $sect['s'] : 'p';
                    $instance = isset( $sect['i'] ) ? $sect['i'] : 0;
                    $default = isset( $sect['default'] ) ? $sect['default'] : null;

                    switch( $sect_type )
                    {
                        
                        // Paragraph type
                        case 'p':

                            $wp_customize->add_setting('def_seperator_' . $i . '_' . $this->page_id, array(
                                'default'   => null,
                                'capability' => 'edit_theme_options',
                                'type' => 'option'
                            ));

                            $wp_customize->add_control( new Seperator_Custom_Control($wp_customize, 'def_seperator_' . $i . '_' . $this->page_id . '_control', array(
                                'label'    => __('', 'fust'),
                                'section'  => 'fust_autopage_' . $this->page_id,
                                'settings' => 'def_seperator_' . $i . '_' . $this->page_id,
                                'display' => 'Paragraaf ' . ( $i + 1 )
                            )));

                            $wp_customize->add_setting('fust_page_' . $this->page_id . '_par_' . $instance . '_title', array(
                                'default'   => isset( $default['title'] ) ? $default['title'] : '[Enter Text]',
                                'capability' => 'edit_theme_options',
                                'type' => 'option'
                            ));
                    
                            $wp_customize->add_control('fust_page_' . $this->page_id . '_par_' . $instance . '_title_control', array(
                                'label'      => __('Paragraaf ' . $instance . ' titel', 'fust'),
                                'section'    => 'fust_autopage_' . $this->page_id,
                                'settings'   => 'fust_page_' . $this->page_id . '_par_' . $instance . '_title',
                            ));

                            $wp_customize->add_setting('fust_page_' . $this->page_id . '_par_' . $instance . '_text', array(
                                'default'   => isset( $default['text'] ) ? $default['text'] : '[Enter Text]',
                                'capability' => 'edit_theme_options',
                                'type' => 'option',
                                'transport' => 'postMessage'
                            ));
                            
                            $wp_customize->add_control( new Text_Editor_Custom_Control( $wp_customize, 'fust_page_' . $this->page_id . '_par_' . $instance . '_text_control', array(
                                'label'      => "Paragraaf " . $instance . ' tekst',
                                'section'    => 'fust_autopage_' . $this->page_id,
                                'settings'   => 'fust_page_' . $this->page_id . '_par_' . $instance . '_text',
                            )));

                            // $wp_customize->add_control('fust_page_' . $this->page_id . '_par_' . $instance . '_text_control', array(
                            //     'label'      => "Paragraaf " . $instance . ' tekst',
                            //     'section'    => 'fust_autopage_' . $this->page_id,
                            //     'settings'   => 'fust_page_' . $this->page_id . '_par_' . $instance . '_text',
                            //     'type' => 'textarea'
                            // ));

                            // Selective Refreshes
                            $wp_customize->selective_refresh->add_partial('fust_page_' . $this->page_id . '_par_' . $instance . '_title_refresh', array(
                                'selector' => 'h1#fust_page_' . $this->page_id . '_par_' . $instance . '_title', // You can also select a css class
                                'settings' => array( 'fust_page_' . $this->page_id . '_par_' . $instance . '_title' ),
                                'render_callback' => function() { 
                                    global $instance;
                                    echo get_option('fust_page_' . $this->page_id . '_par_' . $instance . '_title'); 
                                }
                            ));

                            // $wp_customize->selective_refresh->add_partial('fust_page_' . $this->page_id . '_par_' . $instance . '_text_refresh', array(
                            //     'selector' => 'p#fust_page_' . $this->page_id . '_par_' . $instance . '_text', // You can also select a css class
                            //     'settings' => array( 'fust_page_' . $this->page_id . '_par_' . $instance . '_text' ),
                            //     'render_callback' => function() { 
                            //         global $instance;
                            //         echo get_option('fust_page_' . $this->page_id . '_par_' . $instance . '_text'); 
                            //     }
                            // ));
                        break;
                    }

                    $i++;

                }

                
            }

        }
        
    }

    /**
     * Generates the HTML for the webpage
     * 
     * @since 0.1.0
     */
    public function render()
    {
        $sections = $this->get_sections();

        foreach ($sections as $s) {
            
            $type = isset( $s['type'] ) ? $s['type'] : 'default';

            switch( $type )
            {

                case 'default':

                    $t = isset( $s['s'] ) ? $s['s'] : 'p';
                    $i = isset( $s['i'] ) ? $s['i'] : -1;
                    $d = isset( $s['default'] ) ? $s['default'] : null;

                    switch( $t )
                    {

                        case 'p':

                            ?>
                            <section>
                                <div class="section-content">
                                    <h1 class="title" id="<?= 'fust_page_' . $this->page_id . '_par_' . $i . '_title' ?>"><?= get_option('fust_page_' . $this->page_id . '_par_' . $i . '_title', isset( $d['title'] ) ? $d['title'] : 'Edit in customizer!'); ?></h1>
                                    <br>
                                    <p id="<?= 'fust_page_' . $this->page_id . '_par_' . $i . '_text' ?>"><?= get_option('fust_page_' . $this->page_id . '_par_' . $i . '_text', isset( $d['text'] ) ? $d['text'] : 'Edit in customizer!') ?></p>
                                </div>
                            </section>
                            <?php

                        break;

                    }

                break;

                case 'component':

                    $name = isset( $s['name'] ) ? $s['name'] : 'default';

                    if( file_exists( FUST_THEME_DIR . '/includes/customizer/components/' . $name . '.php' ) )
                    {

                        include_once FUST_THEME_DIR . '/includes/customizer/components/' . $name . '.php';

                        $c = 'FUST_Components_' . $name;

                        if( class_exists( $c ) )
                        {
                            if( method_exists($c, 'render') )
                            {
                                $c::render();
                            }
                        }
                    }

                break;

            }

        }

    }

}