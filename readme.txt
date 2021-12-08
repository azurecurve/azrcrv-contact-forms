=== Contact Forms ===

Description:	Create simple contact forms using the simple contact-form shortcode; supports multiple contact forms on a page.
Version:		1.0.0
Tags:			contact form
Author:			azurecurve
Author URI:		https://development.azurecurve.co.uk/
Contributors:	azurecurve
Plugin URI:		https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/
Download link:	https://github.com/azurecurve/azrcrv-redirect/releases/download/v1.0.0/azrcrv-contact-forms.zip
Donate link:	https://development.azurecurve.co.uk/support-development/
Requires PHP:	5.6
Requires:		1.0.0
Tested:			4.9.99
Text Domain:	azrcrv-cf
Domain Path:	/languages
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Create simple contact forms using the simple contact-form shortcode; supports multiple contact forms on a page.

== Description ==

# Description

A simple contact forms plugin with an options page allowing default settings to be configured; these settings can, in most cases, be overriden when adding a contact form to a page. This plugin supports the use of mutiple contact forms on a page.

Contact forms can be placed on pages using the `simple-contact-forms` shortcode which supports the following parameters:
 * `id` is the only mandatory parameter; a unique id for each contact form on the page must be supplied.
 * `send-from-email` allows the default send from email address to be overridden.
 * `send-from-name` allows the default send from email name to be overriddem.
 * `recipients` is a comma separated list of email address which allows the default recipients to be overridden.
 * `label-name` allows the default label for the name field to be overridden.
 * `label-email` allows the default label for the email field to be overridden.
 * `label-subject` allows the default label for the subject field to be overridden.
 * `label-message` allows the default label for the message field to be overridden.
 * `subject` allows the default subject for the name field to be overridden. If an empty subject is provided, users can free form type a subject. Multiple subjects to allow picking from a drop down list can be supplied, separated with a `|`.
 * `subject-prefix` allows a subject prefix to be added to all subjects when the email is sent and override the default.

Example shortcode usage:
```
	[simple-contact-forms id="contact-us" recipients="bob@example.com,jane@example.com" subject="Sales|Support|Accounts" subject-prefix="Contact us from example.com:"]
```
 
This plugin is multisite compatible, with options set on a per site basis.

== Installation ==

# Installation Instructions

 * Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-contact-forms/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

== Frequently Asked Questions ==

# Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugins languages folder and can also be downloaded from the plugin page on https://development.azurecurve.co.uk; if you do translate this plugin, please sent the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

== Changelog ==

# Changelog

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-contact-forms/releases/v1.0.0)
 * Initial release.

== Other Notes ==

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
 * Add Twitter Cards - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/) / [download](https://github.com/azurecurve/azrcrv-add-twitter-cards/releases/latest/)
 * Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disaable-floc/) / [download](https://github.com/azurecurve/azrcrv-disaable-floc/releases/latest/)
 * Series Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/series-index/) / [download](https://github.com/azurecurve/azrcrv-series-index/releases/latest/)
 * SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
 * Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
 * To Twitter - [details](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/) / [download](https://github.com/azurecurve/azrcrv-to-twitter/releases/latest/)
 * Toggle Show/Hide - [details](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/) / [download](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/)
 * Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
 * URL Shortener - [details](https://development.azurecurve.co.uk/classicpress-plugins/url-shortener/) / [download](https://github.com/azurecurve/azrcrv-url-shortener/releases/latest/)
