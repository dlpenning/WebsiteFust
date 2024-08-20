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
        add_meta_box('fust_activity_signup_metabox', __('Activity Users Signed Up', 'fust'), array('FUST_Activity', 'fust_activity_signup_metabox' ), 'activity', 'advanced');
    }

    public static function parse_tags($tagsString) {
        // Check if the input string is empty or only contains whitespace
        if (trim($tagsString) === '') {
            return [];
        }
    
        // Split the tags string into an array using the comma as a delimiter
        $tagsArray = explode(',', $tagsString);
    
        // Trim any leading or trailing whitespace from each tag
        $tagsArray = array_map('trim', $tagsArray);
    
        return $tagsArray;
    }
    

    public static function formatLocaleDate($date, $lang) {
        // Convert input date string to DateTime object
        $dateTime = DateTime::createFromFormat('d/m/Y', $date);
        
        // Set locale based on input parameter $lang
        $locale = ($lang == 'nl') ? 'nl_NL' : 'en_US';
        
        // Create IntlDateFormatter object
        $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);
        
        // Format the date
        $formattedDate = $formatter->format($dateTime);
        
        return $formattedDate;
    }

    public static function formatDateTimeIso($date, $hour, $minute) {
        // Convert input date string to DateTime object with specified format
        $dateTime = DateTime::createFromFormat('d/m/Y', $date, new DateTimeZone('Europe/Amsterdam'));
        
        // Set hour and minute in Europe/Amsterdam timezone
        $dateTime->setTime($hour, $minute);
        
        // Convert the datetime to UTC timezone
        $dateTime->setTimezone(new DateTimeZone('UTC'));
        
        // Format the date in the specified format
        $formattedDateTime = $dateTime->format('Ymd\THis\Z');
        
        return $formattedDateTime;
    }

    /**
	 * The metabox containing the event data, the date
	 * 
	 * @since 1.0.0
	 */
	public static function fust_activity_data_metabox($post) {
        wp_nonce_field('fust_save_activity', 'fust_activity_data_metabox_nonce');
    
        $location = get_post_meta($post->ID, 'location', true);
        $date = get_post_meta($post->ID, 'date', true);
        $tags = get_post_meta($post->ID, 'tags', true);
        
        $start_hours = get_post_meta($post->ID, 'start_hours', true);
        $start_minutes = get_post_meta($post->ID, 'start_minutes', true);
        $end_hours = get_post_meta($post->ID, 'end_hours', true);
        $end_minutes = get_post_meta($post->ID, 'end_minutes', true);
    
        // Fetch non-member signup settings
        $allow_non_member_signup = get_post_meta($post->ID, 'allow_non_member_signup', true);
        $non_member_fields = get_post_meta($post->ID, 'non_member_fields', true);
        $non_member_fields = is_array($non_member_fields) ? $non_member_fields : [];
    
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
    
            <!-- Signup settings -->
            <div class="field">
            <label for="fust_allow_non_member_signup">
                <input type="checkbox" name="fust_allow_non_member_signup" id="fust_allow_non_member_signup" value="1" <?php checked($allow_non_member_signup, '1'); ?> />
                    Allow non-members to sign up
                </label>
            </div>

            <div class="field">
                <label><b>Fields required for non-members:</b></label><br />
                <label><input type="checkbox" name="fust_non_member_fields[]" value="email" <?php checked(in_array('email', $non_member_fields)); ?> /> Email</label><br />
                <label><input type="checkbox" name="fust_non_member_fields[]" value="full_name" <?php checked(in_array('full_name', $non_member_fields)); ?> /> Full Name</label><br />
                <label><input type="checkbox" name="fust_non_member_fields[]" value="phone_number" <?php checked(in_array('phone_number', $non_member_fields)); ?> /> Phone Number</label>
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
    

    public static function fust_activity_signup_metabox($post) {
        // Get signups data
        $signups = get_post_meta($post->ID, 'activity_signups', true);
        
        // Check if the activity allows non-member signups
        $allow_non_member_signup = get_post_meta($post->ID, 'allow_non_member_signup', true);
        
        // Get the fields required for non-members
        $non_member_fields = get_post_meta($post->ID, 'non_member_fields', true);
        $non_member_fields = is_array($non_member_fields) ? $non_member_fields : [];
        
        // Display the table
        if (!empty($signups)) {
            ?>
            <table class="widefat fixed">
                <thead>
                    <tr>
                        <th><?php _e('Name', 'fust'); ?></th>
                        <th><?php _e('Email', 'fust'); ?></th>
                        <th><?php _e('Phone Number', 'fust'); ?></th>
                        <th><?php _e('Member', 'fust'); ?></th>
                        <th><?php _e('Actions', 'fust'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($signups as $signup) : 
                        $is_member = isset($signup['is_member']) ? $signup['is_member'] : false;
                        
                        if ($is_member) {
                            $user_info = get_userdata($signup['user_id']);
                            $name = $user_info->display_name;
                            $email = $user_info->user_email;
                            $phone_number = ''; // Leave empty for members
                            $user_id = $signup['user_id']; // Store the user ID for display
                        } else {
                            // For non-members
                            $name = isset($signup['full_name']) ? esc_html($signup['full_name']) : '';
                            $email = isset($signup['email']) ? esc_html($signup['email']) : '';
                            $phone_number = isset($signup['phone_number']) ? esc_html($signup['phone_number']) : '';
                            $user_id = ''; // Non-members don't have a user ID
                        }
                        
                        $is_member_text = $is_member ? 'Yes (' . $user_id . ')' : 'No';
                    ?>
                    <tr>
                        <td><?php echo esc_html($name); ?></td>
                        <td><?php echo esc_html($email); ?></td>
                        <td><?php echo esc_html($phone_number); ?></td>
                        <td><?php echo esc_html($is_member_text); ?></td>
                        <td>
                            <?php if ($is_member) : ?>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <input type="hidden" name="action" value="handle_activity_signup">
                                    <input type="hidden" name="activity_id" value="<?php echo esc_attr($post->ID); ?>">
                                    <input type="hidden" name="delete_user_id" value="<?php echo esc_attr($user_id); ?>">
                                    <?php wp_nonce_field('delete_signup_nonce', 'delete_signup_nonce_field'); ?>
                                    <button type="submit" name="delete_signup_submit" class="button button-secondary"><?php _e('Delete', 'fust'); ?></button>
                                </form>

                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo '<p>' . __('No users have signed up for this activity.', 'fust') . '</p>';
        }
    }
    
    


    public static function handle_activity_signup() {
        error_log('Handling signup');
        if (!isset($_POST['activity_signup_nonce_field']) || !wp_verify_nonce($_POST['activity_signup_nonce_field'], 'activity_signup_nonce')) {
            error_log('Invalid nonce');
            wp_die('Invalid nonce');
        }
    
        $activity_id = intval($_POST['activity_id']);
        $user_id = is_user_logged_in() ? get_current_user_id() : 0;
    
        // Retrieve existing signups
        $signups = get_post_meta($activity_id, 'activity_signups', true);
        $signups = is_array($signups) ? $signups : [];
    
        // Log the current signups
        error_log('Current signups: ' . print_r($signups, true));
    
        // Check if the user is already signed up
        $already_signed_up = false;
        if ($user_id > 0) {
            foreach ($signups as $signup) {
                if (is_array($signup) && isset($signup['user_id']) && $signup['user_id'] == $user_id) {
                    $already_signed_up = true;
                    break;
                }
            }
        }
    
        // Log the state of already signed up
        error_log('Already signed up: ' . ($already_signed_up ? 'Yes' : 'No'));
    
        // Handle form submission for signup
        if (isset($_POST['activity_signup_submit'])) {
            if (!$already_signed_up) {
                if ($user_id > 0) {
                    // User is logged in
                    $signups[] = [
                        'is_member' => true,
                        'user_id' => $user_id,
                        'email' => '',  // Empty for members
                        'full_name' => '', // Empty for members
                        'phone_number' => '' // Empty for members
                    ];
                } else {
                    // Non-member
                    $signups[] = [
                        'is_member' => false,
                        'user_id' => null,
                        'email' => sanitize_email($_POST['non_member_email'] ?? ''),
                        'full_name' => sanitize_text_field($_POST['non_member_full_name'] ?? ''),
                        'phone_number' => sanitize_text_field($_POST['non_member_phone_number'] ?? '')
                    ];
                }
                update_post_meta($activity_id, 'activity_signups', $signups);
                error_log('Signups updated after signup submit: ' . print_r($signups, true));
            }
        } elseif (isset($_POST['activity_unsubscribe_submit']) && $user_id > 0) {
            if ($already_signed_up) {
                // Remove the user from the signups
                $signups = array_filter($signups, function($signup) use ($user_id) {
                    return !($signup['is_member'] && isset($signup['user_id']) && $signup['user_id'] == $user_id);
                });
                update_post_meta($activity_id, 'activity_signups', $signups);
                error_log('Signups updated after unsubscribe submit: ' . print_r($signups, true));
            }
        } elseif (isset($_POST['delete_signup_submit']) && isset($_POST['delete_signup_nonce_field']) && wp_verify_nonce($_POST['delete_signup_nonce_field'], 'delete_signup_nonce')) {
            // Handle deletion of signups
            $delete_user_id = intval($_POST['delete_user_id']);
            if ($delete_user_id > 0) {
                $signups = array_filter($signups, function($signup) use ($delete_user_id) {
                    return !($signup['is_member'] && $signup['user_id'] == $delete_user_id);
                });
                update_post_meta($activity_id, 'activity_signups', $signups);
                error_log('Signups updated after delete submit: ' . print_r($signups, true));
            }
        }
    
        wp_redirect(get_permalink($activity_id));
        exit;
    }
    
    

    public static function is_user_signed_up($activity_id, $user_id) {
        // Get the list of users signed up for this activity
        $activity_signups = get_post_meta($activity_id, 'activity_signups', true);
    
        // Ensure $activity_signups is an array
        if (!is_array($activity_signups)) {
            $activity_signups = [];
        }
    
        // Check if the user is already signed up
        foreach ($activity_signups as $signup) {
            if (is_array($signup) && isset($signup['user_id']) && $signup['user_id'] == $user_id) {
                return true;
            }
        }
    
        return false;
    }
    

    public static function save_post($post_id) {
        // Ensure nonce verification and avoid autosave
        if (!isset($_POST['fust_activity_data_metabox_nonce']) ||
            !wp_verify_nonce($_POST['fust_activity_data_metabox_nonce'], 'fust_save_activity')) {
            return;
        }
    
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }
    
        // Define the fields and corresponding meta keys
        $fields = [
            'fust_location' => 'location',
            'fust_date' => 'date',
            'fust_tags' => 'tags',
            'fust_time_start_hours' => 'start_hours',
            'fust_time_start_minutes' => 'start_minutes',
            'fust_time_end_hours' => 'end_hours',
            'fust_time_end_minutes' => 'end_minutes'
        ];
    
        // Loop through the fields and update them
        foreach ($fields as $request_field => $meta_key) {
            self::update_field($request_field, $meta_key, $post_id);
        }
    
        // Save the allow_non_member_signup checkbox
        $allow_non_member_signup = isset($_POST['fust_allow_non_member_signup']) ? '1' : '0';
        update_post_meta($post_id, 'allow_non_member_signup', $allow_non_member_signup);
    
        // Save the non_member_fields checkboxes
        $non_member_fields = isset($_POST['fust_non_member_fields']) ? array_map('sanitize_text_field', $_POST['fust_non_member_fields']) : [];
        update_post_meta($post_id, 'non_member_fields', $non_member_fields);
    }
    
    private static function update_field($request_field, $meta_key, $post_id) {
        if (isset($_POST[$request_field])) {
            update_post_meta($post_id, $meta_key, sanitize_text_field($_POST[$request_field]));
        }
    }
    
    

}
