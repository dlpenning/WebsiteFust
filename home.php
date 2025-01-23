<?php
/*
Template Name: Home page
*/
fust_set_title('Homepage');

add_action('customize_register', array( 'FUST_Home_Page', 'setup_customizer' ));

$services = fust_get_services();
$top_services = array_slice($services, 0, 4);

?>
<?= get_template_part('templates/header') ?>

<main role="main">
    <?php

    $c = get_this_page_controller();
    if( $c ) $c->render();

    ?>
    
    <section class="centered">
        <div class="section-content">
            <h1 class="title section-title">About F.U.S.T.</h1>
            <p class="subtitle"><i><b>F.U.S.T.</b> is a student union dedicated to ensuring high quality of education while also focusing on improving students well-being.</i></p>
            <div class="about-container">
                <p class="row-1-text">What makes us different from other associations is that we represent all students and all associations in the municipality.
                F.U.S.T. is the main student collective that has direct communication with the municipality. This connection enables us to create significant change, and that the voice of our members is heard. Our vision for the future of Tilburg University is to make it a welcoming place for all, where students can flourish both academically and personally.</p>
                <div class="image-container row-1-image">
                    <img src="<?= get_template_directory_uri(); ?>/img/undraw_having_fun_re_vj4h.svg" alt="">
                </div>
                <div class="image-container row-2-image">
                    <img src="<?= get_template_directory_uri(); ?>/img/undraw_teamwork_hpdk.svg" alt="">
                </div>
                <p class="row-2-text">We value internationalization, safety in the city, mental health, efficient transport and affordable housing along with many other domains where we think Tilburg can improve. If you are a student at Tilburg University or a large association, F.U.S.T. can help you achieve your goals and enjoy this beautiful city!</p>
            </div>
        </div>
    </section>
    <!-- <div class="section-divider"></div> -->
    <section>
        <div class="section-content">
            <h1 class="title section-title">Member benefits</h1>
            <p>We provide a number of (digital) exclusive member benefits. Check out our most popular ones below!</p>

            <?php if (is_user_logged_in()) { ?>

            <div class="services-grid section-container">

            <?php foreach ($top_services as $service) { ?>
                <a class="services-grid-item" href="<?= service_get_the_custom_permalink($service) ?>" target="<?php if (service_is_external($service)) { ?>_blank<?php } ?>" >
                    <div class="services-grid-item-upper">
                        <i class="fas <?= service_get_the_icon($service) ?>"></i>
                        <?php if (service_is_external($service)) { ?>
                            <i class="fa fa-external-link-alt"></i>
                        <?php } ?>
                    </div>
                    <div class="services-grid-item-lower">
                        <span title="<?= get_the_title($service->ID) ?>"><?= get_the_title($service->ID) ?></span>
                    </div>
                </a>
            <?php } ?>

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
            <a href="/services" class="button primary">View all member benefits</a>
        </div>
    </section>
    <!-- <div class="section-divider"></div> -->
    <section class="centered">
        <div class="section-content">
            <h1 class="title section-title">Joined Associations</h1>
            
            <div class="partner-grid">
                <?php
                // Get saved associations from the options table
                $associations = get_option('joint_associations', []);

                if (!empty($associations)) {
                    foreach ($associations as $association) {
                        $logo_url = !empty($association['logo_url']) ? esc_url($association['logo_url']) : get_template_directory_uri() . '/img/default-logo.png';
                        $name = esc_html($association['name']);
                        $description = esc_html($association['description']);
                        $website = !empty($association['website']) ? esc_url($association['website']) : '#'; // Use '#' if no website is provided
                        ?>
                        <div class="partner-grid-item">
                            <div class="logo">
                                <img src="<?= $logo_url; ?>" alt="<?= $name; ?>">
                            </div>
                            <div class="content">
                                <h2>
                                    <a href="<?= $website; ?>" class="link no-color" target="_blank"><?= $name; ?></a>
                                </h2>
                                <p><?= $description; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // If no associations are found, display a message
                    echo '<p>No joined associations found.</p>';
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?= get_template_part('templates/footer') ?>