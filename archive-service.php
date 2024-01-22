<?php 
fust_set_title('Member benefits');
?>

<?= get_template_part('templates/header') ?>


<main role="main" class="no-banner">
    <section class="archive-content news reduced-top-space">
        <div class="section-content">
            <h1 class="title section-title">Member benefits</h1>
            <p>We provide a number of (digital) member benefits. Check them out below!</p>

            <?php if (is_user_logged_in()) { ?>

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

            <?php } else { ?>

                <div class="no-access-card">
                    <div class="no-access-card-img-wrapper">
                        <img src="<?= get_template_directory_uri(); ?>/img/undraw_access_denied.svg" alt="">
                    </div>
                    <div class="no-access-card-content-wrapper">
                        <div>
                            <i class="fas fa-lock fa-4x"></i>
                            <h1 class="title">Access denied</h1>
                            <p><i>You have to be logged in to view our member benefits. Become a member now to create your account!</i></p>
                            <a href="/become-a-member" class="button outline white">Join us now</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>