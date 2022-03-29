<?php
/**
 * ------------------------------------------------------------------------------
 * Plugin Name: Contact Forms
 * Description: Create simple contact forms using the simple contact-form shortcode; supports multiple contact forms on a page.
 * Version: 1.0.1
 * Author: azurecurve
 * Author URI: https://development.azurecurve.co.uk/classicpress-plugins/
 * Plugin URI: https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/
 * Text Domain: azrcrv-cf
 * Domain Path: /languages
 * ------------------------------------------------------------------------------
 * This is free software released under the terms of the General Public License,
 * version 2, or later. It is distributed WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. Full
 * text of the license is available at https://www.gnu.org/licenses/rrl-2.0.html.
 * ------------------------------------------------------------------------------
 */

// Declare the namespace.
namespace azurecurve\ContactForms;

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// include plugin menu.
require_once dirname( __FILE__ ) . '/pluginmenu/menu.php';
add_action( 'admin_init', 'azrcrv_create_plugin_menu_cf' );

// include update client.
require_once dirname( __FILE__ ) . '/libraries/updateclient/UpdateClient.class.php';

/**
 * Setup registration activation hook, actions, filters and shortcodes.
 *
 * @since 1.0.0
 */

// add actions.
add_action( 'admin_menu', __NAMESPACE__ . '\\create_admin_menu' );
add_action( 'admin_init', __NAMESPACE__ . '\\register_admin_styles' );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\enqueue_admin_styles' );
add_action( 'init', __NAMESPACE__ . '\\register_frontend_styles' );
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_frontend_styles' );
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_languages' );
add_action( 'admin_post_azrcrv_cf_save_options', __NAMESPACE__ . '\\save_options' );
add_action( 'init', __NAMESPACE__ . '\\process_form' );

// add filters.
add_filter( 'plugin_action_links', __NAMESPACE__ . '\\add_plugin_action_link', 10, 2 );
add_filter( 'codepotent_update_manager_image_path', __NAMESPACE__ . '\\custom_image_path' );
add_filter( 'codepotent_update_manager_image_url', __NAMESPACE__ . '\\custom_image_url' );

// add shortcodes.
add_shortcode( 'simple-contact-form', __NAMESPACE__ . '\\display_contact_form' );

/**
 * Register admin styles.
 *
 * @since 1.0.0
 */
function register_admin_styles() {
	wp_register_style( 'azrcrv-cf-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), '1.0.0' );
}

/**
 * Enqueue admin styles.
 *
 * @since 1.0.0
 */
function enqueue_admin_styles() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'azrcrv-cf' ) {
		wp_enqueue_style( 'azrcrv-cf-admin-styles' );
	}
}

/**
 * Register frontend styles.
 *
 * @since 1.0.0
 */
function register_frontend_styles() {
	wp_register_style( 'azrcrv-cf-styles', plugins_url( 'assets/css/styles.css', __FILE__ ), array(), '1.0.0' );
}

/**
 * Enqueue frontend styles.
 *
 * @since 1.0.0
 */
function enqueue_frontend_styles() {
	wp_enqueue_style( 'azrcrv-cf-styles' );
}

/**
 * Load language files.
 *
 * @since 1.0.0
 */
function load_languages() {
	$plugin_rel_path = basename( dirname( __FILE__ ) ) . '/languages';
	load_plugin_textdomain( 'azrcrv-cf', false, $plugin_rel_path );
}

/**
 * Get options including defaults.
 *
 * @since 1.0.0
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
 *
 * @since 1.0.0
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
 * Add action link on plugins page.
 *
 * @since 1.0.0
 */
function add_plugin_action_link( $links, $file ) {
	static $this_plugin;

	if ( ! $this_plugin ) {
		$this_plugin = plugin_basename( __FILE__ );
	}

	if ( $file == $this_plugin ) {
		$settings_link = '<a href="' . esc_url_raw( admin_url( 'admin.php?page=azrcrv-cf' ) ) . '"><img src="' . esc_url_raw( plugins_url( '/pluginmenu/images/logo.svg', __FILE__ ) ) . '" style="padding-top: 2px; margin-right: -5px; height: 16px; width: 16px;" alt="azurecurve" />' . esc_html__( 'Settings', 'azrcrv-cf' ) . '</a>';
		array_unshift( $links, $settings_link );
	}

	return $links;
}

