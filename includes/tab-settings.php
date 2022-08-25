<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ContactForms;

/**
 * Settings tab.
 */

$tab_settings_label = PLUGIN_NAME . ' ' . esc_html__( 'Settings', 'azrcrv-cf' );
$tab_settings       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Email Settings', 'azrcrv-cf' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .
				sprintf( esc_html__( '%1$s allows simple contact forms to be created and added to pages or posts using the %2$s shortcode.', 'azrcrv-cf' ), PLUGIN_NAME, '<code>[simple-contact-form]</code>' ) . '
					
			</p>
		
		</td>
	
	</tr>
		
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
			<p class="description">' . sprintf( esc_html__( 'Prefix applied to the subject of every email.', 'azrcrv-cf' ), '<strong>subject-prefix</strong>' ) . '</p>
		</td>
		
	</tr>
	
	<tr>
	
		<th scope="row">
			<label for="subject">
				' . esc_html__( 'Subject', 'azrcrv-cf' ) . '
			</label>
		</th>
		
		<td>
			<input name="subject" type="text" id="subject" value="' . esc_attr( $options['defaults']['subject'] ) . '" class="large-text" />
			<p class="description">' . sprintf( esc_html__( 'This will be used as the subject; leave blank for free-form entry; for a drop down list, separate items with a %s (e.g. "Sales|Support|Accounts & Billing").', 'azrcrv-cf' ), '|' ) . '</p>
		</td>
		
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Contact Form Field Labels', 'azrcrv-cf' ) . '</h2>
			
		</th>

	</tr>
		
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

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Messages', 'azrcrv-cf' ) . '</h2>
			
		</th>

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
	
</table>';
