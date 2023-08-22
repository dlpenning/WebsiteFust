<?php
fust_set_title('Homepage');
?>
<?= get_template_part('templates/header') ?>

<main>
    <section class="masthead">
        <div class="inner">
            <div class="masthead-content">
                <h1 class="title masthead-title">More than just your student union.</h1>
                <p class="masthead-subtitle">

                F.U.S.T. is a student union dedicated to ensuring high quality of education while also focusing on improving students well-being. What makes us different from other associations is that we represent all students and all associations in the municipality. F.U.S.T. is the main (or only) student collective that has direct communication with the municipality.

                </p>
                <div><a href="/become-a-member" class="button outline white">Join us now</a></div>
            </div>
            <div class="masthead-figure">
                <img src="<?= get_template_directory_uri(); ?>/img/stock.png" alt="">
                <div class="graphic"></div>
            </div>
        </div>
        <svg class="masthead-transition" width="1920" height="55" viewBox="0 0 1920 55" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0 0L1920 55H0V0Z" fill="white" fill-opacity="0.3" />
            <path d="M0 17L1920 55H0V17Z" fill="white" />
        </svg>
    </section>
    <section class="centered">
        <div class="inner">
            <h1 class="title section-title"><span class="highlight">Your</span> student union</h1>
            <p class="subtitle">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc purus sapien, congue a euismod sit amet.</p>
        </div>
    </section>
</main>

<?= get_template_part('templates/footer') ?>