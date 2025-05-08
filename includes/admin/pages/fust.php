<?php

// Include admin-util from one dir up
require_once dirname(__DIR__) . '/admin-util.php';

$entries = fust_get_vxcf_entries();

function validate_post_args($argument, $type) {
    if (empty(trim($argument))) {
        return -1;
    }

    if ($type == 'number' && !preg_match('/^[0-9]+$/', trim($argument))) {
        return -1;
    }

    return $argument;
}

function user_exists($email) {
    return email_exists($email);
}


/**
 * Approve POST requests to this page and handle them accordingly.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine the action type and dispatch to the appropriate handler
    $action = null;

    // Check for row-level actions
    if (isset($_POST['create-account-id']) || isset($_POST['create-guest-account-id']) || isset($_POST['send-confirmation-id'])) {
        handle_row_level_actions($_POST);
    }
    // Check for setting actions
    elseif (isset($_POST['save-associations'])) {
        handle_setting_actions($_POST);
    }
    // Unknown or invalid request
    else {
        echo '<b>An error occurred handling your request. Please contact a developer.</b>';
        return false;
    }
}


// Group entries by form type
$grouped_entries = [];
foreach ($entries as $key => $entry) {
    $form_name = $entry['form_name'] ?? 'Unknown Form';
    if (!isset($grouped_entries[$form_name])) {
        $grouped_entries[$form_name] = [];
    }
    $grouped_entries[$form_name][$key] = $entry;
}

// Get associations
$associations = get_option('joint_associations', []);
?>

<script src="https://unpkg.com/@tailwindcss/browser@4"></script>
<style>
    .fust-dashboard a {
        color: white;
    }

    .fust-dashboard .card-title h2 {
        margin: 0;
    }
    
    .fust-dashboard tbody button {
        font-weight: 600;
    }

    .fust-dashboard .fust-dashboard-form-title:not(:nth-child(2)) {
        margin-top: 2rem;
    }
</style>


<div class="fust-dashboard max-w-7xl p-6 space-y-8 mx-auto">
    <div class="flex items-center justify-start space-x-2">
        <img src="<?= get_site_icon_url() ?>" alt="Site Logo" class="w-10 h-10">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
    </div>
    <!-- Top Cards -->

    <span class="inline-block text-xl font-bold mb-4 text-gray-800">Content</span>

    <div class="flex space-x-4">
        <div class="w-full bg-white rounded-xl p-6 border border-gray-300">
            <div class="flex items-center justify-between mb-4 card-title">
                <div class="flex items-center space-x-2">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <h2 class="text-xl font-bold text-gray-800">Activities</h2>
                </div>
                <!-- Tag with the count of activities -->
                <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 rounded-md font-semibold"><?= wp_count_posts('activity')->publish ?></span>
            </div>
            <a href="<?= admin_url('edit.php?post_type=activity') ?>" 
                class="inline-block text-center bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded-md">
                Manage activities
            </a>
        </div>

        <div class="w-full bg-white rounded-xl p-6 border border-gray-300">
            <div class="flex items-center justify-between mb-4 card-title">
                <div class="flex items-center space-x-2">
                    <span class="dashicons dashicons-desktop"></span>
                    <h2 class="text-xl font-bold text-gray-800">News</h2>
                </div>
                <!-- Tag with the count of news -->
                <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 rounded-md font-semibold"><?= wp_count_posts('news')->publish ?>
            </div>
            <a href="<?= admin_url('edit.php?post_type=news') ?>"
                class="inline-block text-center bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded-md">
                Manage news
            </a>
        </div>

        <div class="w-full bg-white rounded-xl p-6 border border-gray-300">
            <div class="flex items-center justify-between mb-4 card-title">
                <div class="flex items-center space-x-2">
                    <span class="dashicons dashicons-button"></span>
                    <h2 class="text-xl font-bold text-gray-800">Services</h2>
                </div>
                <!-- Tag with the count of services -->
                <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 rounded-md font-semibold"><?= wp_count_posts('service')->publish ?>
            </div>
            <a href="<?= admin_url('edit.php?post_type=service') ?>" 
                class="inline-block text-center bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded-md">
                Manage services
            </a>
        </div>
    </div>

    <!-- Joined Associations Section -->
    <span class="inline-block text-xl font-bold mb-4 text-gray-800">Settings</span>

    <div class="bg-white rounded-xl p-6 border border-gray-300">
        <div class="flex items-center justify-between mb-4 card-title">
            <div class="flex items-center space-x-3">
                <span class="dashicons dashicons-admin-generic"></span>
                <h2 class="text-xl font-bold text-gray-800">Joined Associations</h2>
            </div>
            <!-- Tag with the count of jouned associations -->
            <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 rounded-md font-semibold"><?= count($associations) ?></span>
        </div>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="space-y-2 joint-associations-form-inner">
                <?php if (!empty($associations)): ?>
                    <?php foreach ($associations as $index => $assoc): ?>
                        <div class="bg-white rounded-md p-4 border border-gray-300">
                            <div class="flex items-start space-x-4">
                                <!-- Logo Section -->
                                <div class="logo-section flex-shrink-0">
                                    <?php if (!empty($assoc['logo_url'])): ?>
                                        <img src="<?= esc_url($assoc['logo_url']) ?>" alt="Logo" class="h-20 w-20 object-cover rounded-md logo-preview mb-2">
                                    <?php else: ?>
                                        <img src="" alt="Logo Preview" class="h-20 w-20 object-cover rounded-md logo-preview mb-2" style="display:none;">
                                    <?php endif; ?>
                                    <input type="text" id="logo-url-<?= $index ?>" name="associations[<?= $index ?>][logo_url]" 
                                        value="<?= esc_url($assoc['logo_url']) ?>" class="hidden" />
                                    <button type="button" class="bg-purple-400 hover:bg-purple-500 text-white px-4 py-2 rounded-md select-logo" 
                                        data-target="logo-url-<?= $index ?>">Select Logo</button>
                                </div>

                                <!-- Content Section -->
                                <div class="flex-grow">
                                    <div class="flex space-x-4 mb-4">
                                        <!-- Name -->
                                        <div class="w-full">
                                            <label for="name-<?= $index ?>" class="block text-sm font-medium text-gray-700">Name</label>
                                            <input type="text" id="name-<?= $index ?>" name="associations[<?= $index ?>][name]" 
                                                value="<?= esc_attr($assoc['name']) ?>" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                                placeholder="Association Name" />
                                        </div>

                                        <!-- Website -->
                                        <div class="w-full">
                                            <label for="website-<?= $index ?>" class="block text-sm font-medium text-gray-700">Website</label>
                                            <input type="url" id="website-<?= $index ?>" name="associations[<?= $index ?>][website]" 
                                                value="<?= esc_url($assoc['website'] ?? '') ?>" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                                placeholder="Website URL (e.g., https://example.com)" />
                                        </div>

                                        <!-- Email -->
                                        <div class="w-full">
                                            <label for="email-<?= $index ?>" class="block text-sm font-medium text-gray-700">Email for guest member confirmation</label>
                                            <input type="email" id="email-<?= $index ?>" name="associations[<?= $index ?>][email]" 
                                                value="<?= esc_attr($assoc['email']) ?>" 
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                                placeholder="Contact Email" />
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="description-<?= $index ?>" class="block text-sm font-medium text-gray-700">Description</label>
                                        <textarea id="description-<?= $index ?>" name="associations[<?= $index ?>][description]" 
                                            rows="2" 
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                                            placeholder="Description"><?= esc_textarea($assoc['description']) ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="text-right mt-4">
                                <button type="button" class="text-red-600 hover:underline cursor-pointer remove-row">Remove</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-gray-500 text-center bg-gray-50 p-4 rounded-md col-span-full">
                        No associations added yet.
                    </div>
                <?php endif; ?>
            </div>

            <!-- Add New Association Button -->
            <div class="mt-6">
                <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md add-row">
                    Add Association
                </button>
            </div>

            <!-- Save Associations Button -->
            <div class="mt-6">
                <button type="submit" name="save-associations" class="bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded-md">
                    Save Associations
                </button>
            </div>
        </form>
    </div>


    <!-- Account Creation Section -->
    <span class="inline-block text-xl font-bold mb-4 text-gray-800">Registrations</span>

    <div class="bg-white rounded-xl p-6 border border-gray-300">
        <div class="flex items-center justify-between mb-4 card-title">
            <div class="flex items-center space-x-3">
                <span class="dashicons dashicons-admin-users"></span>
                <h2 class="text-xl font-bold text-gray-800">Account Creation</h2>
            </div>
            <!-- Tag with the count of registrations -->
            <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 rounded-md font-semibold"><?= count($entries) ?></span>
        </div>

        <!-- Caption section -->
        <p class="text-gray-500 mb-4">Here you can approve registrations and create accounts for new users. To see more info and delete registrations, please press the button below.</p>
        <a href="<?= menu_page_url('vxcf_leads', false) ?>" 
            class="inline-block text-center bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded-md">
            Manage registrations
        </a>

        <?php foreach ($grouped_entries as $form_name => $form_entries) { ?>
            <h2 class="fust-dashboard-form-title text-xl font-medium mt-8 mb-4 text-gray-800">
                <span class="text-sm bg-purple-200 text-purple-700 px-2 py-1 mr-2 rounded-md font-semibold">FORM</span>
                <?= esc_html($form_name) ?>
            </h2>
            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) . '?page=fust' ?>" method="POST">
                <div class="relative overflow-x-auto rounded-md border border-gray-200">
                    <table class="table-auto border-collapse w-full text-left">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 font-semibold">ID</th>
                                <th class="px-4 py-3 font-semibold">Name</th>
                                <th class="px-4 py-3 font-semibold">Email</th>
                                <?php if ($form_name == 'Register as guest member') { ?>
                                    <th class="px-4 py-3 font-semibold">Association</th>
                                <?php } ?>
                                <th class="px-4 py-3 font-semibold"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($form_entries as $key => $entry) { ?>
                                <tr class="border-t border-gray-200">
                                    <td class="px-4 py-2"><?= esc_html($key + 1) ?></td>
                                    <td class="px-4 py-2">
                                        <?= array_key_exists('your-name', $entry) ? esc_html($entry['your-name']) : '<b><i>None</i></b>' ?>
                                    </td>
                                    <td class="px-4 py-2">
                                        <?= array_key_exists('your-email', $entry) ? esc_html($entry['your-email']) : '<b><i>None</i></b>' ?>
                                    </td>
                                    <?php if ($form_name == 'Register as guest member') { ?>
                                        <td class="px-4 py-2">
                                            <?= array_key_exists('association', $entry) ? esc_html(extract_cf7_dropdown_value($entry['association'])) : '<b><i>None</i></b>' ?>
                                        </td>
                                    <?php } ?>
                                    <td class="px-4 py-2 text-right">
                                        <?php if ($form_name == 'Register as member') { ?>
                                            <button
                                                type="submit" 
                                                name="create-account-id" 
                                                value="create-account-<?= esc_attr($key) ?>"
                                                <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                                class="bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded border border-purple-400 hover:border-purple-500 disabled:bg-gray-100 disabled:border disabled:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed hover:cursor-pointer"
                                            >
                                                Create account
                                            </button>
                                        <?php } elseif ($form_name == 'Register as guest member') { ?>
                                            <button
                                                type="submit" 
                                                name="create-guest-account-id" 
                                                value="create-guest-account-<?= esc_attr($key) ?>"
                                                <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                                class="bg-purple-400 hover:bg-purple-500 text-white font-semibold py-2 px-4 rounded border border-purple-400 hover:border-purple-500 disabled:bg-gray-100 disabled:border disabled:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed hover:cursor-pointer"
                                            >
                                                Create guest account
                                            </button>
                                            <button
                                                type="submit"
                                                name="send-confirmation-id"
                                                value="send-confirmation-<?= esc_attr($key) ?>"
                                                <?= !array_key_exists('your-email', $entry) || user_exists($entry['your-email']) ? 'disabled' : '' ?> 
                                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded border border-gray-500 hover:border-gray-600 disabled:bg-gray-100 disabled:border disabled:border-gray-300 disabled:text-gray-300 disabled:cursor-not-allowed hover:cursor-pointer" 
                                            >
                                                Send Confirmation Email
                                            </button>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="action" value="approve-user">
            </form>
        <?php } ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Function to initialize media uploader event listener
    function initializeMediaUploader() {
        // Remove existing event listeners to avoid duplicates
        const clonedButtons = document.querySelectorAll('.select-logo');
        clonedButtons.forEach((button) => {
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
        });

        // Add event listeners to all "select-logo" buttons
        document.querySelectorAll('.select-logo').forEach((button) => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const targetInput = document.querySelector(`#${this.dataset.target}`);
                const mediaUploader = wp.media({
                    title: 'Select a Logo',
                    button: {
                        text: 'Use this logo'
                    },
                    multiple: false // Allow single file selection
                });

                mediaUploader.on('select', function () {
                    const attachment = mediaUploader.state().get('selection').first().toJSON();
                    targetInput.value = attachment.url;

                    // Update preview
                    const preview = targetInput.closest('.logo-section').querySelector('.logo-preview');
                    if (preview) {
                        preview.src = attachment.url;
                        preview.style.display = 'block'; // Show the preview
                    }
                });

                mediaUploader.open();
            });
        });
    }

    // Initialize the media uploader for existing buttons
    initializeMediaUploader();

    // Reinitialize media uploader for dynamically added elements
    const container = document.querySelector('.joint-associations-form-inner');
    const addRowBtn = document.querySelector('.add-row');

    addRowBtn.addEventListener('click', function () {
        const index = container.children.length;
        const card = document.createElement('div');
        card.classList.add('bg-white', 'rounded-md', 'p-4', 'border', 'border-gray-300');

        card.innerHTML = `
            <div class="flex items-start space-x-4">
                <!-- Logo Section -->
                <div class="logo-section flex-shrink-0">
                    <img src="" alt="Logo Preview" class="h-20 w-20 object-cover rounded-md logo-preview mb-2" style="display:none;">
                    <input type="text" id="logo-url-${index}" name="associations[${index}][logo_url]" class="hidden" />
                    <button type="button" class="bg-purple-400 hover:bg-purple-500 text-white px-4 py-2 rounded-md select-logo" 
                        data-target="logo-url-${index}">Select Logo</button>
                </div>

                <!-- Content Section -->
                <div class="flex-grow">
                    <div class="flex space-x-4 mb-4">
                        <!-- Name -->
                        <div class="w-full">
                            <label for="name-${index}" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" id="name-${index}" name="associations[${index}][name]" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                placeholder="Association Name" />
                        </div>

                        <!-- Website -->
                        <div class="w-full">
                            <label for="website-${index}" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" id="website-${index}" name="associations[${index}][website]" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                placeholder="Website URL (e.g., https://example.com)" />
                        </div>

                        <!-- Email -->
                        <div class="w-full">
                            <label for="email-${index}" class="block text-sm font-medium text-gray-700">Email for guest member confirmation</label>
                            <input type="email" id="email-${index}" name="associations[${index}][email]" 
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" 
                                placeholder="Contact Email" />
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description-${index}" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description-${index}" name="associations[${index}][description]" 
                            rows="2" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                            placeholder="Description"></textarea>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="text-right mt-4">
                <button type="button" class="text-red-600 hover:underline remove-row">Remove</button>
            </div>
        `;

        container.appendChild(card);

        // Reinitialize media uploader for the new card
        initializeMediaUploader();
    });

    // Delegate remove-row clicks
    container.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('.bg-white').remove();
        }
    });
});
</script>
