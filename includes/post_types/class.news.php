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
            'supports' => array( 'title', 'editor', 'thumbnail'),
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

    public static function register_metaboxes()
    {
        add_meta_box('fust_news_data_metabox', __('News Data', 'fust'), array('FUST_News', 'fust_news_data_metabox' ), 'news', 'advanced');
    }

    /**
	 * The metabox containing the event data, the date
	 * 
	 * @since 1.0.0
	 */
	public static function fust_news_data_metabox( $post )
	{
		wp_nonce_field('fust_save_news', 'fust_news_data_metabox_nonce');

		$external_link = get_post_meta($post->ID, 'external_link', true);

		?>
        <div class="news-data-metabox">
            <h3>External news article configuration</h3>
            <hr>
            <p>
                <label for="fust_external_link"><b>External link:</b></label>
                <input type="text" name="fust_external_link" autocomplete="off" value="<?= $external_link ?>" />
                <i>Leave empty for an interal news article which can be read on this website.</i>
            </p>

            <style>
                .news-data-metabox label {
                    display: block;
                }

                .news-data-metabox input[type="text"] {
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
		if (!wp_verify_nonce($_POST['fust_news_data_metabox_nonce'], 'fust_save_news' ) ) return;
		if (wp_is_post_autosave($post)) return;

        # Save the meta field fust_external_link
		if (isset($_POST['fust_external_link'])) {
            update_post_meta($post, 'external_link', sanitize_text_field( $_POST['fust_external_link']));
        }
	}
}