/**
 * Custom plugin image path.
 *
 * @since 1.0.0
 */
function custom_image_path( $path ) {
	if ( strpos( $path, 'azrcrv-contact-forms' ) !== false ) {
		$path = plugin_dir_path( __FILE__ ) . 'assets/pluginimages';
	}
	return $path;
}

/**
 * Custom plugin image url.
 *
 * @since 1.0.0
 */
function custom_image_url( $url ) {
	if ( strpos( $url, 'azrcrv-contact-forms' ) !== false ) {
		$url = plugin_dir_url( __FILE__ ) . 'assets/pluginimages';
	}
	return $url;
}

/**
 * Add to menu.
 *
 * @since 1.0.0
 */
function create_admin_menu() {

	add_submenu_page(
		'azrcrv-plugin-menu',
		esc_html__( 'Contact Form Settings', 'azrcrv-cf' ),
		esc_html__( 'Contact Forms', 'azrcrv-cf' ),
		'manage_options',
		'azrcrv-cf',
		__NAMESPACE__ . '\\display_options'
	);

}

/**
 * Load admin css.
 *
 * @since 1.0.0
 */
function load_admin_style() {
	wp_register_style( 'azrcrv-cf-css', plugins_url( 'assets/css/admin.css', __FILE__ ), false, '1.0.0' );
	wp_enqueue_style( 'azrcrv-cf-css' );
}

/**
 * Display Settings page.
 *
 * @since 1.0.0
 */
