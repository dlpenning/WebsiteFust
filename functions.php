<?php
// The absolute theme directory (on harddisk)
define('FUST_THEME_DIR', get_template_directory());


include FUST_THEME_DIR . '/includes/class-fust.php';
include FUST_THEME_DIR . '/includes/post_types/class.news.php';
include FUST_THEME_DIR . '/includes/post_types/class.service.php';

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
add_action('init', ['FUST_Service', 'setup']);

add_action('admin_menu', 'post_remove');


/**
 * Definition of custom logged-in user experience (logic)
 */

// Redirect login to custom login
add_action('init', 'custom_login_redirect');

function custom_login_redirect() {
    global $pagenow;

    // Check if there is any post data. If so, allow the request as normal.
    if (!empty($_POST)) {
        return;
    }
    
    // Check if a $_GET['action'] is set, and if so, load it into $action variable
    $action = (isset($_GET['action'])) ? $_GET['action'] : '';

    // Check if we're on the login page, and ensure the action is not 'logout'
    if ($pagenow == 'wp-login.php' && (!$action || ($action && !in_array($action, ['logout', 'lostpassword', 'rp', 'resetpass'])))) {
        
        // Redirect to the custom login page
        wp_redirect('/fust-login');

        // Stop execution to prevent the page loading for any reason
        exit();
    }
}


/**
 * Create a WordPress user with a generated password.
 * 
 * @since 0.2.2
 */
function create_fust_user_with_generated_password($username, $email, $display_name) {
    if (!is_user_logged_in() || !current_user_can('administrator')) {
        return false;
    }

    // Generate a password
    $password = wp_generate_password();

    // Create user data
    $userdata = array(
        'user_login'    => $username,
        'user_pass'     => $password,
        'user_email'    => $email,
        'display_name'  => $display_name,
        'role'          => 'subscriber', // You can set the user role here
    );

    // Create the user
    $user_id = wp_create_user($userdata['user_login'], $userdata['user_pass'], $userdata['user_email']);

    // Check if the user was created successfully
    if (is_wp_error($user_id)) {
        echo "Error creating user: " . $user_id->get_error_message();
    } else {
        echo "User created successfully! ID: " . $user_id;

        // Send email notification to user and site admin
        wp_new_user_notification($user_id, $password);
    }
}

function fust_get_vxcf_entries() {
    global $wpdb;

    $rows = $wpdb->get_results("SELECT name, value, lead_id FROM wp_vxcf_leads_detail");

    // Initialize an array to store the parsed leads
    $parsedLeads = array();

    // Loop through the query results
    foreach ($rows as $row) {
        $leadId = $row->lead_id;
        $fieldName = $row->name;
        $fieldValue = $row->value;

        // Check if the lead entry exists in the parsedLeads array
        if (!isset($parsedLeads[$leadId])) {
            // If not, initialize a new lead entry
            $parsedLeads[$leadId] = array();
        }

        // Add the field to the lead entry
        $parsedLeads[$leadId][$fieldName] = $fieldValue;
    }

    return $parsedLeads;
}

function fust_get_services() {
    // Setup service query
    $service_query = new WP_Query( [
        'post_type' => 'service'
    ]);

    return $service_query->posts;
}

function service_is_external($post) {
    $external_link = get_post_meta($post->ID, 'external_link', true);

    if ($external_link) {
        return true;
    }

    return false;
}

function service_get_the_custom_permalink($post) {
    if (service_is_external($post)) {
        return get_post_meta($post->ID, 'external_link', true);
    }

    return get_the_permalink($post);
}

function service_get_the_icon($post) {
    $icon = get_post_meta($post->ID, 'icon', true);

    if ($icon) {
        return $icon;
    }

    return 'fa-calculator';
}



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
