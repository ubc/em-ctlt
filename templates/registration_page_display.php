<?php
/*
 * Version: 0.12
 */
//This is the registration form.
//This is a template file for displaying a registration form for an event on a page.
//There should be a copy of this file in your wp-content/uploads/espresso/ folder.
?>
<div id="event_espresso_registration_form" class="event-display-boxes ui-widget">
	
<?php
$ui_corner = 'ui-corner-all'; 
//This tells the system to hide the event title if we only need to display the registration form.
if ($reg_form_only == false) { 
?>
	<h3 class="event_title ui-corner-top" id="event_title-<?php echo $event_id; ?>">
		<?php echo $event_name ?> <?php echo $is_active['status'] == 'EXPIRED' ? ' - <span class="expired_event">Event Expired</span>' : ''; ?> <?php echo $is_active['status'] == 'PENDING' ? ' - <span class="expired_event">Event is Pending</span>' : ''; ?> <?php echo $is_active['status'] == 'DRAFT' ? ' - <span class="expired_event">Event is a Draft</span>' : ''; ?>
	</h3>
	
<?php 
	$ui_corner = 'ui-corner-bottom';
}
?>
 <div class="event_espresso_form_wrapper event-data-display <!--ui-widget-content--> <?php echo $ui_corner ?>">
 	<?php //Featured image
		echo apply_filters('filter_hook_espresso_display_featured_image', $event_id, !empty($event_meta['event_thumbnail_url']) ? $event_meta['event_thumbnail_url'] : '');?>

	<?php /* Venue details. Un-comment first and last lines & any venue details you wish to display or use the provided shortcodes. */ ?>
	<?php // echo '<div id="venue-details-display">'; ?>
	<?php // echo '<p class="section-title">' . __('Venue Details', 'event_espresso') . '</p>'; ?>
	<?php // echo $venue_title != ''?'<p id="event_venue_name-'.$event_id.'" class="event_venue_name">'.stripslashes_deep($venue_title).'</p>':''?>
	<?php // echo $venue_address != ''?'<p id="event_venue_address-'.$event_id.'" class="event_venue_address">'.stripslashes_deep($venue_address).'</p>':''?>
	<?php // echo $venue_address2 != ''?'<p id="event_venue_address2-'.$event_id.'" class="event_venue_address2">'.stripslashes_deep($venue_address2).'</p>':''?>
	<?php // echo $venue_city != ''?'<p id="event_venue_city-'.$event_id.'" class="event_venue_city">'.stripslashes_deep($venue_city).'</p>':''?>
	<?php // echo $venue_state != ''?'<p id="event_venue_state-'.$event_id.'" class="event_venue_state">'.stripslashes_deep($venue_state).'</p>':''?>
	<?php // echo $venue_zip != ''?'<p id="event_venue_zip-'.$event_id.'" class="event_venue_zip">'.stripslashes_deep($venue_zip).'</p>':''?>
	<?php // echo $venue_country != ''?'<p id="event_venue_country-'.$event_id.'" class="event_venue_country">'.stripslashes_deep($venue_country).'</p>':''?>
	<?php // echo '</div>'; ?>
	<?php /* end venue details block */ ?>

	<?php

	switch ($is_active['status']) {

		default: //This will display the registration form
?>
	<div class="event_espresso_form_wrapper">
		<form method="post" action="<?php echo get_permalink( $event_page_id );?>" id="registration_form">
				<!-- CTLT START -->
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span4">
	<!-- CTLT END -->
	<?php
				
			//This hides the date/times and location when usign custom post types or the ESPRESSO_REG_FORM shortcode
				if ( $reg_form_only == false ){	
					
					?>
				<p class="event_address" id="event_address-<php echo $event_id; ?>"><span class="section-title"><?php echo __('Address:', 'event_espresso'); ?></span><br/>
					<span class="address-block">
					<?php
					/* Display the address and google map link if available */
					if ($location != '' && (empty($org_options['display_address_in_regform']) || $org_options['display_address_in_regform'] != 'N')) {
	?>
						<?php echo $venue_title; ?><br />
						<?php echo stripslashes_deep($location); ?><br />
						<span class="google-map-link"><?php echo $google_map_link; ?></span>
	<?php
					}
					else {
						?>
						<span class="span_event_date_label">Sorry, there is no venue or address associated with this event</span>
						<?php
					}
					do_action('action_hook_espresso_social_display_buttons', $event_id);
	?>
					</span>
				</p>

				<p class="start_date">
					<?php if ($end_date !== $start_date) { ?>
					<span class="span_event_date_label">
					<?php _e('Start Date: ', 'event_espresso'); ?>
					</span>
					<?php } else { ?>
					<span class="span_event_date_label">
					<?php _e('Date: ', 'event_espresso'); ?>
					</span>
					<?php } ?>
					<span class="span_event_date_value">
					<?php echo event_date_display($start_date, get_option('date_format')); ?>
					</span>
	<?php if ($end_date !== $start_date) : ?>
					<br/>
					<span class="span_event_date_label">
						<?php _e('End Date: ', 'event_espresso'); ?>
					</span> 
					<span class="span_event_date_value">
					<?php echo event_date_display($end_date, get_option('date_format')); ?>
					</span> 
	<?php endif; ?>
					<?php echo apply_filters('filter_hook_espresso_display_ical', $all_meta); ?>
				</p>
	<?php
				}

			// * * This section shows the registration form if it is an active event * *

				if ($display_reg_form == 'Y') {
	?>
				<p class="event_time">
	<?php
						//This block of code is used to display the times of an event in either a dropdown or text format.
						if (isset($time_selected) && $time_selected == true) {//If the customer is coming from a page where the time was preselected.
							echo event_espresso_display_selected_time($time_id); //Optional parameters start, end, default
						} else {
							echo event_espresso_time_dropdown($event_id);
						}//End time selected
	?>
				</p>
				<!-- CTLT START -->
	<?php
					// This block of code is used to display the staff that are tagged to the event
					$event_staff = do_shortcode( '[ESPRESSO_STAFF event_id="' . $event_id . '" outside_wrapper="ul" outside_wrapper_class="none" inside_wrapper="li" inside_wraper_class="none"]' );
					echo '<span class="section-title">Facilitator(s):</span>';
					if( empty( $event_staff ) ) {
						echo '<div class="event_staff"><p class="event_person"><strong class="person_name">No staff</strong></p></div>';
					} else {
						echo $event_staff;
					}
	?>
				<!-- CTLT END -->
	<?php

					// Added for seating chart addon
					$display_price_dropdown = TRUE;
					if( $is_active['status'] == 'REGISTRATION_OPEN' ) {
						if (defined('ESPRESSO_SEATING_CHART')) {
							$seating_chart_id = seating_chart::check_event_has_seating_chart($event_id);
							if ($seating_chart_id !== FALSE) {
								$display_price_dropdown = FALSE;
							}
						}


						if ($display_price_dropdown == TRUE) {
							$price_label = '<span class="section-title">'.__('Choose an Option: ', 'event_espresso').'</span>';
	?>
							<p class="event_prices">
								<?php do_action( 'espresso_price_select', $event_id, array('show_label'=>TRUE, 'label'=>$price_label) );?>
							</p>
	<?php
						} else {
	?>
							<p class="event_prices">
								<?php do_action( 'espresso_seating_price_select_action', $event_id );?>
							</p>
	<?php
							// Seating chart selector
							do_action('espresso_seating_chart_select', $event_id);
								
						}
					}			
	?>
	<!-- CTLT START -->
			</div>
			<div class="span8">
		<?php
		if ($display_desc == "Y") { //Show the description or not ?>
		<p class="section-title">
			<?php _e('Description:', 'event_espresso') ?>
		</p>
		<div class="event_description clearfix">
			<?php echo espresso_format_content($event_desc); //Code to show the actual description. The Wordpress function "wpautop" adds formatting to your description.   ?>
		</div>
		<?php
		}//End display description
		?>
			</div>
		</div>
		<?php if( current_user_can( 'activate_plugins' ) && class_exists( 'CTLT_Espresso_Controls' ) ) { ?>
		<div class="row-fluid">
			<div class="span12">
				<h3>Admin Requests</h3>
				<div class="accordion" id ="admin-requests-accordion">
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-requests-accordion" href="#handouts">
								Handouts
							</a>
						</div>
						<div id="handouts" class="accordion-body collapse">
							<div class="accordion-inner">
								<dl>
									<dt>Handout Status</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_handouts_radio'] ) ? $admin_notes['_ctlt_espresso_handouts_radio'] : '&nbsp;'; ?></dd>
									<dt>Handout File</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_handouts_upload'] ) ? wp_get_attachment_link( $admin_notes['_ctlt_espresso_handouts_upload'] ) : '&nbsp;'; ?></dd>
								</dl>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-requests-accordion" href="#room-setup">
								Room Setup
							</a>
						</div>
						<div id="room-setup" class="accordion-body collapse">
							<div class="accordion-inner">
								<dl>
									<dt>Room Setup Style</dt>
									<dd><?php echo $admin_notes['_ctlt_espresso_room_setup']; ?></dd>
								</dl>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-requests-accordion" href="#additional-information">
								Additional Information
							</a>
						</div>
						<div id="additional-information" class="accordion-body collapse">
							<div class="accordion-inner">
								<dl>
									<dt>Room Setup Notes</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_room_setup_notes'] ) ? $admin_notes['_ctlt_espresso_room_setup_notes'] : '&nbsp;'; ?></dd>
									<dt>A/V and Computer Requirements</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_av_computer_requirements'] ) ? $admin_notes['_ctlt_espresso_av_computer_requirements'] : '&nbsp;'; ?></dd>
									<dt>Admin Support Notes</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_admin_support_notes'] ) ? $admin_notes['_ctlt_espresso_admin_support_notes'] : '&nbsp;'; ?></dd>
									<dt>Marketing and Communication Support Notes</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_marketing_communication'] ) ? $admin_notes['_ctlt_espresso_marketing_communication'] : '&nbsp;'; ?></dd>
									<dt>Catering Notes</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_catering_notes'] ) ? $admin_notes['_ctlt_espresso_catering_notes'] : '&nbsp;'; ?></dd>
									<dt>Room Setup Assistance</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_room_setup_assistance'] ) ? $admin_notes['_ctlt_espresso_room_setup_assistance'] : '&nbsp;'; ?></dd>
									<dt>Signs for Event</dt>
									<dd><?php echo !empty( $admin_notes['_ctlt_espresso_signs_for_event'] ) ? $admin_notes['_ctlt_espresso_signs_for_event'] : '&nbsp;'; ?></dd>
								</dl>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-requests-accordion" href="#additional-requirements">
								Additional Requirements
							</a>
						</div>
						<div id="additional-requirements" class="accordion-body collapse">
							<div class="accordion-inner">
								<dl>
									<dt>Computers</dt>
									<dd>
										<dl>
											<dt>Computer</dt>
											<dd><?php echo !empty( $admin_notes['_ctlt_espresso_computers'] ) ? $admin_notes['_ctlt_espresso_computers'] : '&nbsp'; ?></dd>
											<dt>Cables</dt>
											<dd><?php echo !empty( $admin_notes['_ctlt_espresso_cables'] ) ? $admin_notes['_ctlt_espresso_cables'] : '&nbsp;'; ?></dd>
											<dt>Laptops</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_laptop_checkbox'];
												echo '<br/> Quantity: ';
												echo $admin_notes['_ctlt_espresso_laptop_textbox']; ?>
											</dd>
											<dt>Headsets</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_headset_checkbox'];
												echo '<br/> Quantity: ';
												echo $admin_notes['_ctlt_espresso_headset_textbox']; ?>
											</dd>
											<dt>Clickers</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_clicker_checkbox'];
												echo '<br/> Quantity: ';
												echo $admin_notes['_ctlt_espresso_clicker_textbox']; ?>
											</dd>
											<dt>Virtual Participation</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_virtual_checkbox'];
												echo '<br/> URL: ';
												echo $admin_notes['_ctlt_espresso_virtual_textbox_website'];
												echo '<br/> Login: ';
												echo $admin_notes['_ctlt_espresso_virtual_textbox_login']; ?>
											</dd>
											<dt>URL's on Computers</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_url_checkbox'];
												echo '<br/> URL: ';
												echo $admin_notes['_ctlt_espresso_url_textbox']; ?>
											</dd>
											<dt>Folder with Files</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_folder_checkbox']; 
												echo $admin_notes['_ctlt_espresso_folder_checkbox'] === 'yes' ? ' Event organizer will send administrators the files' : '&nbsp;'; ?></dd>
											<dt>Additional Software</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_software_checkbox'];
												echo '<br/> Name: '; 
												echo $admin_notes['_ctlt_espresso_software_checkbox'] === 'yes' && !empty( $admin_notes['_ctlt_espresso_software_textbox'] )? $admin_notes['_ctlt_espresso_software_textbox'] : '&nbsp;'; ?>
											</dd>
											<dt>Login Information</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_login_checkbox']; 
												echo '<br/> Username: '; 
												echo $admin_notes['_ctlt_espresso_login_checkbox'] === 'yes' && !empty( $admin_notes['_ctlt_espresso_login_textbox_name'] ) ? $admin_notes['_ctlt_espresso_login_textbox_name'] : '&nbsp;'; 
												echo '<br/> Password: '; 
												echo $admin_notes['_ctlt_espresso_login_checkbox'] === 'yes' && !empty( $admin_notes['_ctlt_espresso_login_textbox_password'] ) ? $admin_notes['_ctlt_espresso_login_textbox_password'] : '&nbsp;'; ?>
											</dd>
											<dt>Audio Recording</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_audio_checkbox'];
												echo '<br/> Headcount: ';
												echo $admin_notes['_ctlt_espresso_audio_checkbox'] === 'yes' && !empty( $admin_notes['_ctlt_espresso_audio_textbox'] ) ? $admin_notes['_ctlt_espresso_audio_textbox'] : '&nbsp;'; ?>
											</dd>
											<dt>Projectors</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_projector_checkbox'];
												echo '<br/> Quantity: ';
												echo $admin_notes['_ctlt_espresso_projector_checkbox'] === 'yes' && !empty( $admin_notes['_ctlt_espresso_projector_textbox'] ) ? $admin_notes['_ctlt_espresso_projector_textbox'] : '&nbsp;'; ?>
											</dd>
											<dt>Speakers</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_speakers_checkbox']; ?></dd>
										</dl>
									</dd>
									<dt>Equipment</dt>
									<dd>
										<dl>
											<dt>Slide Advancer</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_slide_advancer']; ?></dd>
											<dt>Laser Pointer</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_laser_pointer']; ?></dd>
											<dt>Smart Projector</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_smart_projecter']; ?></dd>
											<dt>USB Stick</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_usb_stick']; ?></dd>
											<dt>A/V Technician</dt>
											<dd><?php echo $admin_notes['_ctlt_espresso_av_technician']; ?></dd>
										</dl>
									</dd>
								</dl>
							</div>
						</div>
					</div>
					<div class="accordion-group">
						<div class="accordion-heading">
							<a class="accordion-toggle" data-toggle="collapse" data-parent="#admin-requests-accordion" href="#costs">
								Costs
							</a>
						</div>
						<div id="costs" class="accordion-body collapse">
							<div class="accordion-inner">
								<dl>
									<dt>Facilitator Pay (Total)</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_facilitator_pay']; ?></dd>
									<dt>TA Pay (Total)</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_ta_pay']; ?></dd>
									<dt>Room Cost</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_room_cost']; ?></dd>
									<dt>Ad Cost</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_ad_cost']; ?></dd>
									<dt>Food Cost</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_food_cost']; ?></dd>
									<dt>Other Cost</dt>
									<dd><?php echo '$' . $admin_notes['_ctlt_espresso_other_cost']; ?></dd>
								</dl>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php
		if( $is_active['status'] != 'REGISTRATION_OPEN') {
			?>
			<div class="row-fluid">
				<div class="span12">
					<div class="alert">
						<div class="row-fluid">
							<div class="span11">
								<?php
								switch( $is_active['status'] ) {
									case 'EXPIRED':
										// show the expired message
										__( 'This event has passed.', 'event_espresso' );
										break;

									case 'REGISTRATION_CLOSED':
										// if today's date is after $reg_end_date
										_e( 'We are sorry but registration for this event is now closed.', 'event_espresso' );
										echo '<br />';
										_e( 'Please ', 'event_espresso' );?>
										<a href="contact" title="<?php _e( 'contact us', 'event_espresso' );?>">
											<?php _e( 'contact us ', 'event_espresso' );?>
										</a>
										<?php
										_e( 'if you would like to know if spaces are still available.', 'event_espresso' );
										break;

									case 'REGISTRATION_NOT_OPEN':
										// if today's date is after $reg_end_date
										// if today's date is prior to $reg_start_date
										_e( 'We are sorry but this event is not yet open for registration.', 'event_espresso' );
										echo '<br />';
										__( 'You will be able to register starting ', 'event_espresso' ) . ' ' . event_espresso_no_format_date( $reg_start_date, 'F d, Y' );
										break;

									default:
										// catches all other cases
										// will probably just say this event is not open for registration
										__( 'This event is not open for registration.', 'event_espresso' );
										break;
								}
								?>
							</div>
							<div class="span1">
								<i class="icon-warning-sign"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
		?>
	</div>
	<?php
	if( $is_active['status'] == 'REGISTRATION_OPEN' ) {
	?>
	<!-- CTLT END -->		
				<div id="event-reg-form-groups">
				
					<h3 class="section-heading"><?php _e('Registration Details', 'event_espresso'); ?></h3>
					
	<?php
					//Outputs the custom form questions. This function can be overridden using the custom files addon
					echo event_espresso_add_question_groups( $question_groups, '', NULL, FALSE, array( 'attendee_number' => 1 ), 'ee-reg-page-questions' );
	?>
				</div>
				
	<?php					
					//Coupons
	?>
				<input type="hidden" name="use_coupon[<?php echo $event_id; ?>]" value="<?php echo $use_coupon_code; ?>" />
	<?php
					if ( $use_coupon_code == 'Y' && function_exists( 'event_espresso_coupon_registration_page' )) {
						echo event_espresso_coupon_registration_page($use_coupon_code, $event_id);
					}
					//End coupons display
							
					//Groupons
	?>
				<input type="hidden" name="use_groupon[<?php echo $event_id; ?>]" value="<?php echo $use_groupon_code; ?>" />
	<?php
					if ( $use_groupon_code == 'Y' && function_exists( 'event_espresso_groupon_registration_page' )) {
						echo event_espresso_groupon_registration_page($use_groupon_code, $event_id);
					}
					//End groupons display					
	?>
				<input type="hidden" name="regevent_action" id="regevent_action-<?php echo $event_id; ?>" value="post_attendee">
				<input type="hidden" name="event_id" id="event_id-<?php echo $event_id; ?>" value="<?php echo $event_id; ?>">
				
	<?php
					//Multiple Attendees
					if ( $allow_multiple == "Y" && $number_available_spaces > 1 ) {					
						//This returns the additional attendee form fields. Can be overridden in the custom files addon.
						echo event_espresso_additional_attendees($event_id, $additional_limit, $number_available_spaces, __('Number of Tickets', 'event_espresso'), true, $event_meta);
					} else {				
	?>
				<input type="hidden" name="num_people" id="num_people-<?php echo $event_id; ?>" value="1">
	<?php
					}					
					//End allow multiple	
					
					wp_nonce_field('reg_nonce', 'reg_form_nonce');
					
					//Recaptcha portion
					if ( $org_options['use_captcha'] == 'Y' && empty($_REQUEST['edit_details']) && ! is_user_logged_in()) {
					
						if ( ! function_exists('recaptcha_get_html')) {
							require_once(EVENT_ESPRESSO_PLUGINFULLPATH . 'includes/recaptchalib.php');
						}
						# the response from reCAPTCHA
						$resp = null;
						# the error code from reCAPTCHA, if any
						$error = null;
	?>
				<p class="event_form_field" id="captcha-<?php echo $event_id; ?>">
					<?php _e('Anti-Spam Measure: Please enter the following phrase', 'event_espresso'); ?>
					<?php echo recaptcha_get_html($org_options['recaptcha_publickey'], $error, is_ssl() ? true : false); ?>
				</p>
					
	<?php 
					} 
					//End use captcha  
	?>
				<p class="event_form_submit" id="event_form_submit-<?php echo $event_id; ?>">
					<input class="btn_event_form_submit btn" id="event_form_field-<?php echo $event_id; ?>" type="submit" name="Submit" value="<?php _e('Submit', 'event_espresso'); ?>">
				</p>
	
		<?php } ?>			
	<?php } ?>

	    </form>
	</div>
	
<?php 
				break;
				
			}
			//End Switch statement to check the status of the event

		if (isset($ee_style['event_espresso_form_wrapper_close'])) {
			echo $ee_style['event_espresso_form_wrapper_close']; 
		}			
?>
<p class="edit-link-footer"><?php echo espresso_edit_this($event_id) ?></p>
</div>
</div>
