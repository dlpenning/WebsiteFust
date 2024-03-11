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

if ($start_hours < 10) {
    $start_hours = '0' . $start_hours;
}
if ($start_minutes < 10) {
    $start_minutes = '0' . $start_minutes;
}
if ($end_hours < 10) {
    $end_hours = '0' . $end_hours;
}
if ($end_minutes < 10) {
    $end_minutes = '0' . $end_minutes;
}

$tags_array = FUST_Activity::parse_tags($tags);
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
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-calendar"></i></span><span id="activity-date">Tuesday, February 27th 2024</span></li>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-clock"></i></span><?= $start_hours ?>:<?= $start_minutes ?> - <?= $end_hours ?>:<?= $end_minutes ?></li>
                    <li><span class="icon-wrapper"><i class="fas fa-fw fa-map-marker-alt"></i></span><?= $location ?></li>
                </ul>
                <a href="#" class="button primary">Add to Google Calendar</a>
            </div>
        </div>
    </section>
</main>

<script>
const activityDate = document.getElementById('activity-date');
const dateString = <?= json_encode($date) ?>;
console.log(dateString);
const date = new Date(dateString);
console.log(date)
</script>

<?= get_template_part('templates/footer') ?>