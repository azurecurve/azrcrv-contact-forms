<?php
/*
	tab output on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ContactForms;

/**
 * Sanitize recipients.
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
 */
function display_contact_form( $atts, $content = null ) {

	global $wp;

	if ( isset( $_GET['token'] ) ) {
		$transient_name = 'azrcrv-cf-s-' . sanitize_text_field( wp_unslash( $_GET['token'] ) );
		$responses      = get_transient( $transient_name );
	} else {
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
	$id              = sanitize_text_field( wp_unslash( $args['id'] ) );
	$recipients      = sanitize_recipients( sanitize_text_field( wp_unslash( $args['recipients'] ) ) );
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
		$messages         = '';

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
		$token = bin2hex( random_bytes( 32 ) );
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
			$contact_form  = array(
				'open'  => '',
				'close' => '',
			);
			$submit_button = '';
			$disabled      = 'disabled';
		} else {
			// responses either not present or didn't contain success flag so output submit button.
			$contact_form  = array(
				'open'  => '<form method="post" id="azrcrv-contact-form" action="' . esc_attr( $current_url ) . '">',
				'close' => '</form>',
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
				$subject = '<input name="subject" type="hidden" id="subject" value="' . esc_attr( $subject ) . '" class="regular-text" />' . esc_html( $subject );
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

		$var = '<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>';

	return $contact_form;

}


/**
 * Process contact form after submit.
 */
function process_form() {
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
			$body     .= '<p><b>' . esc_html__( 'From:', 'azrcrv-cf' ) . '</b> <a href="mailto:' . esc_attr( $sender_address ) . '">' . esc_html( $sender_name ) . ' &lt;' . esc_html( $sender_address ) . '&gt;</a></p>';
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
		$body .= '<p><b>' . esc_html__( 'Subject:', 'azrcrv-cf' ) . '</b> ' . esc_html( $subject ) . '</p>';
		$body .= '<p><b>' . esc_html__( 'Message:', 'azrcrv-cf' ) . '</b><br />';
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

	// update_option('azrcrv-cf-1-'.$_POST['token'], $responses);
	if ( isset( $_POST['sender-name'] ) ) {
		$responses['fields']['sender-name'] = sanitize_text_field( wp_unslash( $_POST['sender-name'] ) );
	}
	if ( isset( $_POST['sender-email'] ) ) {
		$responses['fields']['sender-email'] = sanitize_text_field( wp_unslash( $_POST['sender-email'] ) );
	}
	if ( isset( $_POST['subject'] ) ) {
		$responses['fields']['subject'] = sanitize_text_field( wp_unslash( $_POST['subject'] ) );
	}
	if ( isset( $_POST['message'] ) ) {
		$responses['fields']['message'] = sanitize_text_field( wp_unslash( $_POST['message'] ) );
	}

	$transient_name = 'azrcrv-cf-s-' . sanitize_text_field( wp_unslash( $_POST['token'] ) );
	set_transient( $transient_name, $responses, MINUTE_IN_SECONDS * 10 ); // form valid for ten minutes
	// update_option( $transient_name, $responses ); // form valid for ten minutes

	$redirect_url = is_ssl() ? 'https://' : 'http://';

	$redirect_url .= sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) );
	$redirect_url .= sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
	$redirect_url .= '?token=' . sanitize_text_field( wp_unslash( $_POST['token'] ) );

	wp_safe_redirect( esc_url_raw( $redirect_url ) );
	exit;

}
