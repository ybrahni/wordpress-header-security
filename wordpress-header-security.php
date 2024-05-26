<?php
/*
Plugin Name: wordpress-header-security
Plugin URI: https://www.yassinebrahni.com/wordpress/contribution/my-security-headers
Description: A plugin to configure multiple security headers
Version: 1.0.0
Author: Yassine Brahni
Author URI: https://www.linkedin.com/in/ybrahni/
Text Domain: WPHeaderSecurity
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    die('You are not allowed to call this page directly.');
}

// Ensure WordPress functions are available
if (!function_exists('add_action')) {
    die('You are not allowed to call this page directly.');
}

const WPHS_PLUGIN_VERSION = '1.0.0';
const WPHS_STANDARD_VALUE_CSP = "default-src 'self'; script-src 'self' 'unsafe-inline'; img-src 'self' data:;";
const WPHS_STANDARD_VALUE_PERMISSIONS_POLICY = "geolocation 'none'; camera 'none'; microphone 'none';";

function wphs_get_headers(array $headers = array()): array {
    $headers['X-Frame-Options'] = get_option('wphs_X-Frame-Options', 'SAMEORIGIN');
    $headers['Strict-Transport-Security'] = get_option('wphs_Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    $headers['Content-Security-Policy'] = wphs_get_csp_header();
    $headers['X-Content-Type-Options'] = get_option('wphs_X-Content-Type-Options', 'nosniff');
    $headers['Referrer-Policy'] = get_option('wphs_Referrer-Policy', 'strict-origin-when-cross-origin');
    $headers['Permissions-Policy'] = wphs_get_permissions_policy_header();

    return $headers;
}

function wphs_get_csp_header(): string {
    $csp = get_option('wphs_Content-Security-Policy_Custom');
    return empty($csp) ? WPHS_STANDARD_VALUE_CSP : $csp;
}

function wphs_get_permissions_policy_header(): string {
    $pp = get_option('wphs_Permissions-Policy');
    return empty($pp) ? WPHS_STANDARD_VALUE_PERMISSIONS_POLICY : $pp;
}

add_filter('wp_headers', 'wphs_get_headers');

function wphs_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p style="font-size: 20px; font-weight: bold; text-align: center;"><?php _e('Configure the security headers for your website below:', 'WPHeaderSecurity'); ?></p>
        <button id="checkSecurityHeadersButton" style="cursor: pointer; padding: 10px 20px; background: black; color: #fff; font-size: 18px; border-radius: 20px; margin: auto; display: block;"><?php _e('Check Website Security Headers', 'WPHeaderSecurity'); ?></button>
        <form method="post" action="options.php">
            <?php settings_fields('wphs_settings'); ?>
            <?php do_settings_sections('wphs_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <script type="text/javascript">
        document.getElementById("checkSecurityHeadersButton").addEventListener("click", function() {
            let domain = window.location.hostname;
            let securityHeadersURL = `https://securityheaders.com/?q=https://${domain}&followRedirects=on`;
            window.open(securityHeadersURL, "_blank");
        });
    </script>
    <?php
}

function wphs_settings_init() {
    register_setting('wphs_settings', 'wphs_X-Frame-Options', 'sanitize_text_field');
    register_setting('wphs_settings', 'wphs_Strict-Transport-Security', 'sanitize_text_field');
    register_setting('wphs_settings', 'wphs_Content-Security-Policy_Custom', 'sanitize_csp');
    register_setting('wphs_settings', 'wphs_X-Content-Type-Options', 'sanitize_text_field');
    register_setting('wphs_settings', 'wphs_Referrer-Policy', 'sanitize_text_field');
    register_setting('wphs_settings', 'wphs_Permissions-Policy', 'sanitize_text_field');

    add_settings_section('wphs_section', 'Security Headers', function() {}, 'wphs_settings');

    add_settings_field('wphs_X-Frame-Options', 'X-Frame-Options', function() {
        $value = get_option('wphs_X-Frame-Options', 'SAMEORIGIN');
        $choices = array(
            'SAMEORIGIN' => 'SAMEORIGIN (recommended)',
            'ALLOW-FROM' => 'ALLOW-FROM',
            'DENY' => 'DENY'
        );
        echo '<select name="wphs_X-Frame-Options">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }, 'wphs_settings', 'wphs_section');

    add_settings_field('wphs_Strict-Transport-Security', 'Strict-Transport-Security', function() {
        $value = get_option('wphs_Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $choices = array(
            'max-age=31536000; includeSubDomains' => 'max-age=31536000; includeSubDomains (good starting point)',
            'max-age=31536000; includeSubDomains; preload' => 'max-age=31536000; includeSubDomains; preload',
            'max-age=86400; includeSubDomains' => 'max-age=86400; includeSubDomains',
            'max-age=86400; includeSubDomains; preload' => 'max-age=86400; includeSubDomains; preload'
        );
        echo '<select name="wphs_Strict-Transport-Security">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }, 'wphs_settings', 'wphs_section');

    add_settings_field('wphs_X-Content-Type-Options', 'X-Content-Type-Options', function() {
        $value = get_option('wphs_X-Content-Type-Options', 'nosniff');
        $choices = array(
            'nosniff' => 'nosniff (recommended)',
            'none' => 'none'
        );
        echo '<select name="wphs_X-Content-Type-Options">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }, 'wphs_settings', 'wphs_section');

    add_settings_field('wphs_Referrer-Policy', 'Referrer-Policy', function() {
        $value = get_option('wphs_Referrer-Policy', 'strict-origin-when-cross-origin');
        $choices = array(
            'no-referrer' => 'no-referrer',
            'no-referrer-when-downgrade' => 'no-referrer-when-downgrade',
            'origin' => 'origin',
            'origin-when-cross-origin' => 'origin-when-cross-origin',
            'same-origin' => 'same-origin',
            'strict-origin' => 'strict-origin',
            'strict-origin-when-cross-origin' => 'strict-origin-when-cross-origin',
            'unsafe-url' => 'unsafe-url'
        );
        echo '<select name="wphs_Referrer-Policy">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . esc_attr($selected) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }, 'wphs_settings', 'wphs_section');

    add_settings_field('wphs_Content-Security-Policy_Custom', 'Custom Content-Security-Policy', function() {
        $value = get_option('wphs_Content-Security-Policy_Custom', "");
        echo '<label for="wphs_Content-Security-Policy_Custom">Specify Custom Policy: </label>';
        echo '<input type="text" id="wphs_Content-Security-Policy_Custom" name="wphs_Content-Security-Policy_Custom" value="' . esc_attr($value) . '" style="width: 100%;" />';
        echo '<p class="description">Note: If you choose a Custom CSP, other CSP options will be invalid. Please ensure you understand the syntax and implications of your custom policy.</p>';
    }, 'wphs_settings', 'wphs_section');

    add_settings_field('wphs_Permissions-Policy', 'Permissions-Policy', function() {
        $value = get_option('wphs_Permissions-Policy', WPHS_STANDARD_VALUE_PERMISSIONS_POLICY);
        echo '<textarea id="wphs_Permissions-Policy" name="wphs_Permissions-Policy" rows="5" cols="50" style="width: 100%;">' . esc_textarea($value) . '</textarea>';
    }, 'wphs_settings', 'wphs_section');
}

add_action('admin_init', 'wphs_settings_init');
add_action('admin_menu', function() {
    add_options_page('WP Header Security', 'WP Header Security', 'manage_options', 'wphs_settings', 'wphs_settings_page');
});

function sanitize_csp($value) {
    // Remove any HTML tags
    $value = wp_strip_all_tags($value);
    // Removing any special characters that don't belong in CSPs
    $value = preg_replace('/[^a-zA-Z0-9-;:\.\/\* \'"]/', '', $value);
    return $value;
}

function wphs_activate() {
    if (!get_option('wphs_X-Frame-Options')) {
        add_option('wphs_X-Frame-Options', 'SAMEORIGIN');
    }
    if (!get_option('wphs_Strict-Transport-Security')) {
        add_option('wphs_Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
    if (!get_option('wphs_Content-Security-Policy_Custom')) {
        add_option('wphs_Content-Security-Policy_Custom', WPHS_STANDARD_VALUE_CSP);
    }
    if (!get_option('wphs_X-Content-Type-Options')) {
        add_option('wphs_X-Content-Type-Options', 'nosniff');
    }
    if (!get_option('wphs_Referrer-Policy')) {
        add_option('wphs_Referrer-Policy', 'strict-origin-when-cross-origin');
    }
    if (!get_option('wphs_Permissions-Policy')) {
        add_option('wphs_Permissions-Policy', WPHS_STANDARD_VALUE_PERMISSIONS_POLICY);
    }
}
register_activation_hook(__FILE__, 'wphs_activate');

function wphs_deactivate() {
    delete_option('wphs_X-Frame-Options');
    delete_option('wphs_Strict-Transport-Security');
    delete_option('wphs_Content-Security-Policy_Custom');
    delete_option('wphs_X-Content-Type-Options');
    delete_option('wphs_Referrer-Policy');
    delete_option('wphs_Permissions-Policy');
}
register_deactivation_hook(__FILE__, 'wphs_deactivate');
