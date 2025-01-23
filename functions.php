<?php
// The absolute theme directory (on harddisk)
define('FUST_THEME_DIR', get_template_directory());

include FUST_THEME_DIR . '/includes/class-fust.php';
include FUST_THEME_DIR . '/includes/post_types/class.news.php';
include FUST_THEME_DIR . '/includes/post_types/class.service.php';
include FUST_THEME_DIR . '/includes/post_types/class.activity.php';
include FUST_THEME_DIR . '/includes/customizer/controls/seperator.php';
include FUST_THEME_DIR . '/includes/class-fust-customizer.php';
include FUST_THEME_DIR . '/includes/customizer/class-fust-page.php';
include FUST_THEME_DIR . '/utils.php';

// Include Stripe's main library file
require_once get_template_directory() . '/stripe-php/init.php';


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
add_action('init', ['FUST_Activity', 'setup']);
add_action('init', ['FUST_Activity', 'setup']);

add_action('admin_post_handle_activity_signup', ['FUST_Activity', 'handle_activity_signup']);
add_action('admin_post_nopriv_handle_activity_signup', ['FUST_Activity', 'handle_activity_signup']);
add_action('wp_ajax_delete_signup', ['FUST_Activity', 'handle_delete_signup']);



add_action('admin_menu', 'post_remove');


function load_scripts() {
    wp_register_script('jquery', get_template_directory_uri() . '/js/jquery-3.5.0.min.js', '', 1, true);
    wp_enqueue_script('jquery');

    wp_enqueue_script('jquery_datetime', get_template_directory_uri() . '/js/jquery-datetime.js');
    wp_register_style('datetimepicker_css', get_template_directory_uri() . '/style/jquery.datetimepicker.min.css');
    wp_enqueue_style('datetimepicker_css');
}

function add_admin_scripts() {
    wp_enqueue_script('util', get_template_directory_uri() . '/js/util.js');
    wp_enqueue_script('admin', get_template_directory_uri() . '/js/admin.js');
}

add_action('wp_enqueue_scripts', 'load_scripts');
add_action('admin_enqueue_scripts', 'add_admin_scripts');


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
function create_fust_user_with_generated_password($username, $email, $display_name, $webhook=FALSE, $guest_member=FALSE) {
    if (!$webhook && (!is_user_logged_in() || !current_user_can('administrator'))) {
        return false;
    }

    $password = wp_generate_password();
    $role = $guest_member ? 'guest-member' : 'subscriber';

    // Create user data
    $userdata = array(
        'user_login'    => $username,
        'user_pass'     => $password,
        'user_email'    => $email,
        'display_name'  => $display_name,
        'role'          => $role,
    );

    // Create the user using WP insert
    $user_id = wp_insert_user($userdata);

    // Check if the user was created successfully
    if (is_wp_error($user_id)) {
        echo "Error creating user: " . $user_id->get_error_message();
        return false;
    }
    echo "User created successfully! ID: " . $user_id;

    // Generate password reset URL
    $reset_key = get_password_reset_key(get_userdata($user_id));
    $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($username), 'login');

    // Load your custom email template
    $standard_user_template = file_get_contents(get_template_directory() . '/email_member_confirmation.html');
    $guest_member_template = file_get_contents(get_template_directory() . '/email_guest_member_confirmation.html');

    $template = $guest_member ? $guest_member_template : $standard_user_template;

    // Replace placeholders in the template
    $message = str_replace(
        array('{NAME}', '{EMAIL}', '{RESET_URL}'),
        array($display_name, $email, $reset_url),
        $template
    );

    // Set the email subject
    $subject = 'Welcome to ' . get_bloginfo('name');

    // Send the email
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email, $subject, $message, $headers);

    return true;
}

/**
 * Fetches all CF7 form entries using a direct database query.
 * 
 * Returns an array of form entries, where each entry includes its ID, form ID, form name,
 * and all associated form fields.
 * 
 * Shape of result:
 * [
 *   {
 *     "id": number,
 *     "form_id": string,
 *     "form_name": string,
 *     ...(form_fields)
 *   },
 *   ...
 * ]
 * 
 * Uses tables:
 * - wp_posts (to fetch CF7 form names with post_type 'wpcf7_contact_form')
 * - wp_vxcf_leads (to get lead metadata and form IDs)
 * - wp_vxcf_leads_detail (to get individual field data for each lead)
 */
