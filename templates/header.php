<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    wp_head();
    ?>
</head>
<body>
<h1><?php bloginfo('name') ?></h1>
<h2><?php bloginfo('description') ?></h2>
<?php
wp_nav_menu();
?>