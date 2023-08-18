<?php
/**
 * FUST Website Theme functions and definitions
 */

function add_fust_stylesheets() {
	wp_enqueue_style('global', get_template_directory_uri() . '/style/global.css');
	wp_enqueue_style('components', get_template_directory_uri() . '/style/components.css');
	wp_enqueue_style('style', get_stylesheet_uri());
}

function add_fust_app_header() {
	register_nav_menu('fust-app-header',__('FUST App Header'));
}

// Sets the title of the webpage
function fust_set_title($title)
{
  global $fust_page_title;
  $fust_page_title = $title;
}

// Gets the title of the webpage
function fust_get_title()
{
  global $fust_page_title;
  if (!$fust_page_title) $fust_page_title = '{untitled}';
  return $fust_page_title;
}



add_action( 'init', 'add_fust_app_header' );
add_action( 'wp_enqueue_scripts', 'add_fust_stylesheets' );
