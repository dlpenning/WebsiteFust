<?= get_template_part('templates/head') ?>

<header class="app-header">
    <div class="top-navbar">
        <div class="top-navbar-inner">
            <div></div>
            <?php include 'socials.php'; ?>
        </div>
    </div>
    <div class="main-navbar">
        <div class="main-navbar-inner">
            <nav>
                <a href="/"><?php include 'logo.php'; ?></a>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'fust-app-header',
                    )
                );
                ?>
            </nav>
            <div class="right-nav">
                <a href="/become-a-member" class="item button primary">Become a member</a>
                <a href="/fust-login">
                    <i class="fa <?= is_user_logged_in() ? 'fas' : 'far' ?> fa-user"></i>
                </a>
            </div>
            <div class="nav-burger">
                <?php include 'burger.php'; ?>
            </div>
        </div>

        <div class="nav-screen">
            <header class="nav-screen-header">
                <div class="nav-close">
                    <i class="fa fa-times fa-2x nav-close"></i>
                </div>
                <div class="nav-screen-search">
                    <a href="/fust-login">
                        <i class="fa <?= is_user_logged_in() ? 'fas' : 'far' ?> fa-user"></i>
                    </a>
                </div>
            </header>
            <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'fust-app-header',
                    )
                );
            ?>
            <a href="/become-a-member" class="item button primary">Become a member</a>
            <div class="nav-screen-socials">
                <?php include 'socials.php'; ?>
            </div>
        </div>
    </div>
</header>