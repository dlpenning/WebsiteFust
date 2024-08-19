<?php
/*
Template Name: Knowledge Database Page
*/

fust_set_title(the_title('', '', false));
?>

<?= get_template_part('templates/header') ?>

<main role="main">
    <section class="knowledge-database-header">
        <article class="section-content">
            <div class="activity-tags">
                <span>Knowledge database</span>
            </div>
            <h1 class="title section-title"><?= the_title() ?></h1>
        </article>
    </section>
    <section class="reduced-top-space">
        <article class="section-content knowledge-database-article">
            <p><?= the_content() ?></p>
        </article>
    </section>
</main>

<?= get_template_part('templates/footer') ?>
