# Contact Forms
[Contact Forms plugin for ClassicPress Plugin](https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/)

# Description

A simple contact forms plugin with an options page allowing default settings to be configured; these settings can, in most cases, be overriden when adding a contact form to a page. This plugin supports the use of mutiple contact forms on a page.

Contact forms can be placed on pages using the `simple-contact-forms` shortcode which supports the following parameters:
 * **`id`** is the only mandatory parameter; a unique id for each contact form on the page must be supplied.
 * **`send-from-email`** allows the default send from email address to be overridden.
 * **`send-from-name`** allows the default send from email name to be overriddem.
 * **`recipients`** allows the default recipients to be overridden.
 * **`label-name`** allows the default label for the name field to be overridden.
 * **label-email`** allows the default label for the email field to be overridden.
 * **`label-subject`** allows the default label for the subject field to be overridden.
 * **`label-message`** allows the default label for the message field to be overridden.
 * **`subject`** allows the default subject for the name field to be overridden. If an empty subject is provided, users can free form type a subject. Multiple subjects to allow picking from a drop down list can be supplied, separated with a `|`.
 * **`subject-prefix`** allows a subject prefix to be added to all subjects when the email is sent and override the default.

Example shortcode usage:
```
	[simple-contact-forms id="contact-us" recipients="bob@example.com,jane@example.com" subject="Sales|Support|Accounts" subject-prefix="Contact us from example.com:"]
```
 
This plugin is multisite compatible, with options set on a per site basis.

# Installation Instructions

 * Download the plugin from [GitHub](https://github.com/azurecurve/azrcrv-contact-forms/releases/latest/).
 * Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
 * Activate the plugin.
 * Configure relevant settings via the configuration page in the admin control panel (azurecurve menu).

# About azurecurve

**azurecurve** was one of the first plugin developers to start developing for Classicpress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

Some of the other plugins available from **azurecurve** are:
 * Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
 * Add Twitter Cards - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/) / [download](https://github.com/azurecurve/azrcrv-add-twitter-cards/releases/latest/)
 * Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disaable-floc/) / [download](https://github.com/azurecurve/azrcrv-disaable-floc/releases/latest/)
 * Redirect - [details](https://development.azurecurve.co.uk/classicpress-plugins/redirect/) / [download](https://github.com/azurecurve/azrcrv-redirect/releases/latest/)
 * Series Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/series-index/) / [download](https://github.com/azurecurve/azrcrv-series-index/releases/latest/)
 * SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
 * To Twitter - [details](https://development.azurecurve.co.uk/classicpress-plugins/to-twitter/) / [download](https://github.com/azurecurve/azrcrv-to-twitter/releases/latest/)
 * Toggle Show/Hide - [details](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/) / [download](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/)
 * Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
 * URL Shortener - [details](https://development.azurecurve.co.uk/classicpress-plugins/url-shortener/) / [download](https://github.com/azurecurve/azrcrv-url-shortener/releases/latest/)