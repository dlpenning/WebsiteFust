<?php
// Template Name: Membership Registration - Cancel Page
fust_set_title('Payment failed');

$name = isset($_GET['username']) ? sanitize_text_field(urldecode($_GET['username'])) : '';
?>

<?= get_template_part('templates/header') ?>

<main role="main">
  <section class="reduced-top-space">
    <article class="section-content">
        <h1 class="title section-title">Payment Failed, <?php echo esc_html($name); ?>.</h1>
        <div class="cancel-message">
            <p>Unfortunately, your payment was not successful. Please try again.</p>
        </div>
    </article>
  </section>
</main>

<?= get_template_part('templates/footer') ?>

