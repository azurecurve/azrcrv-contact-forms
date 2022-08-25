<?php
/*
	language functions
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ContactForms;

/**
 * Load language files.
 */
function load_languages() {
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '../assets/languages';
	load_plugin_textdomain( 'azrcrv-cf', false, $plugin_rel_path );
}
