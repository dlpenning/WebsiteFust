<?php
fust_set_title('Homepage');

$services = fust_get_services();
$top_services = array_slice($services, 0, 4);

?>
<?= get_template_part('templates/header') ?>

<main role="main">
    <section class="masthead">
        <div class="section-content">
            <div class="masthead-content">
                <h1 class="title masthead-title">More than just your student union.</h1>
                <p class="masthead-subtitle">
                    Representing all students that study and/or live in Tilburg. By involving not only the students of Tilburg but also the associations focused on students we ensure that Tilburg will live up to all its potential as a student city.
                    F.U.S.T. as a union ensures that there is always a place where students feel safe to voice their opinion and be themselves.
                </p>
                <div><a href="/become-a-member" class="button outline white">Join us now</a></div>
            </div>
            <div class="masthead-figure">
                <img src="<?= get_template_directory_uri(); ?>/img/20230829-fust-066.jpg" alt="">
                <div class="graphic"></div>
            </div>
        </div>
        <svg class="masthead-transition" width="1920" height="57" viewBox="0 0 1920 57" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0L1920 55H0V0Z" fill="white" fill-opacity="0.3"/>
            <path d="M0 17L1920 57H0V17Z" fill="white"/>
        </svg>
    </section>
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
    <div class="section-divider"></div>
    <section>
        <div class="section-content">
            <h1 class="title section-title">Member benefits</h1>
            <p>We provide a number of (digital) exclusive member benefits. Check out our most popular ones below!</p>

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
            <a href="/services" class="button primary">View all member benefits</a>
        </div>
    </section>
</main>

<?= get_template_part('templates/footer') ?>