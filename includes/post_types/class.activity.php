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

    public static function parse_tags($tagsString) {
        // Split the tags string into an array using the comma as a delimiter
        $tagsArray = explode(',', $tagsString);
    
        // Trim any leading or trailing whitespace from each tag
        $tagsArray = array_map('trim', $tagsArray);
    
        return $tagsArray;
    }

    /**
	 * The metabox containing the event data, the date
	 * 
	 * @since 1.0.0
	 */
	public static function fust_activity_data_metabox( $post )
	{
		wp_nonce_field( 'fust_save_activity', 'fust_activity_data_metabox_nonce' );

        $location = get_post_meta($post->ID, 'location', true);
        $date = get_post_meta($post->ID, 'date', true);
        $tags = get_post_meta($post->ID, 'tags', true);
        
        $start_hours = get_post_meta( $post->ID, 'start_hours', true );
		$start_minutes = get_post_meta( $post->ID, 'start_minutes', true );
		$end_hours = get_post_meta( $post->ID, 'end_hours', true );
		$end_minutes = get_post_meta( $post->ID, 'end_minutes', true );


		?>
        <div class="activity-data-metabox">
            <div class="field">
                <label for="fust_location"><b>Location:</b></label>
                <input type="text" name="fust_location" autocomplete="off" value="<?= $location ?>" />
            </div>
            <div class="field">
                <label for="fust_tags">Tags: </label>
                <input type="text" name="fust_tags" id="fust_tags" autocomplete="off" value="<?= $tags ?>" />
            </div>
            <div class="field">
                <label for="fust_date">Date: </label>
                <input id="fust_date" type="text" name="fust_date" autocomplete="off" value="<?= $date ?>">
            </div>
            <div id="timecontrol" style="margin-bottom: 20px;">
                <table>
                    <tr>
                        <td>Start time: </td>
                        <td><select name="fust_time_start_hours"><?php for ($i=0; $i < 24; $i++) {  ?><option value="<?= ($i) ?>" <?php if( $i == $start_hours ) echo 'selected'; ?>><?php

                                        if( $i < 10 ) echo '0' . ($i);
                                        else echo ($i);

                                    ?></option> <?php
                                } ?></select> : <select name="fust_time_start_minutes"><?php for ($i=0; $i < 60; $i++) { 
                                    ?> <option value="<?= ($i) ?>" <?php if( $i == $start_minutes ) echo 'selected'; ?>><?php

                                        if( $i < 10 ) echo '0' . ($i);
                                        else echo ($i);

                                    ?></option> <?php
                            } ?></select>
                        </td>
                    </tr>
                    <tr style="margin-right: 20px;">
                        <td class="end_time">End time: </td>
                        <td class="end_time"><select name="fust_time_end_hours"><?php for ($i=0; $i < 24; $i++) { 
                                    ?> <option value="<?= ($i) ?>" <?php if( $i == $end_hours ) echo 'selected'; ?>><?php

                                        if( $i < 10 ) echo '0' . ($i);
                                        else echo ($i);

                                    ?></option> <?php
                                } ?></select> :
                            <select name="fust_time_end_minutes"><?php for ($i=0; $i < 60; $i++) { 
                                    ?> <option value="<?= ($i) ?>" <?php if( $i == $end_minutes ) echo 'selected'; ?>><?php

                                        if( $i < 10 ) echo '0' . ($i);
                                        else echo ($i);

                                    ?></option> <?php
                            } ?></select></td>
                        <td>
                        </td>
                    </tr>
                </table>
            </div>

            <style>
                .activity-data-metabox label {
                    display: block;
                }

                .activity-data-metabox input[type="text"] {
                    margin-top: 6px;
                    width: 100%;
                }

                .activity-data-metabox .field {
                    position: relative;
                    margin: 1em 0;
                }

                .activity-data-metabox .field input.error {
                    border-color: #f00;
                }

                .fust-tags-dropdown {
                    position: absolute;
                    top: 100%;
                    left: 0;
                    display: flex;
                    width: 100%;
                    margin-top: 6px;
                    border: 1px solid #8c8f94;
                    flex-direction: column;
                    border-radius: 4px;
                    /*display: none;*/
                    z-index: 5;
                }

                .fust-tags-dropdown-item {
                    padding: 6px 1rem;
                    background-color: #fff;
                }

                .fust-tags-dropdown-item:hover {
                    background-color: #f0f0f1;
                    cursor: pointer;
                }

                .fust-tags-dropdown-item:first-child {
                    border-radius: 4px 4px 0 0;
                }

                .fust-tags-dropdown-item:last-child {
                    border-radius: 0 0 4px 4px;
                }

                .fust-tags-dropdown.open {
                    display: flex;
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
        if (isset($_POST['fust_location'])) {
            update_post_meta($post, 'location', sanitize_text_field( $_POST['fust_location'] ));
        }

        if (isset($_POST['fust_date'])) {
            update_post_meta($post, 'date', sanitize_text_field( $_POST['fust_date'] ));
        }

        if (isset($_POST['fust_tags'])) {
            update_post_meta($post, 'tags', sanitize_text_field( $_POST['fust_tags'] ));
        }

        if (isset($_POST['fust_time_start_hours'])) {
            update_post_meta($post, 'start_hours', sanitize_text_field( $_POST['fust_time_start_hours'] ));
        }

        if (isset($_POST['fust_time_start_minutes'])) {
            update_post_meta($post, 'start_minutes', sanitize_text_field( $_POST['fust_time_start_minutes'] ));
        }

        if (isset($_POST['fust_time_end_hours'])) {
            update_post_meta($post, 'end_hours', sanitize_text_field( $_POST['fust_time_end_hours'] ));
        }

        if (isset($_POST['fust_time_end_minutes'])) {
            update_post_meta($post, 'end_minutes', sanitize_text_field( $_POST['fust_time_end_minutes'] ));
        }
        
        // Not working FSR
        // update_field('fust_location', 'location');
        // update_field('fust_date', 'date');
        // update_field('fust_start_hours', 'start_hours');
        // update_field('fust_start_minutes', 'start_minutes');
        // update_field('fust_end_hours', 'end_hours');
        // update_field('fust_end_minutes', 'end_minutes');
	}

    private static function update_field( $request_field, $field)
    {
        if (isset($_POST[$request_field])) {
            update_post_meta($post, $field, sanitize_text_field( $_POST[$request_field] ));
        }
    }
}
