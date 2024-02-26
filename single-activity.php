<?php 
fust_set_title(the_title('', '', false));
?>

<?= get_template_part('templates/header') ?>

<main role="main">
    <section class="post-content activity reduced-top-space">
        <div class="section-content">
            <div class="activity-details">
                <h1 class="title"><?= the_title() ?></h1>
                <div class="activity-tags">
                    <span>Meeting</span>
                    <span>Career</span>
                    <span>Personal development</span>
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
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-calendar"></i></span>Tuesday, February 27th 2024</li>
                    <li><span class="icon-wrapper"><i class="far fa-fw fa-clock"></i></span>12:30 - 13:30</li>
                    <li><span class="icon-wrapper"><i class="fas fa-fw fa-map-marker-alt"></i></span>Tilburg Campus</li>
                </ul>
                <a href="#" class="button primary">Add to Google Calendar</a>
            </div>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>