function display_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'azrcrv-cf' ) );
	}

	global $wpdb;

	// Retrieve plugin configuration options from database.
	$options = get_option_with_defaults( 'azrcrv-cf' );

	echo '<div id="azrcrv-cf-general" class="wrap">';

	?>
		<h1>
			<?php
				echo '<a href="https://development.azurecurve.co.uk/classicpress-plugins/"><img src="' . esc_url_raw( plugins_url( '/pluginmenu/images/logo.svg', __FILE__ ) ) . '" style="padding-right: 6px; height: 20px; width: 20px;" alt="azurecurve" /></a>';
				echo esc_html( get_admin_page_title() );
			?>
		</h1>
		<?php

		// outputting message not it a form so nonce not required.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['settings-updated'] ) ) {
			echo '<div class="notice notice-success is-dismissible">
					<p><strong>' . esc_html__( 'Settings have been saved.', 'azrcrv-cf' ) . '</strong></p>
				</div>';
		}

		$tab_1 = '<h2>' . esc_html__( 'Defaults', 'azrcrv-cf' ) . '</h2>
				
				<table class="form-table">
					
					<tr>
					
						<th scope="row">
							<label for="from-email-address">
								' . esc_html__( 'From Email Address', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="from-email-address" type="email" id="from-email-address" value="' . esc_attr( $options['defaults']['from-email-address'] ) . '" class="regular-text" />
							<p class="description">' . esc_html__( 'This will be used as the from email address; leave blank to use the senders email address.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="from-email-name">
								' . esc_html__( 'From Email Name', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="from-email-name" type="text" id="from-email-name" value="' . esc_attr( $options['defaults']['from-email-name'] ) . '" class="regular-text" />
							<p class="description">' . esc_html__( 'This will be used as the name for the from email name; leave blank to use the senders email name.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="recipients">
								' . esc_html__( 'Recipients', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="recipients" type="email" id="recipients" value="' . esc_attr( $options['defaults']['recipients'] ) . '" class="large-text" multiple />
							<p class="description">' . esc_html__( 'This will be used as the recipient email address (separate with commas to specify more than one recipient); leave blank to use the admin email address.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="subject-prefix">
								' . esc_html__( 'Subject Prefix', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="subject-prefix" type="text" id="subject" value="' . esc_attr( $options['defaults']['subject-prefix'] ) . '" class="regular-text" />
							<p class="description">' . sprintf( esc_html__( 'Prefix applied to every email subject; can be overridden using the %s parameter in the shortcode.', 'azrcrv-cf' ), '<strong>subject-prefix</strong>' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="subject">
								' . esc_html__( 'Subject', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="subject" type="text" id="subject" value="' . esc_attr( $options['defaults']['subject'] ) . '" class="regular-text" />
							<p class="description">' . sprintf( esc_html__( 'This will be used as the subject; leave blank for free-form entry; for a drop down list, separate items with a %s (e.g. "Sales|Support|Accounts & Billing").', 'azrcrv-cf' ), '|' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="success">
								' . esc_html__( 'Success', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="success" type="text" id="success" value="' . esc_attr( $options['defaults']['success'] ) . '" class="large-text" />
							<p class="description">' . esc_html__( 'Message to display to users for successfully sent message.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="failure">
								' . esc_html__( 'Failure', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="failure" type="text" id="failure" value="' . esc_attr( $options['defaults']['failure'] ) . '" class="large-text" />
							<p class="description">' . esc_html__( 'Message to display to users for failed sent message.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
				</table>
				
				<h2>' . esc_html__( 'Contact Form Labels', 'azrcrv-cf' ) . '</h2>
				
				<table class="form-table">
					
					<tr>
					
						<th scope="row">
							<label for="label-name">
								' . esc_html__( 'Name', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="label-name" type="text" id="label-name" value="' . esc_attr( $options['defaults']['labels']['name'] ) . '" class="regular-text" />
							<p class="description">' . esc_html__( 'Label for the "Name" field on the contact form.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="label-email">
								' . esc_html__( 'Email', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="label-email" type="text" id="label-email" value="' . esc_attr( $options['defaults']['labels']['email'] ) . '" class="regular-text" />
							<p class="description">' . esc_html__( 'Label for the "Email" field on the contact form.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="label-subject">
								' . esc_html__( 'Subject', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="label-subject" type="text" id="label-subject" value="' . esc_attr( $options['defaults']['labels']['subject'] ) . '" class="regular-text" />
							<p c
							lass="description">' . esc_html__( 'Label for the "Subject" field on the contact form.', 'azrcrv-cf' ) . '</p>
						</td>
					</tr>
					
					<tr>
					
						<th scope="row">
							<label for="label-message">
								' . esc_html__( 'Message', 'azrcrv-cf' ) . '
							</label>
						</th>
						
						<td>
							<input name="label-message" type="text" id="label-message" value="' . esc_attr( $options['defaults']['labels']['message'] ) . '" class="regular-text" />
							<p class="description">' . esc_html__( 'Label for the "Message" field on the contact form.', 'azrcrv-cf' ) . '</p>
						</td>
						
					</tr>
					
				</table>';

		?>
		<form method="post" action="admin-post.php">
			<fieldset>
				
				<input type="hidden" name="action" value="azrcrv_cf_save_options" />
				<input name="page_options" type="hidden" value="from-email-address, from-email-from-name, subject-prefix, subject, success, failure, label-name, label-email, label-subject, label-message" />
				
				<?php
					// <!-- Adding security through hidden referrer field -->.
					wp_nonce_field( 'azrcrv-cf', 'azrcrv-cf-nonce' );
				?>
				
				<div id="tabs">
					<div id="tab-panel-1" >
						<?php echo $tab_1; ?>
					</div>
				</div>
			</fieldset>
			
			<input type="submit" name="btn_save" value="<?php esc_html_e( 'Save Settings', 'azrcrv-cf' ); ?>" class="button-primary"/>
		</form>
	</div>
	<?php

}

/**
 * Save settings.
 *
 * @since 1.0.0
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

/**
 * Sanitize recipients.
 *
 * @since 1.0.0
 */
function sanitize_recipients( $recipients_to_sanitize ) {

	$recipients = explode( ',', $recipients_to_sanitize );

	$valid_recipients = array();
	foreach ( $recipients as $recipient ) {
		$sanitized_email = sanitize_email( wp_unslash( $recipient ) );
		if ( is_email( $sanitized_email ) ) {
			$valid_recipients[] = $sanitized_email;
		}
	}

	return implode( ',', $valid_recipients );

}

/**
 * Display contact form.
 *
 * @since 1.0.0
 */
