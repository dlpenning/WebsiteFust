<?php
fust_set_title(the_title('', '', false));
?>

<?= get_template_part('templates/header') ?>

<main role="main">
  <section class="reduced-top-space">
    <article class="section-content">
      <h1 class="title section-title"><?= the_title() ?></h1>
      <p><?= the_content() ?></p>
    </div>
  </section>
</main>

<?= get_template_part('templates/footer') ?>

