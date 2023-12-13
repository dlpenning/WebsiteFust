<?php 
fust_set_title(the_title('', '', false));
?>

<?= get_template_part('templates/header') ?>

<main role="main" class="no-banner">
    <section class="post-content">
        <div class="section-content">

            <?php if (has_post_thumbnail()): ?>
                <figure class="post-thumbnail-fig">
                    <img src="<?php the_post_thumbnail_url('largest'); ?>" class="post-thumbnail">
                    <figcaption><?= the_post_thumbnail_caption() ?></figcaption>
                </figure>
            <?php endif; ?>

            <h1 class="title"><?= the_title() ?></h1>
            <p><?= get_the_date('d M Y') ?> - <?= reading_time() ?> read</p>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article>
                    <?= the_content() ?>
                </article>
            <?php endwhile; endif; ?>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>