function display_contact_form( $atts, $content = null ) {

	global $wp;

	if ( isset( $_GET['token'] ) ) {
		$transient_name = 'azrcrv-cf-s-' . sanitize_text_field( wp_unslash( $_GET['token'] ) );
		$responses      = get_transient( $transient_name );
	}else{
		$responses = '';
	}
	
	// get options with defaults.
	$options = get_option_with_defaults( 'azrcrv-cf' );

	// get shortcode attributes.
	$args = shortcode_atts(
		array(
			'id'              => '',
			'send-from-email' => '',
			'send-from-name'  => '',
			'recipients'      => '',
			'label-name'      => $options['defaults']['labels']['name'],
			'label-email'     => $options['defaults']['labels']['email'],
			'label-subject'   => $options['defaults']['labels']['subject'],
			'label-message'   => $options['defaults']['labels']['message'],
			'subject'         => $options['defaults']['subject'],
			'subject-prefix'  => $options['defaults']['subject-prefix'],
		),
		$atts
	);
	
	// sanitize shortcode attributes.
	$id         = sanitize_text_field( wp_unslash( $args['id'] ) );
	$recipients = sanitize_recipients( sanitize_text_field( wp_unslash( $args['recipients'] ) ) );
	$send_from_email = sanitize_email( wp_unslash( $args['send-from-email'] ) );
	$send_from_name  = sanitize_text_field( wp_unslash( $args['send-from-name'] ) );
	$label_name      = sanitize_text_field( wp_unslash( $args['label-name'] ) );
	$label_email     = sanitize_text_field( wp_unslash( $args['label-email'] ) );
	$label_subject   = sanitize_text_field( wp_unslash( $args['label-subject'] ) );
	$label_message   = sanitize_text_field( wp_unslash( $args['label-message'] ) );
	$subject         = sanitize_text_field( wp_unslash( $args['subject'] ) );
	$subject_prefix  = sanitize_text_field( wp_unslash( $args['subject-prefix'] ) );

	if ( $id == '' ) {
		// is this a valid contact form?

		$contact_form = '<div class="azrcrv-cf-form">
			<div class="azrcrv-cf-error">
				' . esc_html__( 'Contact form cannot be displayed; an id must be provided.', 'azrcrv-cf' ) . '
			</div>
		</div>';

	} elseif ( $recipients == '' and $options['defaults']['recipients'] == '' ) {
		// have recipients been supplied from shortcode or default options?

		$contact_form = '<div class="azrcrv-cf-form">
			<div class="azrcrv-cf-error">
				' . esc_html__( 'Contact form cannot be displayed; at least one recipient must be provided.', 'azrcrv-cf' ) . '
			</div>
		</div>';

	} else {

		// create empty variables.
		$sender_name      = '';
		$sender_email     = '';
		$previous_subject = '';
		$message          = '';
		$messages = '';
		
		if ( is_array( $responses ) ) {
			// form has been submitted so responses to be processed.

			if ( isset( $responses['id'] ) && $id == $responses['id'] ) {
				// load user inputs if form previously submitted.
				if ( isset( $responses['fields']['sender-name'] ) ) {
					$sender_name = sanitize_text_field( wp_unslash( $responses['fields']['sender-name'] ) );
				}
				if ( isset( $responses['fields']['sender-email'] ) ) {
					$sender_email = sanitize_email( wp_unslash( $responses['fields']['sender-email'] ) );
				}
				if ( isset( $responses['fields']['subject'] ) ) {
					$previous_subject = sanitize_text_field( wp_unslash( $responses['fields']['subject'] ) );
				}
				if ( isset( $responses['fields']['message'] ) ) {
					$message = sanitize_text_field( wp_unslash( $responses['fields']['message'] ) );
				}

				if ( is_array( $responses['messages'] ) ) {
					// valid messages to display.

					foreach ( $responses['messages'] as $response ) {
						// failure.
						if ( $response == 'error-invalid-nonce' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'Could not submit message due to an invalid nonce.', 'azrcrv-cf' ) . '</div>';
						}
						if ( $response == 'error-processing-issue' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'There was a problem processing your message.', 'azrcrv-cf' ) . '</div>';
						}
						if ( $response == 'error-name-missing' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'Could not submit message as name cannot be blank.', 'azrcrv-cf' ) . '</div>';
						}
						if ( $response == 'error-email-missing' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'Could not submit message as email cannot be blank.', 'azrcrv-cf' ) . '</div>';
						}
						if ( $response == 'error-subject-missing' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'Could not submit message as subject cannot be blank.', 'azrcrv-cf' ) . '</div>';
						}
						if ( $response == 'error-message-missing' ) {
							$messages .= '<div class="azrcrv-cf-error">' . esc_html__( 'Could not submit as message cannot be blank.', 'azrcrv-cf' ) . '</div>';
						}
						// success.
						if ( $response == 'success-message-sent' ) {
							$messages .= '<div class="azrcrv-cf-success">' . esc_html__( 'Your message has been sent successfully.', 'azrcrv-cf' ) . '</div>';
							$success   = true;
						}
					}
				}
			}
		}

		// generate token.
		$token      = bin2hex( random_bytes( 32 ) );
		// add time to transient.
		$token_time = time();
		$transient  = array(
			'time' => $token_time,
		);
		// add fields to transient instead of as hidden fields; keeps email addresses hidden.
		if ( $recipients != '' ) {
			$transient['recipients'] = $recipients;
		}
		if ( $send_from_email != '' ) {
			$transient['send-from-email'] = $send_from_email;
		}
		if ( $send_from_name != '' ) {
			$transient['send-from-name'] = $send_from_name;
		}
		if ( strlen( $subject_prefix ) > 0 ) {
			$transient['subject-prefix'] = $subject_prefix;
		}

		// set tansient.
		$transient_name = 'azrcrv-cf-' . $token;
		set_transient( $transient_name, $transient, MINUTE_IN_SECONDS * 10 ); // form valid for ten minutes
		
		// set url for resirect
		$current_url = home_url( add_query_arg( array(), $wp->request ) );
		
		if ( isset( $success ) && $success == true ) {
			// responses contained success flag so no submit button.
			$contact_form = array(
				'open' => '',
				'close' => ''
				);
			$submit_button = '';
			$disabled      = 'disabled';
		} else {
			// responses either not present or didn't contain success flag so output submit button.
			$contact_form = array(
				'open' => '<form method="post" id="azrcrv-contact-form" action="' . esc_attr ( $current_url ) . '">',
				'close' => '</form>'
				);
			$submit_button = '<input type="submit" name="submit-' . $token . '" value="' . esc_html__( 'Send', 'azrcrv-cf' ) . '" class="button-primary"/>';
			$disabled      = '';
		}
		
		if ( $subject == '' ) {
			// no subject so prompt for user input
			if ( strlen( $previous_subject ) > 0 ) {
				$subject = $previous_subject;
			}
			$subject = '<input name="subject" type="text" id="subject" value="' . esc_attr( $subject ) . '" class="regular-text" ' . $disabled . ' />';

		} else {
			
			$found_pos = strpos( $subject, '|' );

			if ( $found_pos === false ) {
				if ( strlen( $previous_subject ) > 0 ) {
					$subject = $previous_subject;
				}
				// subject predefined so display and lock field.
				$subject = '<input name="subject" type="hidden" id="subject" value="' . esc_attr( $subject ) . '" class="regular-text" />'. esc_html( $subject ) ;
			} else {
				// subject contains | so set as drop down list.
				$available_subjects = '';
				$subject_options    = explode( '|', $subject );
				foreach ( $subject_options as $subject_option ) {
					$selected = '';
					if ( $subject_option == $previous_subject ) {
						$selected = 'selected';
					}
					$available_subjects .= '<option value="' . esc_attr( $subject_option ) . '" ' . $selected . '>' . esc_html( $subject_option ) . '</option>';
				}
				$subject = '<select name="subject" id="subject" ' . $disabled . '>' . $available_subjects . '</select>';
			}
		}

		// build form.
		$contact_form = '<div class="azrcrv-cf-form">
		
			' . $messages . '
		
			' . $contact_form['open'] . '
			
				<fieldset>
					<input type="hidden" name="action" value="azrcrv_cf_submit_contact_form" /><input name="page_options" type="hidden" value="sender-name, sender-email, subject, message" /><input name="contact-form-id" type="hidden" value="' . $id . '" /><input name="recipient" class="azrcrv-cf-visible" type="input" value="" /><input name="token" class="azrcrv-cf-visible" type="input" value="' . $token . '" />' .
						wp_nonce_field( 'azrcrv-cf-contact-form', 'azrcrv-cf-contact-form-nonce', true, false )
					. '<p class="azrcrv-cf-label">' . esc_html( $label_name ) . '</p>
					<p class="azrcrv-cf-user-input"><input name="sender-name" type="text" id="sender-name" value="' . esc_attr( $sender_name ) . '" class="regular-text" ' . $disabled . ' /></p>
					
					<p class="azrcrv-cf-label">' . esc_html( $label_email ) . '</p>
					<p class="azrcrv-cf-user-input"><input name="sender-email" type="email" id="sender-email" value="' . esc_attr( $sender_email ) . '" class="regular-text" ' . $disabled . ' /></p>
					
					<p class="azrcrv-cf-label">' . esc_html( $label_subject ) . '</p>
					<p class="azrcrv-cf-user-input">' . $subject . '</p>
					
					<p class="azrcrv-cf-label">' . esc_html( $label_message ) . '</p>
					<p class="azrcrv-cf-user-input"><textarea name="message" id="message" class="large-text" rows="6" cols="50" ' . $disabled . '>' . esc_attr( $message ) . '</textarea></p>
					
				</fieldset>
				
				' . $submit_button . '
				
			' . $contact_form['close'] . '
				
		</div>';
	}

	// 
	$var = '<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>';

	return $contact_form;

}


