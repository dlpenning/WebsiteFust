<?php
// Add all pages
global $registered_pages;

// Subpage: footer
array_push( $registered_pages, fust_add_page('null', 'footer', 'Footer', [
    [
        'type' => 'component',
        'name' => 'footer'
    ],
], function() { return true; } ));

// Page: Home page
array_push( $registered_pages, fust_add_page('home.php', 'home', 'Homepage', [
    [
        'type' => 'component',
        'name' => 'masthead'
    ],
], function() { return true; } ));