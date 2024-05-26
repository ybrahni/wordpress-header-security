# WP Header Security Plugin

The WP Header Security plugin provides an easy way to add security headers to your WordPress site. Security headers provide an additional layer of security by helping to protect against common web attacks, such as cross-site scripting (XSS), clickjacking, and more.

## Installation

1. Upload the `wordpress-header-security` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress

## Usage

The WP Header Security plugin provides an easy-to-use settings page where you can configure the security headers for your site. To access the settings page, navigate to 'Settings' -> 'WP Header Security' in the WordPress dashboard.

### Available Security Headers

The following security headers are available for configuration:

- X-Frame-Options
- Strict-Transport-Security
- Content-Security-Policy
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

### Recommended Options

Here are the recommended options for each security header:

- X-Frame-Options: `SAMEORIGIN`
- Strict-Transport-Security: `max-age=31536000; includeSubDomains; preload`
- Content-Security-Policy: `default-src 'self'; script-src 'self' 'unsafe-inline'; img-src 'self' data:;`
- X-Content-Type-Options: `nosniff`
- Referrer-Policy: `strict-origin-when-cross-origin`
- Permissions-Policy: `geolocation 'none'; camera 'none'; microphone 'none';`

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

## Compatibility

This plugin has been tested and verified to work on WordPress versions 5.8, 5.9, 6.0, 6.1, and 6.2.

## Contributing

Contributions are welcome! Please feel free to open a pull request or submit an issue.

## TODO
- [ ] Add comments to the code to improve readability and maintainability.
- [ ] Add internationalization support.
- [ ] Add screenshots to to better explain the plugin's functionality and show the results.

## License

The WP Header Security plugin is licensed under the MIT License.
