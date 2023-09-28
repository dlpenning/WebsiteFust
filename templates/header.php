<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <title><?= fust_get_title() ?> - FUST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,700;1,400&family=League+Spartan:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="body-inner">
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
                        <!--<i class="fa fa-search"></i>-->
                    </div>
                </div>
            </div>
        </header>