<?php
// Include Stripe's main library file
require_once get_template_directory() . '/stripe-php/init.php';

// Configure Stripe client (secrets stored outside of version management)
$stripe = new \Stripe\StripeClient(STRIPE_SECRET_KEY);
$endpoint_secret = STRIPE_WEBHOOK_SECRET;

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch(\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit();
} catch(\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit();
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.completed':
        $session = $event->data->object;

        // Handle the successful payment
        try {
            // Validate and extract metadata
            if (!isset($session->metadata->email) || !isset($session->metadata->username)) {
                throw new Exception('Missing required metadata.');
            }

            $email = sanitize_email($session->metadata->email);
            $name = sanitize_text_field($session->metadata->username);

            // Ensure the email and name are valid
            if (!is_email($email)) {
                throw new Exception('Invalid email address.');
            }

            // Generate a safe username
            $username = to_snake_case($name);

            // Ensure username is unique
            if (username_exists($username)) {
                $username = wp_unique_username($username, $email);
            }

            error_log('Successfully validated Stripe session checkout. Creating user with username: ' . $username . ', email: ' . $email . ' and name ' . $name);

            // Create the user with a generated password
            $success = create_fust_user_with_generated_password($username, $email, $name, TRUE, FALSE);

            if (!$success) {
                throw new Exception('Could not create user.');
            }
        } catch (Exception $e) {
            // Log the error for debugging
            error_log('Error handling checkout.session.completed event: ' . $e->getMessage() . '. Full event data logged on the line below:');
            error_log(print_r($event, TRUE));

            // Send a 500 HTTP response code to indicate a server error
            http_response_code(500);
            echo 'Error processing the payment. Please contact support.';
            exit();
        }

        // Respond with a 200 OK status to acknowledge receipt of the event
        http_response_code(200);
        break;
    default:
        echo 'Received unknown event type ' . $event->type;
        http_response_code(400);
        exit();
}

http_response_code(200);