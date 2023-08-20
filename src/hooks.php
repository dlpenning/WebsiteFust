<?php

add_action('admin_menu', function() {
    add_menu_page(
        'FUST Admin Page',
        'FUST',
        'administrator',
        'fust',
        function() {
            include __DIR__ . '/pages/fust.php';
        },
        '',
        2
    );
});