function fust_get_vxcf_entries() {
    global $wpdb;

    // Query to fetch all leads, form names, and associated details
    $query = "
        SELECT 
            l.id AS id,
            l.form_id AS form_id,
            p.post_title AS form_name,
            d.name AS field_name,
            d.value AS field_value
        FROM 
            wp_vxcf_leads l
        LEFT JOIN 
            wp_posts p ON p.ID = REPLACE(l.form_id, 'cf_', '') AND p.post_type = 'wpcf7_contact_form'
        INNER JOIN 
            wp_vxcf_leads_detail d ON l.id = d.lead_id
        ORDER BY 
            l.id, d.id;

    ";

    // Execute the query
    $leads = $wpdb->get_results($query);

    // Initialize the array to store structured results
    $parsedEntries = [];
    $currentEntry = null;
    $currentId = null;

    // Parse the results into structured entries
    foreach ($leads as $lead) {
        if ($currentId !== $lead->id) {
            // Save the previous entry if it exists
            if ($currentEntry !== null) {
                $parsedEntries[] = $currentEntry;
            }

            // Initialize a new entry
            $currentEntry = [
                'id' => $lead->id,
                'form_id' => $lead->form_id,
                'form_name' => $lead->form_name ?: 'Unknown Form', // Handle missing form names
            ];
            $currentId = $lead->id;
        }

        // Add field data to the current entry
        $currentEntry[$lead->field_name] = $lead->field_value;
    }

    // Add the last entry to the array
    if ($currentEntry !== null) {
        $parsedEntries[] = $currentEntry;
    }

    return $parsedEntries;
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

function fust_get_activities() {
    // Setup activity query
    $activity_query = new WP_Query([
        'post_type' => 'activity'
    ]);

    return $activity_query->posts;
}

function activity_get_the_date($post) {
    $date = get_post_meta($post->ID, 'date', true);

    if ($date) {
        return $date;
    }

    return '';
}


/**
 * Customizer
 */
// Add page data
global $registered_pages;
$registered_pages = [];

require_once FUST_THEME_DIR . '/includes/setup-pages.php';

function get_this_page_controller()
{
    global $registered_pages;

    foreach ($registered_pages as $page) {
        
        if( $page -> page_id == get_page_template_slug() )
        {
            return $page;
        }
        elseif( $page->page_id == "home" && is_front_page() )
        {
            return $page;
        }

    }

    return null;

}

function get_component_controller( $id )
{
    global $registered_pages;

    foreach ($registered_pages as $page) {
        
        if( $page -> page_id == $id ) return $page;

    }

    return null;
}






/* ------- Formatting functions --------*/


function to_snake_case($input_string) {
    $snake_case_string = preg_replace('/[^A-Za-z0-9]+/', '_', $input_string);
    $snake_case_string = strtolower($snake_case_string);
    $snake_case_string = trim($snake_case_string, '_');

    return $snake_case_string;
}

// Excerpt length limiter
function get_excerpt($limit, $source = null) {
    $excerpt = $source ? get_the_excerpt($source) : get_the_excerpt();
    $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);  // Remove shortcodes
    $excerpt = strip_shortcodes($excerpt);              // Strip shortcodes
    $excerpt = strip_tags($excerpt);                    // Strip HTML tags

    // Only truncate if the excerpt length exceeds the limit
    if (mb_strlen($excerpt) > $limit) {
        $excerpt = substr($excerpt, 0, $limit); 
        $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
        $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
        $excerpt = $excerpt . '&hellip;';                 // Append ellipsis
    } else {
        $excerpt = trim($excerpt);                      // Just trim any excess whitespace
    }

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


// Adds page data to the customizer
function fust_add_page( $page_template_file, $page_unique_id, $display_name, $components, $check_function = null )
{
    $page = new FUST_Customizer_Page( $page_template_file, $page_unique_id, $display_name, $check_function );
    $page->default_sections = $components;
    return $page;
}



// Increase upload size
@ini_set( 'upload_max_size' , '256M' );
@ini_set( 'post_max_size', '256M');
@ini_set( 'max_execution_time', '300' );

function create_stripe_checkout_session() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log('Invalid request method');
        wp_send_json_error(['error' => 'Invalid request method']);
        return;
    }

    // Stripe API key (secret, defined in WP-config)
    \Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

    // Read raw POST data and decode it
    $raw_data = file_get_contents('php://input');
    $form_data = json_decode($raw_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Invalid JSON data');
        wp_send_json_error(['error' => 'Invalid JSON data']);
        return;
    }

    // Access formData from the decoded JSON
    $form_data = isset($form_data['formData']) ? $form_data['formData'] : [];

    // Check if required keys exist in formData
    if (!isset($form_data['your-name']) || !isset($form_data['your-email'])) {
        error_log('Required form fields are missing');
        wp_send_json_error(['error' => 'Required form fields are missing']);
        return;
    }

    $amount = 500; // Amount in cents

    // Build URLs to pass form data
    $success_url = add_query_arg([
        'session_id' => '{CHECKOUT_SESSION_ID}',
        'username' => urlencode($form_data['your-name']),
        'email' => urlencode($form_data['your-email']),
    ], site_url('/thank-you'));
    
    $cancel_url = add_query_arg([
        'username' => urlencode($form_data['your-name']),
        'email' => urlencode($form_data['your-email']),
    ], site_url('/cancel-membership-registration'));

    try {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'ideal'], // Add other payment methods if needed
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'F.U.S.T. Membership fee',
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
            'metadata' => [
                'username' => $form_data['your-name'],
                'email' => $form_data['your-email'],
            ],
        ]);

        wp_send_json_success(['id' => $session->id]);

    } catch (Exception $e) {
        error_log('Exception caught: ' . $e->getMessage());
        wp_send_json_error(['error' => $e->getMessage()]);
    }
}