/**
 * Process contact form after submit.
 *
 * @since 1.0.0
 */
function process_form(){
	// was a contact form id included in POST?
	if ( ! isset( $_POST['contact-form-id'] ) ) {
		return;
	}
	
	// get options
	$options = get_option( 'azrcrv-cf' );

	// create responses array.
	$responses = array(
		'id'       => sanitize_text_field( wp_unslash( $_POST['contact-form-id'] ) ),
		'messages' => array(),
	);
	
	// Check that the nonce was set and valid.
	if ( ! isset( $_POST['azrcrv-cf-contact-form-nonce'] ) || ! wp_verify_nonce( $_POST['azrcrv-cf-contact-form-nonce'], 'azrcrv-cf-contact-form' ) ) {
		$responses['messages'][] = 'error-invalid-nonce';
	}
	
	// was a tken sent?
	if ( ! isset( $_POST['token'] ) ) {
			$responses['messages'][] = 'error-processing-issue';
	} else {
		// get transient
		$transient_name = 'azrcrv-cf-' . sanitize_text_field( wp_unslash( $_POST['token'] ) );
		$transient      = get_transient( $transient_name );
		delete_transient( $transient_name );
	}
	
	if ( ! isset( $_POST['recipient'] ) || $_POST['recipient'] != '' ) {
		// error if recipients honeypot POST included
		$responses['messages'][] = 'error-processing-issue';
	} else {
		
		if ( ! isset( $transient ) || $transient === false || $transient['time'] + 3 >= time() ) {
			
			$responses['messages'][] = 'error-processing-issue';

		} else {
			if ( ! isset( $_POST['sender-name'] ) || $_POST['sender-name'] == '' ) {
				$responses['messages'][] = 'error-name-missing';
			}

			if ( ! isset( $_POST['sender-email'] ) || $_POST['sender-email'] == '' ) {
				$responses['messages'][] = 'error-email-missing';
			}

			if ( ! isset( $_POST['subject'] ) || $_POST['subject'] == '' ) {
				$responses['messages'][] = 'error-subject-missing';
			}

			if ( ! isset( $_POST['message'] ) || $_POST['message'] == '' ) {
				$responses['messages'][] = 'error-message-missing';
			}

			$recipients = '';
			if ( isset( $transient['recipients'] ) and $transient['recipients'] != '' ) {
				// in function the recipient string is split and each email individually sanitized.
				$recipients = sanitize_recipients( sanitize_text_field( wp_unslash( $transient['recipients'] ) ) );
			} else {
				// in function the recipient string is split and each email individually sanitized.
				$recipients = sanitize_recipients( sanitize_text_field( wp_unslash( $options['defaults']['recipients'] ) ) );
			}
			if ( $recipients == '' ) {
				$responses['messages'][] = 'error-recipients-not-valid';
			}
		}
	}

	if ( count( $responses['messages'] ) == 0 ) {
		// process if we have no errors in responses.

		$headers = array();

		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		
		// set from email address and name from options.
		$from_email_address = sanitize_email( wp_unslash( $options['defaults']['from-email-address'] ) );
		$from_email_name    = sanitize_text_field( wp_unslash( $options['defaults']['from-email-name'] ) );
		if ( isset( $transient['send-from-email'] ) && $transient['send-from-email'] != '' ) {
			$send_from_email = sanitize_email( wp_unslash( $transient['send-from-email'] ) );
			if ( is_email( $send_from_email ) ) {
				$from_email_address = $send_from_email;
			}
		}
		if ( isset( $transient['send-from-name'] ) && $transient['send-from-name'] != '' ) {
			$from_email_name = sanitize_text_field( wp_unslash( $transient['send-from-name'] ) );
		}

		// set to and sender.
		$to             = $recipients;
		$sender_name    = sanitize_text_field( wp_unslash( $_POST['sender-name'] ) );
		$sender_address = sanitize_email( wp_unslash( $_POST['sender-email'] ) );

		$body = '';
		if ( $from_email_address != '' ) {
			// if from email address set in options override those supplied by user; add these to message body.
			if ( $from_email_name == '' ) {
				$from_email_name = $sender_name;
			}
			$headers[] = 'From: ' . $from_email_name . ' <' . $from_email_address . '>';
			$headers[] = 'Reply-To: ' . $sender_name . ' <' . $sender_address . '>';
			$body     .= '<p><b>' . esc_html__( 'From:', 'azrcrv-cf' ) . '</b> ' . '<a href="mailto:' . esc_attr( $sender_address ) . '">' . esc_html( $sender_name ) . ' &lt;' . esc_html( $sender_address ) . '&gt;</a></p>';
		} else {
			// no email from address set in options so use those from user.
			$headers[] = 'From: ' . $sender_name . ' <' . $sender_address . '>';
		}
		// sanitize subject.
		$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ) );
		if ( isset( $transient['subject-prefix'] ) ) {
			// add subject prefix if supplied.
			$subject_prefix = sanitize_text_field( wp_unslash( $transient['subject-prefix'] ) );
			$subject        = $subject_prefix . ' ' . $subject;
		}
		$body        .= '<p><b>' . esc_html__( 'Subject:', 'azrcrv-cf' ) . '</b> ' . esc_html( $subject ) . '</p>';
		$body        .= '<p><b>' . esc_html__( 'Message:', 'azrcrv-cf' ) . '</b><br />';
		// get allowed html tags and santze message.
		$allowed_html = wp_kses_allowed_html( 'post' );
		$message      = wp_kses( wp_unslash( $_POST['message'] ), $allowed_html );
		$body        .= '<p>' . nl2br( $message ) . '</p>'; // not escaped as want to retain html tags in message/

		// add user ip to email if it can be determined.
		if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
			$user_ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
			$body   .= '<p><b>' . esc_html__( 'User IP:', 'azrcrv-cf' ) . '</b> ' . esc_html( $user_ip ) . '</p>';
		}
		$body .= '<hr><p>' . esc_html__( 'Sent from a contact form on', 'azrcrv-cf' ) . ' <a href="' . trailingslashit( esc_url_raw( get_site_url() ) ) . '">' . trailingslashit( esc_url_raw( get_site_url() ) ) . '</a></p>';

		// send email.
		$response = wp_mail( $to, $subject, $body, $headers );

		$response == false;
		// check response from wp_mail and set flag.
		if ( $response == true ) {
			$responses['messages'][] = 'success-message-sent';
		} else {
			$responses['messages'][] = 'error-send-failed';
		}
	}
	
