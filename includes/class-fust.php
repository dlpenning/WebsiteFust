<?php
/**
 * F.U.S.T. Website logic
 * 
 * @copyright Icarus Webservices
 * @package iws.fust
 */

define('FUST_THEME_DIR', get_template_directory());

class FUST
{
    public static $post_types = ['FUST_News', 'FUST_Service', 'FUST_Activity'];

    /**
     * Initialize the theme features
     * 
     * @since 0.2.0
     */
    public static function init()
    {
        if (is_array(self::$post_types)) foreach (self::$post_types as $type) {
            if (class_exists($type) && method_exists($type, 'setup')) {
                $type::setup();
            }
        }
    }

    /**
     * Adds meta boxes to all the post types
     * 
     * @since 0.2.1
     */
    public static function add_meta_boxes()
    {
        if( is_array( self::$post_types ) ) foreach (self::$post_types as $type) { 
            if( class_exists( $type ) && method_exists($type, 'register_metaboxes')) $type::register_metaboxes();
        }
    }

    /**
     * Save hook for custom metadata using meta boxes
     * 
     * @since 0.2.1
     */
    public static function save_post($post)
    {
        if (is_array(self::$post_types)) foreach (self::$post_types as $type) {
            if (class_exists($type) && method_exists($type, 'save_post')) $type::save_post($post);
        }
    }

    /**
     * Set up all the hooks for the theme
     * 
     * @since 0.2.0
     */
    public static function hooks()
    {
        // Initialize the theme features
        add_action('init', ['FUST', 'init']);
        add_action('add_meta_boxes', ['FUST', 'add_meta_boxes']);
        add_action('save_post', ['FUST', 'save_post']);

        // Add the FUST admin page
        add_action('admin_menu', function() {
            add_menu_page(
                'FUST Admin Page',
                'FUST',
                'administrator',
                'fust',
                function() {
                    include FUST_THEME_DIR . '/includes/admin/pages/fust.php';
                },
                '',
                2
            );
        });
    }
}