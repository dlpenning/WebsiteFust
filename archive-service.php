<?php 
fust_set_title('Services');
?>

<?= get_template_part('templates/header') ?>


<main role="main" class="no-banner">
    <section class="archive-content news reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Services</h1>
            <p>We provide a number of (digital) services. Check them out below!</p>

            <div class="services-grid section-container">

                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <a class="services-grid-item" href="<?= service_get_the_custom_permalink($post) ?>" target="<?php if (service_is_external($post)) { ?>_blank<?php } ?>" >
                        <div class="services-grid-item-upper">
                            <i class="fas <?= service_get_the_icon($post) ?>"></i>
                            <?php if (service_is_external($post)) { ?>
                                <i class="fa fa-external-link-alt"></i>
                            <?php } ?>
                        </div>
                        <div class="services-grid-item-lower">
                            <span title="<?= get_the_title($p) ?>"><?= get_the_title($p) ?></span>
                        </div>
                    </a>
                <?php endwhile; endif; ?>

            </div>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>