//update_option('azrcrv-cf-1-'.$_POST['token'], $responses);
	if ( isset( $_POST['sender-name'] ) ){
		$responses['fields']['sender-name'] = $_POST['sender-name'];
	}
	if ( isset( $_POST['sender-email'] ) ){
		$responses['fields']['sender-email'] = $_POST['sender-email'];
	}
	if ( isset( $_POST['subject'] ) ){
		$responses['fields']['subject'] = $_POST['subject'];
	}
	if ( isset( $_POST['message'] ) ){
		$responses['fields']['message'] = $_POST['message'];
	}

	$transient_name = 'azrcrv-cf-s-' . sanitize_text_field( wp_unslash( $_POST['token'] ) );
	set_transient( $transient_name, $responses, MINUTE_IN_SECONDS * 10 ); // form valid for ten minutes
	//update_option( $transient_name, $responses ); // form valid for ten minutes
	
	
	$redirect_url  = is_ssl() ? 'https://' : 'http://';

	$redirect_url .= wp_unslash( $_SERVER['HTTP_HOST'] );
	$redirect_url .= wp_unslash( $_SERVER['REQUEST_URI'] );
	$redirect_url .= '?token=' . sanitize_text_field( wp_unslash( $_POST['token'] ) );
	
	wp_safe_redirect( esc_url_raw( $redirect_url ) );
	exit;
	
}