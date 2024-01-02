<?php
/**
 * The service CPT.
 * Stores services.
 * 
 * @since 0.2.1
 * 
 * @package iws.fust.CPTs
 */
class FUST_Service
{
    public static function setup()
    {
        register_post_type('service', ['public' => true, 'labels' => array(
                'name'               => __( 'Services',                   'fust' ),
                'singular_name'      => __( 'Service',                    'fust' ),
                'menu_name'          => __( 'Services',                   'fust' ),
                'name_admin_bar'     => __( 'Services',                   'fust' ),
                'add_new'            => __( 'Add New',                    'fust' ),
                'add_new_item'       => __( 'Add New Service',            'fust' ),
                'edit_item'          => __( 'Edit Service',               'fust' ),
                'new_item'           => __( 'New Service',                'fust' ),
                'view_item'          => __( 'View Service',               'fust' ),
                'search_items'       => __( 'Search Services',            'fust' ),
                'not_found'          => __( 'No services found',          'fust' ),
                'not_found_in_trash' => __( 'No services found in trash', 'fust' ),
                'all_items'          => __( 'All Services',               'fust' ),
            ),
            'menu_icon' => 'dashicons-button', 
            'supports' => array( 'title', 'editor', 'thumbnail'),
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => 'services',
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'menu_position' => 9,
            'rewrite' => array("slug" => "services", "with_front" => false)
        ]);
    }

    public static function register_metaboxes()
    {
        add_meta_box('fust_service_data_metabox', __('Service Data', 'fust'), array('FUST_Service', 'fust_service_data_metabox' ), 'service', 'advanced');
    }

    /**
	 * The metabox containing the event data, the date
	 * 
	 * @since 1.0.0
	 */
	public static function fust_service_data_metabox( $post )
	{
		wp_nonce_field( 'fust_save_service', 'fust_service_data_metabox_nonce' );

        $external_link = get_post_meta($post->ID, 'external_link', true);
        $icon = get_post_meta($post->ID, 'icon', true);


		?>
        <div class="service-data-metabox">
            <h3>Service visual configuration</h3>
            <hr>
            <p>
                <label for="fust_icon"><b>Icon:</b></label>
                <input type="text" name="fust_icon" autocomplete="off" value="<?= $icon ?>" />
            </p>
            <h3>External service configuration</h3>
            <hr>
            <p>
                <label for="fust_external_link"><b>External link:</b></label>
                <input type="text" name="fust_external_link" autocomplete="off" value="<?= $external_link ?>" />
                <i>Leave empty for an interal service which can be used on this website.</i>
            </p>

            <style>
                .service-data-metabox label {
                    display: block;
                }

                .service-data-metabox input[type="text"] {
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
		if (!wp_verify_nonce($_POST['fust_service_data_metabox_nonce'], 'fust_save_service' ) ) return;
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
