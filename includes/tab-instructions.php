<?php
/*
	other plugins tab on settings page
*/

/**
 * Declare the Namespace.
 */
namespace azurecurve\ContactForms;

/**
 * Instructions tab.
 */
$tab_instructions_label = esc_html__( 'Instructions', 'azrcrv-cf' );
$tab_instructions       = '
<table class="form-table azrcrv-settings">

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( '%1$s allows you to create simple contact forms using the %2$s shortcode and supports multiple contact forms on a page.', 'azrcrv-cf' ), PLUGIN_NAME, '<code>simple-contact-form</code>' ) . '
				
			</p>
		
		</td>
	
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Default Settings', 'azrcrv-cf' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'The %s tab allows default settings to be configured.', 'azrcrv-cf' ), 'Settings' ) . '
				
			</p>
		
		</td>
	
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Shortcode Parameters', 'azrcrv-cf' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>' .

				sprintf( esc_html__( 'Contact forms can be placed on pages using the %s shortcode which supports the following parameters:', 'azrcrv-cf' ), '<code>simple-contact-form</code>' ) . '
				
			</p>
			
			<ul>
				<li>' . sprintf( esc_html__( '%s is the only mandatory parameter; a unique id for each contact form on the page must be supplied.', 'azrcrv-cf' ), '<code>id</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default send from email address to be overridden.', 'azrcrv-cf' ), '<code>send-from-email</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default send from email name to be overridden.', 'azrcrv-cf' ), '<code>send-from-name</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s is a comma separated list of email address which allows the default recipients to be overridden.', 'azrcrv-cf' ), '<code>recipients</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default label for the name field to be overridden.', 'azrcrv-cf' ), '<code>label-name</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default label for the email field to be overridden.', 'azrcrv-cf' ), '<code>label-email</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default label for the subject field to be overridden.', 'azrcrv-cf' ), '<code>label-subject</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default label for the message field to be overridden.', 'azrcrv-cf' ), '<code>label-message</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows the default subject for the name field to be overridden. If an empty subject is provided, users can free form type a subject. Multiple subjects to allow picking from a drop down list can be supplied, separated with a `|`.', 'azrcrv-cf' ), '<code>subject</code>' ) . '</li>
				<li>' . sprintf( esc_html__( '%s allows a subject prefix to be added to all subjects when the email is sent and override the default.', 'azrcrv-cf' ), '<code>subject-prefix</code>' ) . '</li>
			</ul>
		
		</td>
	
	</tr>

	<tr>
	
		<th scope="row" colspan=2 class="azrcrv-settings-section-heading">
			
				<h2 class="azrcrv-settings-section-heading">' . esc_html__( 'Shortcode Example', 'azrcrv-cf' ) . '</h2>
			
		</th>

	</tr>

	<tr>
	
		<td scope="row" colspan=2>
		
			<p>
				
				<code>[simple-contact-form id="contact-us" recipients="bob@example.com,jane@example.com" subject="Sales|Support|Accounts" subject-prefix="Contact us from example.com:"]</code>
				
			</p>
		
		</td>
	
	</tr>
	
</table>';
