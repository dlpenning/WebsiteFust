<?php 
fust_set_title('News');

function is_external() {
    if (get_post_custom_values('external_link')) {
        return true;
    }

    return false;
}

function get_the_custom_permalink($p) {
    if (is_external()) {
        return implode('', get_post_custom_values('external_link'));
    }

    return get_the_permalink($p);
}
?>

<?= get_template_part('templates/header') ?>

<main role="main" class="no-banner">
    <section class="archive-content news reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Recent news</h1>

            <div class="news-grid">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="news-grid-item">
                    <a href="<?= get_the_custom_permalink($p) ?>" target="<?php if (is_external()) { ?>_blank<?php } ?>" >
                        <div class="thumbnail">
                            <?php if (has_post_thumbnail()) { ?>
                                <img class="post-thumbnail" src="<?php the_post_thumbnail_url('medium'); ?>">
                            <?php } else { ?>
                                <i class="far fa-newspaper abs-centered fa-3x"></i>
                            <?php }; ?>
                        </div>
                    </a>
                    <div class="lower">
                        <a href="<?= get_the_custom_permalink($p) ?>" target="<?php if (is_external()) { ?>_blank<?php } ?>" >
                            <h3><?= get_the_title($p) ?></h3>
                            <?php if (is_external()) { ?>
                                <i class="fa fa-external-link-alt"></i>
                            <?php } ?>
                        </a>
                        <p><?= get_excerpt(100, $p) ?></p>
                    </div>
                    <p><?= get_the_date('d M Y') ?> - <?= reading_time() ?> read</p>
                </div>
            <?php endwhile; endif; ?>

            </div>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>