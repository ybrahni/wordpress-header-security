<?php
/*
Plugin Name: wordpress-header-security
Plugin URI: https://www.yassinebrahni.com/wordpress/contribution/my-security-headers
Description: A plugin to configure multiple security headers
Version: 1.0
Author: Yassine Brahni
Author URI: https://www.yassinebrahni.com/
License: GPLv2 or later
*/
function wp_header_security() {
    $headers = array(
        'X-Frame-Options' => '',
        'Strict-Transport-Security' => '',
        'Content-Security-Policy' => '',
        'X-Content-Type-Options' => '',
        'Referrer-Policy' => '',
        'Permissions-Policy' => '',
    );

    foreach ($headers as $header => $default_value) {
        $value = get_option('wp_header_security_' . $header, $default_value);
        if ($value) {
            header($header . ': ' . $value);
        }
    }
}

add_action('send_headers', 'wp_header_security');

function wp_header_security_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <p style=" font-size: 20px; font-weight: bold; text-align: center; "><?php _e( 'Configure the security headers for your website below:', 'WPHeaderSecurity' ); ?></p>
        <button id="checkSecurityHeadersButton" style="cursor: pointer; padding: 10px 20px; background: black; color: #fff; font-size: 18px; border-radius: 20px; margin: auto; display: block;">Check Website Security Headers</button>
        <form method="post" action="options.php">
            <?php settings_fields('wp_header_security'); ?>
            <?php do_settings_sections('wp_header_security'); ?>
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
function wp_header_security_settings_init() {
    register_setting('wp_header_security', 'wp_header_security_X-Frame-Options');
    register_setting('wp_header_security', 'wp_header_security_Strict-Transport-Security');
    register_setting('wp_header_security', 'wp_header_security_Content-Security-Policy');
    register_setting('wp_header_security', 'wp_header_security_X-Content-Type-Options');
    register_setting('wp_header_security', 'wp_header_security_Referrer-Policy');
    register_setting('wp_header_security', 'wp_header_security_Permissions-Policy');
    add_settings_section('wp_header_security_section', 'Security Headers', function() {}, 'wp_header_security');


    add_settings_field('wp_header_security_X-Frame-Options', 'X-Frame-Options', function() {
        $value = get_option('wp_header_security_X-Frame-Options', 'SAMEORIGIN');
        $choices = array(
            'SAMEORIGIN' => 'SAMEORIGIN (recommended)',
            'ALLOW-FROM' => 'ALLOW-FROM',
            'DENY' => 'DENY'
        );
        echo '<select name="wp_header_security_X-Frame-Options">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr( $key ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';
        echo '<p class="description">The X-Frame-Options header restricts which websites can embed your site\'s content in an iframe. 
                <strong>SAMEORIGIN</strong> allows only the same domain to embed content. 
                <strong>ALLOW-FROM</strong> allows a specific domain to embed content. 
                <strong>DENY</strong> blocks all domains from embedding content.</p>';
    }, 'wp_header_security', 'wp_header_security_section');

    add_settings_field('wp_header_security_Strict-Transport-Security', 'Strict-Transport-Security', function() {
        $value = get_option('wp_header_security_Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $choices = array(
            'max-age=31536000; includeSubDomains' => 'max-age=31536000; includeSubDomains (good starting point)',
            'max-age=31536000; includeSubDomains; preload' => 'max-age=31536000; includeSubDomains; preload',
            'max-age=86400; includeSubDomains' => 'max-age=86400; includeSubDomains',
            'max-age=86400; includeSubDomains; preload' => 'max-age=86400; includeSubDomains; preload'
        );
        echo '<select name="wp_header_security_Strict-Transport-Security">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';
        echo '<p class="description"> Strict-Transport-Security is an HTTP response header that instructs the browser to only use HTTPS to access the website. This can help protect your website from certain types of attacks, such as man-in-the-middle attacks. The value of this header should include the maximum age, in seconds, that the browser should remember to only use HTTPS.</p>';
    }, 'wp_header_security', 'wp_header_security_section');

    add_settings_field('wp_header_security_X-Content-Type-Options', 'X-Content-Type-Options', function() {
        $value = get_option('wp_header_security_X-Content-Type-Options', 'nosniff');
        $choices = array(
            'nosniff' => 'nosniff (recommended)',
            'none' => 'none'
        );
        echo '<select name="wp_header_security_X-Content-Type-Options">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';
        echo '<p class="description">The X-Content-Type-Options header is a security feature that helps to prevent content sniffing, which is the process of guessing the content type of a file based on its contents. This feature is particularly useful for preventing certain types of attacks, such as cross-site scripting (XSS) and code injection, that can occur when a server sends a file with an unexpected content type. By setting the X-Content-Type-Options header to "nosniff", the browser is instructed to always use the declared content type of the file and not to guess or override it. This can help to ensure that the browser doesn\'t execute malicious content disguised as a different file type.</p>';
    }, 'wp_header_security', 'wp_header_security_section');

    add_settings_field('wp_header_security_Referrer-Policy', 'Referrer-Policy', function() {
        $value = get_option('wp_header_security_Referrer-Policy', 'strict-origin-when-cross-origin');
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
        echo '<select name="wp_header_security_Referrer-Policy">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';
        echo '<p class="description">It\'s recommended to use \'strict-origin-when-cross-origin\' or \'same-origin\' for most websites, but the best choice depends on the specific security requirements and functionality of the website.</p>';
    }, 'wp_header_security', 'wp_header_security_section');

    add_settings_field('wp_header_security_Content-Security-Policy', 'Content-Security-Policy', function() {
        $value = get_option('wp_header_security_Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline'; img-src 'self' data:;");

        // Dropdown selection
        $choices = array(
            "default-src 'self'; script-src 'self' 'unsafe-inline'; img-src 'self' data:;" => "default-src 'self'; script-src 'self' 'unsafe-inline'; img-src 'self' data:;",
            "default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';" => "default-src 'none'; script-src 'self'; connect-src 'self'; img-src 'self'; style-src 'self';",
            "default-src 'self'; font-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;" => "default-src 'self'; font-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;"
        );
        echo '<select name="wp_header_security_Content-Security-Policy_Select">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $label. '</option>';
        }
        echo '</select>';
    }, 'wp_header_security', 'wp_header_security_section');


    add_settings_field('wp_header_security_Permissions-Policy', 'Permissions-Policy', function() {
        $value = get_option('wp_header_security_Permissions-Policy', "geolocation 'none'; camera 'none'; microphone 'none';");

        // Dropdown selection
        $choices = array(
            "geolocation 'none'; camera 'none'; microphone 'none';" => "geolocation 'none'; camera 'none'; microphone 'none'; (recommended)",
            "geolocation 'self'; camera 'self'; microphone 'self';" => "geolocation 'self'; camera 'self'; microphone 'self';",
            "geolocation 'none'; camera 'self'; microphone 'self';" => "geolocation 'none'; camera 'self'; microphone 'self';",
            "geolocation 'self'; camera 'none'; microphone 'none';" => "geolocation 'self'; camera 'none'; microphone 'none';"
        );
        echo '<select name="wp_header_security_Permissions-Policy_Select">';
        foreach ($choices as $key => $label) {
            $selected = ($value == $key) ? 'selected' : '';
            echo '<option value="' . $key . '" ' . $selected . '>' . $label . '</option>';
        }
        echo '</select>';
    }, 'wp_header_security', 'wp_header_security_section');


}

add_action('admin_init', 'wp_header_security_settings_init');
add_action('admin_menu', function() {
    add_options_page('WP Header Security', 'WP Header Security', 'manage_options', 'wp_header_security', 'wp_header_security_settings_page');
});

