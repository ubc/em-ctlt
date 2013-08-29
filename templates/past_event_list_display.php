<?php

/*
 * Version: 0.16
 */
// This is the past event list template page.
// This is a template file for displaying a past event list on a page.
// This file should be paired with the past_event_list.php file
/*
 * Shortcode Usage
 * [CTLT_PAST_EVENT_LIST] will display a list of past events
 * [CTLT_PAST_EVENT_LIST current_page=1] will set the page on load to be at 1 (pageload at 1 is default behaviour)
 * [CTLT_PAST_EVENT_LIST events_per_page=5] will set the number of events to display per page at 5 (5 events per page is default behaviour)
 * [CTLT_PAST_EVENT_LIST num_page_links_to_display=10]
 * [CTLT_PAST_EVENT_LIST use_wrapper="true"]
 *
 * Example:
 * [CTLT_PAST_EVENT_LIST events_per_page=5 current_page=2]
 */

// Print out the array of event status options
// print_r( event_espresso_get_is_active( $event_id ) );
// Here we can create messages based on the event status. These variables can be echoed anywhere on the page to display your status message.
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

// You can also display a custom message. For example, this is a custom registration no open message:
$status_display_custom_closed = $status['status'] == 'REGISTRATION_CLOSED' ? ' - <span class="espresso_closed">' . __( 'Registration is closed', 'event_espresso' ) . '</span>' : '';
global $this_event_id;
$this_event_id = $event_id;

$display_category_name = do_shortcode( '[CATEGORY_NAME event_id="' . $event_id . '"]' );
if( empty( $display_category_name ) ) {
	$display_category_name = "Uncategorized";
}
$num_attendees = get_number_of_attendees_reg_limit( $event_id, 'num_attendees' ); // gets the number of attendees
//$event_time = espresso_event_time( $event_id, 'start_time' );
?>

<div class="row-fluid">
	<div class="span12 ctlt-espresso-display-event">
		<div class="media ctlt-espresso-display-padding">
			<a class="pull-left" title="<?php echo stripslashes_deep( $event_name ); ?>" id="a_event_title-<?php echo $event_id; ?>" href="<?php echo $registration_url; ?>">
				<?php echo apply_filters( 'filter_hook_espresso_display_featured_image', $event_id, !empty( $event_meta['event_thumbnail_url'] ) ? $event_meta['event_thumbnail_url'] : '' ); ?>
			</a>
			<div class="media-body">
				<h4 class="media-heading">
					<div id="event_date-<?php echo $event_id; ?>">
						<a title="<?php echo stripslashes_deep( $event_name ); ?>" class="a_event_title" id="a_event_title-<?php echo $event_id; ?>" href="<?php echo $registration_url; ?>">
							<?php echo stripslashes_deep( $event_name ); ?>
						</a>
					</div>
				</h4>
				<div class="container-fluid">
					<div class="span4">
						<i class="icon-calendar"></i> <?php echo event_date_display( $start_date, get_option( 'date_format' ) ) ?> <!-- displays the date -->
						<?php echo apply_filters( 'filter_hook_espresso_display_ical', $all_meta ); ?> <br /> <!-- displays the ical icon -->
						<i class="icon-time"></i>
						<span class="label label-inverse"> <?php echo espresso_event_time( $event_id, 'start_time' ) . ' - ' . espresso_event_time( $event_id, 'end_time' ); ?></span> <br /> <!-- displays the event time -->
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
						echo espresso_format_content( $output_desc );
						?>						
					</div>
					<div class="span3">
						<!-- since this is a past event you cannot register, so there will be no multi-event-reg functionality for this -->
						<h4>
							<a title="<?php echo stripslashes_deep( $event_name ); ?>" class="a_event_title" id="a_event_title-<?php echo $event_id; ?>" href="<?php echo $registration_url; ?>">
								<?php _e( 'View Event', 'event_espresso' ); ?>
							</a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
