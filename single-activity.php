<?php 
fust_set_title(the_title('', '', false));

$id = $post->ID;

$location = get_post_meta( $id, 'location', true );
$date = get_post_meta( $id, 'date', true );
$tags = get_post_meta( $id, 'tags', true );
$start_hours = get_post_meta( $id, 'start_hours', true );
$start_minutes = get_post_meta( $id, 'start_minutes', true );
$end_hours = get_post_meta( $id, 'end_hours', true );
$end_minutes = get_post_meta( $id, 'end_minutes', true );

$formatted_date = FUST_Activity::formatLocaleDate($date, 'en');
$formatted_time = sprintf('%02d:%02d - %02d:%02d', $start_hours, $start_minutes, $end_hours, $end_minutes);

$start_date_time_iso = FUST_Activity::formatDateTimeIso($date, $start_hours, $start_minutes);
$end_date_time_iso = FUST_Activity::formatDateTimeIso($date, $end_hours, $end_minutes);

$tags_array = FUST_Activity::parse_tags($tags);

function get_google_calendar_link($post_id, $loc, $start_time, $end_time) {
    return 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode(get_the_title($post_id)) . '&details=' . urlencode(get_the_content(null, false, $post_id)) . '&location=' . urlencode($loc) . '&dates=' . urlencode($start_time) . '%2F' . urlencode($end_time);
}

function render_activity_signup_form() {
    // Get the current post ID
    $activity_id = get_the_ID();
    
    // Check if non-member signup is allowed
    $allow_non_member_signup = get_post_meta($activity_id, 'allow_non_member_signup', true);
    
    // Get the fields required for non-member signup
    $non_member_fields = get_post_meta($activity_id, 'non_member_fields', true);

    // Ensure $non_member_fields is an array
    if (!is_array($non_member_fields)) {
        $non_member_fields = [];
    }

    // Determine if the current user is signed up
    $is_signed_up = is_user_logged_in() ? FUST_Activity::is_user_signed_up($activity_id, get_current_user_id()) : false;

    ?>
    <form id="activity-signup-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="handle_activity_signup">
        <input type="hidden" name="activity_id" value="<?= esc_attr($activity_id); ?>" />

        <?php if (is_user_logged_in()): ?>
            <?php if ($is_signed_up): ?>
                <p>You are already signed up for this activity.</p>
                <button type="submit" name="activity_unsubscribe_submit">Unsubscribe from this Activity</button>
            <?php else: ?>
                <button type="submit" name="activity_signup_submit">Sign Up for this Activity</button>
            <?php endif; ?>
        <?php elseif ($allow_non_member_signup): ?>
            <?php if (in_array('email', $non_member_fields)): ?>
                <label for="non_member_email">Email:</label>
                <input type="email" name="non_member_email" id="non_member_email" required>
            <?php endif; ?>
            
            <?php if (in_array('full_name', $non_member_fields)): ?>
                <label for="non_member_full_name">Full Name:</label>
                <input type="text" name="non_member_full_name" id="non_member_full_name" required>
            <?php endif; ?>
            
            <?php if (in_array('phone_number', $non_member_fields)): ?>
                <label for="non_member_phone_number">Phone Number:</label>
                <input type="text" name="non_member_phone_number" id="non_member_phone_number" required>
            <?php endif; ?>
            
            <button type="submit" name="activity_signup_submit">Sign Up for this Activity</button>
        <?php else: ?>
            <p>You must be logged in to sign up for this activity.</p>
        <?php endif; ?>

        <?php wp_nonce_field('activity_signup_nonce', 'activity_signup_nonce_field'); ?>
    </form>
    <?php
}
?>

<?= get_template_part('templates/header') ?>

<main role="main">
    <section class="post-content activity reduced-top-space">
        <div class="section-content">
            <div class="activity-details">
                <h1 class="title"><?= the_title() ?></h1>
                <div class="activity-tags">
                    <?php foreach ($tags_array as $tag) { ?>
                        <span><?= $tag ?></span>
                    <?php } ?>
                </div>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <article>
                        <?= the_content() ?>
                    </article>
                <?php endwhile; endif; ?>

                <?php render_activity_signup_form(get_the_ID()); ?>
            </div>
            <div class="activity-metadata">
                <h3>Metadata</h3>
                <ul>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-calendar"></i></span><span id="activity-date"><?= $formatted_date ?></span></li>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-clock"></i></span><?= $formatted_time ?></li>
                    <?php if (trim($location) !== '') { ?> <li><span class="icon-wrapper"><i class="fas fa-fw fa-map-marker-alt"></i></span><?= $location ?></li> <?php } ?>
                </ul>
                <a href="<?= get_google_calendar_link(get_the_ID(), $location, $start_date_time_iso, $end_date_time_iso) ?>" target="_blank" class="button primary">Add to Google Calendar</a>
            </div>
        </div>
    </section>
</main>

<?= get_template_part('templates/footer') ?>