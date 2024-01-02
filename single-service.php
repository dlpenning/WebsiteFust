<?php 
fust_set_title(the_title('', '', false));
?>

<?= get_template_part('templates/header') ?>

<main role="main">
    <section class="post-content reduced-top-space">
        <div class="section-content">

            <h1 class="title"><?= the_title() ?></h1>

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article>
                    <?= the_content() ?>
                </article>
            <?php endwhile; endif; ?>
        </div>

    </section>
</main>

<?= get_template_part('templates/footer') ?>