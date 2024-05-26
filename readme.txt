=== WP Header Security ===
Contributors: Yassine Brahni
Tags: security, headers, http headers, x-frame-options, content-security-policy, strict-transport-security, x-content-type-options, referrer-policy, permissions-policy
Requires at least: 5.8
Tested up to: 6.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

WP Header Security is a plugin that adds an easy-to-use interface for setting security headers in WordPress. Security headers help protect your website from various attacks, such as clickjacking, cross-site scripting (XSS), and code injection.

With this plugin, you can easily set the following security headers:

- X-Frame-Options
- Content-Security-Policy
- Strict-Transport-Security
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

Each header has recommended options that you can choose from in the plugin's settings page. You can also enter your custom values for each header.

### Changes and Enhancements

- Restructured plugin to streamline header management
- Added default header values for `X-Frame-Options`, `Strict-Transport-Security`, `Content-Security-Policy`, `X-Content-Type-Options`, `Referrer-Policy`, and `Permissions-Policy`
- Implemented dynamic header retrieval from WordPress options with fallbacks to default values
- Introduced custom and selectable `Content-Security-Policy` options in settings
- Added a settings page in the admin panel for configuring security headers
- Created dropdowns for selecting predefined values for `X-Frame-Options`, `Strict-Transport-Security`, `X-Content-Type-Options`, `Referrer-Policy`, and `Permissions-Policy`
- Included JavaScript for checking website security headers via securityheaders.com
- Added sanitization functions for custom input values
- Set default options on plugin activation
- Cleaned up options on plugin deactivation
- Ensured proper function availability checks and direct access prevention

== Installation ==

1. Upload the wordpress-header-security folder to the /wp-content/plugins/ directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to the plugin's settings page by clicking on 'WP Header Security' under the 'Settings' menu in the WordPress admin panel.
4. Choose the recommended or custom options for each security header.
5. Click 'Save Changes' to save your settings.

== Frequently Asked Questions ==

= What are security headers? =

Security headers are HTTP response headers that provide an extra layer of security for your website. They help prevent various attacks, such as clickjacking, cross-site scripting (XSS), and code injection.

= How do I know which options to choose for each security header? =

The plugin provides recommended options for each security header that you can choose from. These options are based on industry best practices and will help protect your website from common attacks. However, you can also enter your custom values for each header.

= Will this plugin slow down my website? =

No, this plugin will not slow down your website. Security headers are lightweight and have a minimal impact on website performance.

= Which versions of WordPress are supported? =

This plugin requires WordPress version 5.8 or higher and has been tested up to version 6.3.

== Changelog ==

= 1.0.0 =

Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.