<?php
$entries = fust_get_vxcf_entries();

function validate_post_args($argument, $type) {
    if (empty(trim($argument))) {
        return -1;
    }

    if ($type == 'number' && !preg_match('/^[0-9]+$/', trim($argument))) {
        return -1;
    }

    return $argument;
}

function user_exists($email) {
    return email_exists($email);
}


/**
 * Approve post requests happen to this page and handle them accordingly.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Extract the ID based on the button clicked
    if (isset($_POST['create-account-id'])) {
        // Button clicked: Create account
        $id = validate_post_args(str_replace('create-account-', '', $_POST['create-account-id']), 'number');
        $action = 'create-account';
    } elseif (isset($_POST['create-guest-account-id'])) {
        // Button clicked: Create account
        $id = validate_post_args(str_replace('create-guest-account-', '', $_POST['create-guest-account-id']), 'number');
        $action = 'create-guest-account';
    } elseif (isset($_POST['send-confirmation-id'])) {
        // Button clicked: Send confirmation email
        $id = validate_post_args(str_replace('send-confirmation-', '', $_POST['send-confirmation-id']), 'number');
        $action = 'send-confirmation';
    } else {
        // Invalid request, no action identified
        echo '<b>An error occurred handling your request. Please contact a developer.</b>';
        return false;
    }

    if (!$id) {
        echo '<b>Invalid ID provided.</b>';
        return false;
    }

    // Retrieve the entry from the list based on the ID
    $entry = $entries[$id] ?? null;
    if (!$entry) {
        echo '<b>Entry not found.</b>';
        return false;
    }

    // Process the action based on the button clicked
    switch ($action) {
        case 'create-account':
            // Create the FUST user
            $username = to_snake_case($entry['your-name']);
            $email = $entry['your-email'];
            $display_name = $entry['your-name'];

            create_fust_user_with_generated_password($username, $email, $display_name, FALSE, FALSE);
            break;

        case 'create-guest-account':
            // Create the FUST user
            $username = to_snake_case($entry['your-name']);
            $email = $entry['your-email'];
            $display_name = $entry['your-name'];

            create_fust_user_with_generated_password($username, $email, $display_name, FALSE, TRUE);
            break;

        case 'send-confirmation':
            // Generate email content
            [$subject, $message] = generate_guest_member_email($entry);

            // Fixed internal email address
            $internal_email = 'johndoe@example.com';

            // Send the email
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($internal_email, $subject, $message, $headers);
            break;

        default:
            echo '<b>Unknown action.</b>';
            return false;
    }

    // Optionally, provide feedback to the user (e.g. success message)
    echo '<b>Action completed successfully!</b>';
}

// Group entries by form type
$grouped_entries = [];
foreach ($entries as $key => $entry) {
    $form_name = $entry['form_name'] ?? 'Unknown Form';
    if (!isset($grouped_entries[$form_name])) {
        $grouped_entries[$form_name] = [];
    }
    $grouped_entries[$form_name][$key] = $entry;
}
?>

<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<div class="wrap">
    <h1>FUST Dashboard</h1>
    <table class="form-table">
        <tbody>
            <tr>
                <th><label>Registrations</label></th>
                <td>
                    <a href="<?= menu_page_url('vxcf_leads', false) ?>" class="button button-primary">Go to page</a>
                </td>
            </tr>
        </tbody>
    </table>

    <h1>Account Creation</h1>
    <?php foreach ($grouped_entries as $form_name => $form_entries) { ?>
        <h2><?= esc_html($form_name) ?></h2>
        <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=fust' ?>" method="POST">
            <table class="wp-list-table widefat fixed striped" style="width: auto;">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($form_entries as $key => $entry) { ?>
                        <tr>
                            <td><?= esc_html($key) ?></td>
                            <td><?= array_key_exists('your-name', $entry) ? esc_html($entry['your-name']) : '<b><i>None</i></b>' ?></td>
                            <td><?= array_key_exists('your-email', $entry) ? esc_html($entry['your-email']) : '<b><i>None</i></b>' ?></td>
                            <td>
                                <button 
                                    type="submit" 
                                    name="create-account-id" 
                                    value="create-account-<?= esc_attr($key) ?>"
                                    <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                    class="button button-primary"
                                >
                                    Create account
                                </button>
                                <button 
                                    type="submit" 
                                    name="create-guest-account-id" 
                                    value="create-guest-account-<?= esc_attr($key) ?>"
                                    <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                    class="button button-primary"
                                >
                                    Create guest account
                                </button>
                                <?php if ($form_name == 'Register as guest member') { ?>
                                        <button
                                        type="submit"
                                        name="send-confirmation-id"
                                        value="send-confirmation-<?= esc_attr($key) ?>"
                                        <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                        class="button button-secondary" 
                                    >
                                        Send Confirmation Email
                                    </button>
                                    
                                    <?php
                                } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" name="action" value="approve-user">
        </form>
    <?php } ?>
</div>
