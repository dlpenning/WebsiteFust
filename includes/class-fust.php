<?php
/**
 * F.U.S.T. Website logic
 * 
 * @copyright Icarus Webservices
 * @version 0.2.0
 * @package iws.fust
 */

define('FUST_THEME_DIR', get_template_directory());

class FUST
{
    public static $post_types = ['FUST_News'];

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
     * Set up all the hooks for the theme
     * 
     * @since 0.2.0
     */
    public static function hooks()
    {
        // Initialize the theme features
        add_action('init', ['FUST', 'init']);

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