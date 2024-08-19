<?php
// Template Name: Membership Registration - Thank You Page
fust_set_title('Thank you!');

$name = isset($_GET['username']) ? sanitize_text_field(urldecode($_GET['username'])) : '';
?>

<?= get_template_part('templates/header') ?>

<main role="main">
  <section class="reduced-top-space">
    <article class="section-content">
      <h1 class="title section-title">Thank You, <?php echo esc_html($name); ?>!</h1>
      <div class="thank-you-message">
        <p>Your payment was successful. Check your email for more details.</p>
    </div>
    </article>
  </section>
</main>

<?= get_template_part('templates/footer') ?>

