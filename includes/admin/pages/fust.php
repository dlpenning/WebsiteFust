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

function toSnakeCase($inputString) {
    $snakeCaseString = preg_replace('/[^A-Za-z0-9]+/', '_', $inputString);
    $snakeCaseString = strtolower($snakeCaseString);
    $snakeCaseString = trim($snakeCaseString, '_');

    return $snakeCaseString;
}

/**
 * Approve post requests happen to this page. Handle them here by creating a new user.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = validate_post_args($_POST['id'], 'number');
    $action = validate_post_args($_POST['action'], 'string');

    if (!$id or !$action) {
        echo '<b>An error occurred handling your request. Please contact a developer.</b>';
        return false;
    }

    $username = toSnakeCase($entries[$id]['your-name']);
    $email = $entries[$id]['your-email'];
    $display_name = $entries[$id]['your-name'];

    create_fust_user_with_generated_password($username, $email, $display_name);
}
?>

<div class="wrap">
    <h1>FUST Admin Page</h1>
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
    <h1>Account creation</h1>
    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=fust' ?>" method="POST">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entries as $key => $entry) { ?>
                <tr>
                    <td><?= $key ?></td>
                    <td><?= $entry['your-name'] ?></td>
                    <td><?= $entry['your-email'] ?></td>
                    <td><button type="submit" name="id" value="<?= $key ?>" <?= user_exists($entry['your-email']) ? 'disabled' : '' ?>>Create account</button><td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <input type=hidden name="action" value="approve-user">
    </form>
</div>