function apply_as_guest_member() {
    error_log('Test error log on functions load');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        error_log('Invalid request method');
        wp_send_json_error(['error' => 'Invalid request method']);
        return;
    }

    // Read raw POST data and decode it
    $raw_data = file_get_contents('php://input');
    $form_data = json_decode($raw_data, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('Invalid JSON data');
        wp_send_json_error(['error' => 'Invalid JSON data']);
        return;
    }

    // Access formData from the decoded JSON
    $form_data = isset($form_data['formData']) ? $form_data['formData'] : [];

    // Check if required keys exist in formData
    if (
        !isset($form_data['your-name']) ||
        !isset($form_data['your-email']) ||
        !isset($form_data['association'])
    ) {
        error_log('Required form fields are missing');
        wp_send_json_error(['error' => 'Required form fields are missing']);
        return;
    }

    error_log('Test prop');

    [$subject, $message] = generate_guest_member_email($form_data);

    $association_emails = array(
        'Student Party SAM' => 'johndoe@example.com',
        'Magister JFT' => 'johndoe@example.com',
        'Stimulus' => 'johndoe@example.com',
    );

    $email_address = $association_emails[$form_data['association']];

    // Send the email
    $headers = array('Content-Type: text/html; charset=UTF-8');
    wp_mail($email_address, $subject, $message, $headers);

    error_log('Email send done');

    return true;
}

function extract_cf7_dropdown_value($serialized_value) {
    // Unserialize the value
    $unserialized_data = @unserialize($serialized_value);

    // Check if unserialization was successful and data is an array
    if ($unserialized_data !== false && is_array($unserialized_data)) {
        // Extract the first value if it exists
        return isset($unserialized_data[0]) ? $unserialized_data[0] : '';
    }

    // If unserialization fails or the data is not as expected, return the original value
    return $serialized_value;
}


function generate_guest_member_email($form_data) {
    // To send the mail, first load the template
    $template = file_get_contents(get_template_directory() . '/email_new_guest_member.html');

    error_log('Template done');

    // Replace placeholders in the template
    $message = str_replace(
        array('{NAME}', '{GUEST_MEMBER_FULL_NAME}', '{GUEST_MEMBER_EMAIL}', '{GUEST_MEMBER_ASSOCIATION}'),
        array(extract_cf7_dropdown_value($form_data['association']), $form_data['your-name'], $form_data['your-email'], extract_cf7_dropdown_value($form_data['association'])),
        $template
    );

    error_log('Str replace done');
    error_log($message);

    // Set the email subject
    $subject = 'New Guest Member Sign-Up for F.U.S.T.';

    return [$subject, $message];
}

add_action('rest_api_init', function () {
    register_rest_route('stripe/v1', '/create-checkout-session', array(
        'methods' => 'POST',
        'callback' => 'create_stripe_checkout_session',
        'permission_callback' => '__return_true',
    ));

    register_rest_route('guest-member/v1', '/apply-as-guest-member', array(
        'methods' => 'POST',
        'callback' => 'apply_as_guest_member',
        'permission_callback' => '__return_true',
    ));
});



// Stripe Webhook
function add_custom_api_endpoints() {
    add_rewrite_rule('^api/stripe/v1/verify-checkout/?$', 'index.php?verify_checkout=1', 'top');
}
add_action('init', 'add_custom_api_endpoints');

function register_custom_query_vars($vars) {
    $vars[] = 'verify_checkout';
    return $vars;
}
add_filter('query_vars', 'register_custom_query_vars');

function custom_api_endpoint_template_include($template) {
    global $wp_query;

    if (isset($wp_query->query_vars['verify_checkout'])) {
        $webhook_file = get_stylesheet_directory() . '/webhook.php';

        // Ensure the file exists before including it
        if (file_exists($webhook_file)) {
            include $webhook_file;
            exit; // Stop further execution
        } else {
            wp_die('Webhook handler file not found.');
        }
    }

    return $template;
}
add_action('template_include', 'custom_api_endpoint_template_include');


add_filter( 'ure_role_additional_options', 'add_prohibit_access_to_admin_option', 10, 1 );

function add_prohibit_access_to_admin_option($items) {
    $item = URE_Role_Additional_Options::create_item( 'prohibit_admin_access', esc_html__('Prohibit access to admin', 'user-role-editor'), 'init', 'prohibit_access_to_admin' );
    $items[$item->id] = $item;
    
    return $items;
}

function prohibit_access_to_admin() {
    
    if ( is_admin() && !wp_doing_ajax() ) {
        wp_redirect( get_home_url() );
    }
}