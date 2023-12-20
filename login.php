<?php
/*
Template Name: Custom Login Page
*/

fust_set_title('Login');

function get_current_user_roles() {
  if (is_user_logged_in()) {
    $user = wp_get_current_user(); 
    $roles = (array) $user->roles;
    return $roles;
 
  }

  return array();
}

function is_current_user_admin() {
    $roles = get_current_user_roles();
    if (in_array('administrator', $roles)) {
        return true;
    }

    return false;
}

?>

<?= get_template_part('templates/head') ?>

<main role="main" class="fust-login">
    <div class="fust-login-left">
      <?php include 'templates/logo.php'; ?>
    </div>
    <div class="fust-login-right">
        <div class="fust-login-card">
            <div class="fust-login-card-inner">
                <?php
                if (!is_user_logged_in()) {
                    // Display the login form
                    ?>
                    <header>
                        <?php include 'templates/logo.php'; ?>
                        <h1>Log in</h1>
                    </header>
                    <form action="<?= esc_url( home_url( '/wp-login.php' ) ) ?>" method="post">
                        <div class="field">
                            <label for="user_login"><?= esc_html_e('Username', 'textdomain') ?></label>
                            <input type="text" name="log" id="user_login" value="<?= esc_attr(wp_unslash($user_login)) ?>">
                        </div>
                        <div class="field">
                            <label for="user_pass"><?= esc_html_e('Password', 'textdomain') ?></label>
                            <input type="password" name="pwd" id="user_pass">
                        </div>
                        <div class="field actions">
                            <input type="submit" class="button primary" name="wp-submit" value="<?= esc_attr_e('Log In', 'textdomain') ?>">
                            <a href="/" class="link">Return to website</a>
                        </div>
                        <input type="hidden" name="action" value="process_fust_login">
                        <input type="hidden" name="redirect_to" value="<?= esc_url(home_url()); ?>">
                    </form>
                    <?php
                } else {
                    // Display a message for logged-in users
                    ?>
                    <header>
                        <?php include 'templates/logo.php'; ?>
                    </header>
                    <p><?= esc_html_e('You are already logged in.', 'textdomain'); ?></p>
                    <form action="" method="post">
                        <?php if (is_current_user_admin()) {
                            ?>
                            <a href="<?= esc_url(get_admin_url()) ?>" class="button primary" style="text-align: center; font-weight: bold;">Go to WP admin</a>
                            <?php
                        }
                        ?>
                        <div class="field actions">
                            <a href="<?= esc_url(wp_logout_url(home_url())) ?>" class="button primary">Log Out</a>
                            <a href="/" class="link">Return to website</a>
                        </div>
                        <input type="hidden" name="action" value="process_fust_login">
                        <input type="hidden" name="redirect_to" value="<?= esc_url(home_url()); ?>">
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?= get_template_part('templates/foot') ?>