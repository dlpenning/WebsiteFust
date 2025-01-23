<?php
/**
 * Handle row-level actions, such as creating accounts or sending confirmation emails.
 *
 * @param array $post The POST request data.
 */
function handle_row_level_actions($post) {
    // Determine the specific action
    if (isset($post['create-account-id'])) {
        $id = validate_post_args(str_replace('create-account-', '', $post['create-account-id']), 'number');
        $action = 'create-account';
    } elseif (isset($post['create-guest-account-id'])) {
        $id = validate_post_args(str_replace('create-guest-account-', '', $post['create-guest-account-id']), 'number');
        $action = 'create-guest-account';
    } elseif (isset($post['send-confirmation-id'])) {
        $id = validate_post_args(str_replace('send-confirmation-', '', $post['send-confirmation-id']), 'number');
        $action = 'send-confirmation';
    } else {
        echo '<b>Invalid row-level action.</b>';
        return false;
    }

    if (!$id) {
        echo '<b>Invalid ID provided.</b>';
        return false;
    }

    // Retrieve the entry from the list based on the ID
    $entries = fust_get_vxcf_entries();
    $entry = $entries[$id] ?? null;

    if (!$entry) {
        echo '<b>Entry not found.</b>';
        return false;
    }

    // Process the row-level action
    switch ($action) {
        case 'create-account':
            $username = to_snake_case($entry['your-name']);
            $email = $entry['your-email'];
            $display_name = $entry['your-name'];

            create_fust_user_with_generated_password($username, $email, $display_name, FALSE, FALSE);
            break;

        case 'create-guest-account':
            $username = to_snake_case($entry['your-name']);
            $email = $entry['your-email'];
            $display_name = $entry['your-name'];

            create_fust_user_with_generated_password($username, $email, $display_name, FALSE, TRUE);
            break;

        case 'send-confirmation':
            [$subject, $message] = generate_guest_member_email($entry);

            // Find the association email
            $email_address = '';
            $association_option_data = get_option('joint_associations', []); // Normal array containing 'name' and 'email' field
            
            foreach ($association_option_data as $association) {
                if ($association['name'] === extract_cf7_dropdown_value($entry['association'])) {
                    $email_address = $association['email'];
                    break;
                }
            }

            if (empty($email_address)) {
                error_log('Association email not found');
                wp_send_json_error(['error' => 'Association email not found (entry: ' . $entry['association'] . ')']);
                echo '<b>Association email not found.</b>';
                break;
            }

            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($email_address, $subject, $message, $headers);
            break;

        default:
            echo '<b>Unknown row-level action.</b>';
            return false;
    }

    echo '<b>Row-level action completed successfully!</b>';
}


// Function to remove slashes before apostrophes
function remove_apostrophe_slashes($string) {
    return preg_replace("/\\\'/", "'", $string);
}



/**
 * Handle settings-related actions, such as saving joined associations.
 *
 * @param array $post The POST request data.
 */
function handle_setting_actions($post) {
    if (isset($post['save-associations'])) {
        // Initialize an array to hold the updated associations.
        $associations = [];

        // Iterate over the posted associations.
        foreach ($_POST['associations'] as $index => $assoc) {
            $association = [
                'name' => sanitize_text_field($assoc['name'] ?? ''),
                'description' => sanitize_textarea_field($assoc['description'] ?? ''),
                'email' => sanitize_email($assoc['email'] ?? ''),
                'website' => esc_url_raw($assoc['website'] ?? ''),
                'logo_url' => esc_url_raw($assoc['logo_url'] ?? ''),
            ];

            // Remove any extra slashes that may have been added
            $association['name'] = remove_apostrophe_slashes($association['name']);
            $association['description'] = remove_apostrophe_slashes($association['description']);
            $association['email'] = remove_apostrophe_slashes($association['email']);
            $association['website'] = remove_apostrophe_slashes($association['website']);
            $association['logo_url'] = remove_apostrophe_slashes($association['logo_url']);

            // Add the association to the list
            $associations[] = $association;
        }

        // Save updated associations
        update_option('joint_associations', $associations);

        echo '<b>Settings saved successfully!</b>';
    } else {
        echo '<b>Unknown settings action.</b>';
        return false;
    }
}


/**
 * Processes logo uploads for all associations.
 *
 * @param array $associations The list of associations from the POST request.
 * @return array Updated associations with logo URLs included.
 */
function process_logo_uploads($associations) {
    foreach ($associations as $index => $association) {
        // Check if there's a file upload for this association
        $file_key = "associations[$index][logo]";
        $logo_url = handle_logo_upload($file_key);

        // If a logo was uploaded successfully, update the association
        if ($logo_url && !is_string($logo_url)) { // Ignore error messages (string)
            $associations[$index]['logo_url'] = $logo_url;
        }
    }

    return $associations;
}


/**
 * Handles file uploads for a single logo.
 *
 * @param string $file_key The key in the $_FILES array.
 * @return string|null URL of the uploaded logo, or null if no file was uploaded.
 */
function handle_logo_upload($file_array) {
    if (!isset($file_array) || $file_array['error'] !== UPLOAD_ERR_OK) {
        return null; // Return null if no valid file was uploaded.
    }

    // Create the upload directory if it doesn't exist.
    $upload_dir = wp_upload_dir();
    $upload_path = $upload_dir['basedir'] . '/joint-associations/';
    wp_mkdir_p($upload_path);

    // Generate the file path.
    $file_name = sanitize_file_name($file_array['name']);
    $file_tmp = $file_array['tmp_name'];
    $file_path = $upload_path . $file_name;

    // Move the uploaded file to the destination.
    if (move_uploaded_file($file_tmp, $file_path)) {
        // Return the URL of the uploaded file.
        return $upload_dir['baseurl'] . '/joint-associations/' . $file_name;
    }

    return null; // Return null if the upload failed.
}