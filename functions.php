<?php
// The absolute theme directory (on harddisk)
define('FUST_THEME_DIR', get_template_directory());


include FUST_THEME_DIR . '/includes/class-fust.php';
include FUST_THEME_DIR . '/includes/post_types/class.news.php';

FUST::hooks();

/**
 * FUST Website Theme functions and definitions
 */

function add_fust_stylesheets() {
	wp_enqueue_style('global', get_template_directory_uri() . '/style/global.css');
	wp_enqueue_style('components', get_template_directory_uri() . '/style/components.css');
	wp_enqueue_style('style', get_template_directory_uri() . '/style/style.css');
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

// The default Posts and Comments types are removed because they're not used on this site
function post_remove ()
{ 
  remove_menu_page('edit.php');
  remove_menu_page('edit-comments.php');
}


add_theme_support('post-thumbnails');

add_image_size('largest', 1920, 1080, false);
add_image_size('medium', 1280, 720, false);
add_image_size('smallest', 640, 360, false);
add_image_size('square-medium', 515, 512, false);
add_image_size('square-small', 256, 256, false);

add_action('init', 'add_fust_app_header');
add_action('wp_enqueue_scripts', 'add_fust_stylesheets');
add_action('init', ['FUST_News', 'setup']);

add_action('admin_menu', 'post_remove');




/* ------- Front-end formatting functions --------*/

// Excerpt length limiter
function get_excerpt($limit, $source = null) {
    $excerpt = $source ? get_the_excerpt($source) : get_the_excerpt();
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
    $excerpt = strip_shortcodes($excerpt);
    $excerpt = strip_tags($excerpt);
    $excerpt = substr($excerpt, 0, $limit);
    $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
    $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
    $excerpt = $excerpt.'&hellip;';
    return $excerpt;
}

function get_days_ago($date) {
    $date = new DateTime($date);
    $now = new DateTime();
    $diff = $date->diff($now)->format("%a");
    $days = intval($diff);
    switch ($days) {
        case 0:
            return 'Vandaag';
            break;

        case 1:
            return $days.' dag geleden';
            break;
        
        default:
            return $days.' dagen geleden';
            break;
    }
}

function get_days_ahead($date) {
    $date = new DateTime($date);
    $now = new DateTime();
    $diff = $now->diff($date)->format("%a");
    $days = intval($diff);
    switch ($days) {
        case 0:
            return 'Vandaag';
            break;

        case 1:
            return 'Over '.$days.' dag';
            break;
        
        default:
            return 'Over '.$days.' dagen';
            break;
    }
}

// Line Break Shortcode
function line_break_shortcode() {
	return '<br />';
}
add_shortcode( 'br', 'line_break_shortcode' );

// Estimated reading time
function reading_time() {
    $content = get_post_field('post_content', $post->ID);
    $word_count = str_word_count(strip_tags( $content ));
    $readingtime = ceil($word_count / 200);

    if ($readingtime == 1) {
        $timer = " min";
    } else {
        $timer = " mins";
    }
    
    $totalreadingtime = $readingtime . $timer;

    return $totalreadingtime;
}
