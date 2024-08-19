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

function get_google_calendar_link($loc, $start_time, $end_time)
{
    return 'https://www.google.com/calendar/render?action=TEMPLATE&text=' . urlencode(get_the_title($p)) . '&details=' . urlencode(get_the_content($p)) . '&location=' . urlencode($loc) . '&dates=' . urlencode($start_time) . '%2F' . urlencode($end_time);
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
            </div>
            <div class="activity-metadata">
                <h3>Metadata</h3>
                <ul>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-calendar"></i></span><span id="activity-date"><?= $formatted_date ?></span></li>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-clock"></i></span><?= $formatted_time ?></li>
                    <?php if (trim($location) !== '') { ?> <li><span class="icon-wrapper"><i class="fas fa-fw fa-map-marker-alt"></i></span><?= $location ?></li> <?php } ?>
                </ul>
                <a href="<?= get_google_calendar_link($location, $start_date_time_iso, $end_date_time_iso) ?>" target="_blank" class="button primary">Add to Google Calendar</a>
            </div>
        </div>
    </section>
</main>

<?= get_template_part('templates/footer') ?>