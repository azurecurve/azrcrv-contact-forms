<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ContactForms;

/**
 * Get options including defaults.
 */
function get_option_with_defaults( $option_name ) {

	$defaults = array(
		'defaults' => array(
			'from-email-address' => '',
			'from-email-name'    => '',
			'recipients'         => '',
			'subject-prefix'     => '',
			'subject'            => '',
			'labels'             => array(
				'name'    => esc_html__( 'Your Name', 'azrcrv-cf' ),
				'email'   => esc_html__( 'Your Email', 'azrcrv-cf' ),
				'subject' => esc_html__( 'Subject', 'azrcrv-cf' ),
				'message' => esc_html__( 'Your Message', 'azrcrv-cf' ),
			),
			'success'            => esc_html__( 'Thanks for your message, we&#8217;ll look into it as soon as we can.', 'azrcrv-cf' ),
			'failure'            => esc_html__( 'Unfortunately, there was a problem and your mesage could not be sent.', 'azrcrv-cf' ),
		),
	);

	$options = get_option( $option_name, $defaults );

	$options = recursive_parse_args( $options, $defaults );

	return $options;

}

/**
 * Recursively parse options to merge with defaults.
 */
function recursive_parse_args( $args, $defaults ) {
	$new_args = (array) $defaults;

	foreach ( $args as $key => $value ) {
		if ( is_array( $value ) && isset( $new_args[ $key ] ) ) {
			$new_args[ $key ] = recursive_parse_args( $value, $new_args[ $key ] );
		} else {
			$new_args[ $key ] = $value;
		}
	}

	return $new_args;
}

/**
 * Display Settings page.
 */
function display_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-cf' ) );
	}

	// Retrieve plugin configuration options from database.
	$options = get_option_with_defaults( PLUGIN_HYPHEN );

	echo '<div id="' . esc_attr( PLUGIN_HYPHEN ) . '-general" class="wrap">';

		echo '<h1>';
			echo '<a href="' . esc_url_raw( DEVELOPER_RAW_LINK ) . esc_attr( PLUGIN_SHORT_SLUG ) . '/"><img src="' . esc_url_raw( plugins_url( '../assets/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
			echo esc_html( get_admin_page_title() );
		echo '</h1>';

	// phpcs:ignore.
	if ( isset( $_GET['settings-updated'] ) ) {
		echo '<div class="notice notice-success is-dismissible">
					<p><strong>' . esc_html__( 'Settings have been saved.', 'azrcrv-cf' ) . '</strong></p>
				</div>';
	}

		require_once 'tab-settings.php';
		require_once 'tab-instructions.php';
		require_once 'tab-other-plugins.php';
		require_once 'tabs-output.php';
	?>
		
	</div>
	<?php
}

/**
 * Save settings.
 */
function save_options() {

	// Check that user has proper security level.
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permissions to perform this action', 'azrcrv-cf' ) );
	}

	// Check that nonce field created in configuration form is present.
	if ( ! empty( $_POST ) && check_admin_referer( 'azrcrv-cf', 'azrcrv-cf-nonce' ) ) {

		// Retrieve original plugin options array.
		$options = get_option( 'azrcrv-cf' );

		// update settings.
		// defaults.
		$option_name = 'from-email-address';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_email( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'from-email-name';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}
		$option_name = 'recipients';
		if ( isset( $_POST[ $option_name ] ) ) {

			// in function the recipient string is split and each email individually sanitized
			$recipients = sanitize_recipients( sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) ) );

			$options['defaults'][ $option_name ] = $recipients;
		}

		$option_name = 'subject';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'subject-prefix';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'success';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'failure';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		// labels.
		$option_name = 'label-name';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults']['labels'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'label-email';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults']['labels'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		$option_name = 'label-subject';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults']['labels'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}
		$option_name = 'label-message';
		if ( isset( $_POST[ $option_name ] ) ) {
			$options['defaults']['labels'][ $option_name ] = sanitize_text_field( wp_unslash( $_POST[ $option_name ] ) );
		}

		// Store updated options array to database.
		update_option( 'azrcrv-cf', $options );

		// Redirect the page to the configuration form that was processed.
		wp_safe_redirect( add_query_arg( 'page', 'azrcrv-cf&settings-updated', admin_url( 'admin.php' ) ) );
		exit;
	}

}
