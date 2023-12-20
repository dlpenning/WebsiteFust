<?php
/**
 * The news CPT.
 * Stores news items.
 * 
 * @since 0.2.0
 * 
 * @package iws.fust.CPTs
 */
class FUST_News
{
    public static function setup()
    {
      	register_post_type('news', ['public' => true, 'labels' => array(
						'name'               => __( 'News',                    'fust' ),
						'singular_name'      => __( 'News',                    'fust' ),
						'menu_name'          => __( 'News',                    'fust' ),
						'name_admin_bar'     => __( 'News',                    'fust' ),
						'add_new_item'       => __( 'Add New News Item',       'fust' ),
						'edit_item'          => __( 'Edit Item',               'fust' ),
						'add_new'            => __( 'Add New',                 'fust' ),
						'new_item'           => __( 'New Item',                'fust' ),
						'view_item'          => __( 'View Item',               'fust' ),
						'search_items'       => __( 'Search Items',            'fust' ),
						'not_found'          => __( 'No items found',          'fust' ),
						'not_found_in_trash' => __( 'No items found in trash', 'fust' ),
						'all_items'          => __( 'All News Items',          'fust' ),
					), 	
					'menu_icon' => 'dashicons-desktop', 
					'supports' => array( 'title', 'editor', 'thumbnail', 'custom-fields'),
					'publicly_queryable' => true,
					'show_ui' => true, 
					'show_in_menu' => true, 
					'show_in_nav_menus' => true,
					'query_var' => true,
					'rewrite' => 'news',
					'capability_type' => 'post',
					'has_archive' => true, 	
					'hierarchical' => false,
					'menu_position' => 8,
					'rewrite' => array("slug" => "news", "with_front" => false)
				]);
    }
}
