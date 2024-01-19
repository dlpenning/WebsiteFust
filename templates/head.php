<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <title><?= fust_get_title() ?> - FUST</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,400;0,500;0,700;1,400&family=League+Spartan:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link href="<?= get_template_directory_uri() ?>/assets/fontawesome/css/fontawesome.css" rel="stylesheet">
    <link href="<?= get_template_directory_uri() ?>/assets/fontawesome/css/brands.css" rel="stylesheet">
    <link href="<?= get_template_directory_uri() ?>/assets/fontawesome/css/solid.css" rel="stylesheet">
    <link href="<?= get_template_directory_uri() ?>/assets/fontawesome/css/regular.css" rel="stylesheet">
</head>
<body>
    <div class="body-inner">