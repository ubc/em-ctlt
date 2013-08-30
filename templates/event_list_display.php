<?php

/*
 * Version: 0.16
 */
//This is the event list template page.
//This is a template file for displaying an event lsit on a page.
//There should be a copy of this file in your wp-content/uploads/espresso/ folder.
/*
 * use the following shortcodes in a page or post:
 * [EVENT_LIST]
 * [EVENT_LIST limit=1]
 * [EVENT_LIST css_class=my-custom-class]
 * [EVENT_LIST show_expired=true]
 * [EVENT_LIST show_deleted=true]
 * [EVENT_LIST show_secondary=false]
 * [EVENT_LIST show_recurrence=true]
 * [EVENT_LIST category_identifier=your_category_identifier]
 *
 * Example:
 * [EVENT_LIST limit=5 show_recurrence=true category_identifier=your_category_identifier]
 *
 */

//Print out the array of event status options
//print_r (event_espresso_get_is_active($event_id));
//Here we can create messages based on the event status. These variables can be echoed anywhere on the page to display your status message.

$status = event_espresso_get_is_active(0,$event_meta);
$status_display = ' - ' . $status['display_custom'];
$status_display_ongoing = $status['status'] == 'ONGOING' ? ' - ' . $status['display_custom'] : '';
$status_display_deleted = $status['status'] == 'DELETED' ? ' - ' . $status['display_custom'] : '';
$status_display_secondary = $status['status'] == 'SECONDARY' ? ' - ' . $status['display_custom'] : ''; //Waitlist event
$status_display_draft = $status['status'] == 'DRAFT' ? ' - ' . $status['display_custom'] : '';
$status_display_pending = $status['status'] == 'PENDING' ? ' - ' . $status['display_custom'] : '';
$status_display_denied = $status['status'] == 'DENIED' ? ' - ' . $status['display_custom'] : '';
$status_display_expired = $status['status'] == 'EXPIRED' ? ' - ' . $status['display_custom'] : '';
$status_display_reg_closed = $status['status'] == 'REGISTRATION_CLOSED' ? ' - ' . $status['display_custom'] : '';
$status_display_not_open = $status['status'] == 'REGISTRATION_NOT_OPEN' ? ' - ' . $status['display_custom'] : '';
$status_display_open = $status['status'] == 'REGISTRATION_OPEN' ? ' - ' . $status['display_custom'] : '';

//You can also display a custom message. For example, this is a custom registration not open message:
$status_display_custom_closed = $status['status'] == 'REGISTRATION_CLOSED' ? ' - <span class="espresso_closed">' . __('Regsitration is closed', 'event_espresso') . '</span>' : '';
global $this_event_id;
$this_event_id = $event_id;
if( $multi_reg && event_espresso_get_status( $event_id ) == 'ACTIVE' ) {
	$params = array(
			// REQUIRED, the id of the event that needs to be added to the cart
			'event_id' => $event_id,
			// REQUIRED, anchor of the link, can use text or image
			'anchor' => __( "Register", 'event_espresso' ),
			// REQUIRED, if not available at this point, use the next line before this array declaration
			// $event_name = get_event_field( 'event_name', EVENT_DETAILS_TABLE, ' WHERE id = ' . $event_id );
			'event_name' => $event_name
		);
	$cart_link = event_espresso_cart_link( $params );
}
else {
	$cart_link = false;
} 
$display_category_name = do_shortcode( '[CATEGORY_NAME event_id="' . $event_id . '"]' );
if( empty( $display_category_name ) ) {
	$display_category_name = "Uncategorized";
}
$num_attendees = get_number_of_attendees_reg_limit( $event_id, 'num_attendees' ); // gets the number of attendees
?>
<div class="row-fluid">
	<div class="span12 ctlt-espresso-display-padding">
		<div class="media ctlt-espresso-display-padding">
			<a class="pull-left" title="<?php echo stripslashes_deep( $event_name ); ?>" id="a_event_title-<?php echo $event_id; ?>" href="<?php echo $registration_url; ?>">
				<?php echo apply_filters( 'filter_hook_espresso_display_featured_image', $event_id, !empty( $event_meta['event_thumbnail_url'] ) ? $event_meta['event_thumbnail_url'] : '' ); ?>
			</a>
			<div class="media-body">
				<h4 class="media-heading">
					<div id="event_date-<?php echo $event_id; ?>">
						<a title="<?php echo stripslashes_deep( $event_name ); ?>"  class="a_event_title" id="a_event_title-<?php echo $event_id; ?>" href="<?php echo $registration_url; ?>">
							<?php echo stripslashes_deep( $event_name ); ?>
						</a>
					</div>
				</h4>
				<div class="container-fluid">
					<div class="span4">
						<i class="icon-calendar"></i> <?php echo event_date_display( $start_date, get_option( 'date_format' ) ) ?> <!-- displays the date -->
						<?php echo apply_filters( 'filter_hook_espresso_display_ical', $all_meta ) ?> <br /> <!-- displays the ical icon -->
						<i class="icon-time"></i>
						<span class="label label-inverse"> <?php echo espresso_event_time( $event_id, 'start_time' ) . ' - ' . espresso_event_time( $event_id, 'end_time' ) ?></span> <br /> <!-- displays the event time -->
						<i class="icon-folder-open"></i> <?php echo $display_category_name; ?> <br /> <!-- displays the category/series -->
						<i class="icon-user"></i> <?php echo $num_attendees . '/' . $reg_limit; ?> <!-- displays the number of attendees -->
					</div>
					<div class="span5">
						<?php
						// show shorter descriptions set at 15 words max
						if (!empty($event_desc)) {
							$output_desc = explode( " ", $event_desc, 15 );
							if( count( $output_desc ) > 14 ) {
								$output_desc = array_reverse( $output_desc );
								array_shift( $output_desc );
								$output_desc = array_reverse( $output_desc );
								$output_desc[14] = "...";
							}
							$output_desc = implode( " ", $output_desc );
						}
						echo $output_desc;
						?>						
					</div>
					<div class="span3">
						<?php
						// do a conditional check to see if event is full
						// if event is full only display the join waitlist button
						// otherwise show whatever the regular stuff is
						if( $num_attendees >= $reg_limit ) {
								if( $overflow_event_id != '0' && $allow_overflow == 'Y' ) {
									?>
									<h4>
										<a id="regiser_link-<?php echo $overflow_event_id; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>" href="<?php echo espresso_reg_url( $overflow_event_id ); ?>">
											<?php _e( 'Join Waiting List', 'event_espresso' ); ?>
										</a>
									</h4>
									<?php
								}
						}
						else {
							if( isset( $cart_link ) && $externalURL == '' && $cart_link ) {
								echo '<h4>' . $cart_link . '</h4>';
							}
							else {
								?>
								<h4>
									<a id="a_register_link-<?php echo $event_id; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>" href="<?php echo $registration_url; ?>">
										<?php _e( 'Register', 'event_espresso' ); ?>
									</a>
								</h4>
								<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

	
