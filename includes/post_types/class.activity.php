<?php
/**
 * The activity CPT.
 * Stores activities.
 * 
 * @since 0.2.2
 * 
 * @package iws.fust.CPTs
 */
class FUST_Activity
{
    public static function setup()
    {
        register_post_type('activity', ['public' => true, 'labels' => array(
                'name'               => __( 'Activities',                   'fust' ),
                'singular_name'      => __( 'Activity',                     'fust' ),
                'menu_name'          => __( 'Activities',                   'fust' ),
                'name_admin_bar'     => __( 'Activities',                   'fust' ),
                'add_new'            => __( 'Add New',                      'fust' ),
                'add_new_item'       => __( 'Add New Activity',             'fust' ),
                'edit_item'          => __( 'Edit Activity',                'fust' ),
                'new_item'           => __( 'New Activity',                 'fust' ),
                'view_item'          => __( 'View Activity',                'fust' ),
                'search_items'       => __( 'Search Activities',            'fust' ),
                'not_found'          => __( 'No activities found',          'fust' ),
                'not_found_in_trash' => __( 'No activities found in trash', 'fust' ),
                'all_items'          => __( 'All Activities',               'fust' ),
            ),
            'menu_icon' => 'dashicons-calendar-alt', 
            'supports' => array( 'title', 'editor', 'thumbnail'),
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => 'activities',
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 10,
            'rewrite' => array("slug" => "activities", "with_front" => false)
        ]);
    }

    public static function register_metaboxes()
    {
        add_meta_box('fust_activity_data_metabox', __('Activity Data', 'fust'), array('FUST_Activity', 'fust_activity_data_metabox' ), 'activity', 'advanced');
    }

    /**
	 * The metabox containing the event data, the date
	 * 
	 * @since 1.0.0
	 */
	public static function fust_activity_data_metabox( $post )
	{
		wp_nonce_field( 'fust_save_activity', 'fust_activity_data_metabox_nonce' );

        $external_link = get_post_meta($post->ID, 'external_link', true);
        $icon = get_post_meta($post->ID, 'icon', true);


		?>
        <div class="activity-data-metabox">
            <p>
                <label for="fust_icon"><b>Icon:</b></label>
                <input type="text" name="fust_icon" autocomplete="off" value="<?= $icon ?>" />
            </p>

            <style>
                .activity-data-metabox label {
                    display: block;
                }

                .activity-data-metabox input[type="text"] {
                    margin: 6px auto;
                    width: 100%;
                }
            </style>
        </div>
        <?php
	}

    public static function save_post( $post )
	{
        # Sanity checks
		if (!wp_verify_nonce($_POST['fust_activity_data_metabox_nonce'], 'fust_save_activity' ) ) return;
		if (wp_is_post_autosave($post)) return;

        # Save the meta fields
		if (isset($_POST['fust_external_link'])) {
            update_post_meta($post, 'external_link', sanitize_text_field( $_POST['fust_external_link']));
        }

        if (isset($_POST['fust_icon'])) {
            update_post_meta($post, 'icon', sanitize_text_field( $_POST['fust_icon']));
        }
	}
}
