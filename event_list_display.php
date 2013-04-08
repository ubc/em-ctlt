<?php

/*
 * Version: 0.9
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
?>
	<td>
		<div class="event-meta">
				<?php //Featured image
				echo apply_filters('filter_hook_espresso_display_featured_image', $event_id, !empty($event_meta['event_thumbnail_url']) ? $event_meta['event_thumbnail_url'] : '');?>
				<?php /*if ( $event->event_cost != '0.00' ) { ?>
					 <p id="p_event_price-<?php echo $event_id ?>" class="event_price"><span class="section-title"><?php  echo __('Price: ', 'event_espresso'); ?></span> <?php echo  $org_options['currency_symbol'].$event->event_cost; ?></p>
				<?php } else { ?>
					<p id="p_event_price-<?php echo $event_id ?>" class="event_price"><?php echo __('Free Event', 'event_espresso'); ?></p>
				<?php }*/?>

			<p id="event_date-<?php echo $event_id ?>"><!--<span class="section-title"><?php _e('Date:', 'event_espresso'); ?></span>-->  <?php echo event_date_display($start_date, get_option('date_format')) ?> 
				<?php //Add to calendar button
				echo apply_filters('filter_hook_espresso_display_ical', $all_meta) . '<br/>';
				echo espresso_event_time( $event_id, 'start_time' ) . ' - ' . espresso_event_time( $event_id, 'end_time');?>
			</p>
		</div>
	</td>
	<td>
		<!--<div id="event_data-<?php echo $event_id ?>" class="event_data <?php echo $css_class; ?> <?php echo $category_identifier; ?> event-data-display event-list-display event-display-boxes ui-widget">-->
		<div id="event_data-<?php echo $event_id ?>" >
			<a title="<?php echo stripslashes_deep($event_name) ?>" class="a_event_title" id="a_event_title-<?php echo $event_id ?>" href="<?php echo $registration_url; ?>"><?php echo stripslashes_deep($event_name) ?></a>
				<?php /* These are custom messages that can be displayed based on the event status. Just un-comment the one you want to use. */ ?>
				<?php //echo $status_display; //Turn this on to display the overall status of the event.  ?>
				<?php //echo $status_display_ongoing; //Turn this on to display the ongoing message. ?>
				<?php //echo $status_display_deleted; //Turn this on to display the deleted message. ?>
				<?php //echo $status_display_secondary; //Turn this on to display the waitlist message. ?>
				<?php //echo $status_display_reg_closed; //Turn this on to display the registration closed message. ?>
				<?php //echo $status_display_not_open; //Turn this on to display the secondary message. ?>
				<?php //echo $status_display_open; //Turn this on to display the not open message. ?>
				<?php //echo $status_display_custom_closed; //Turn this on to display the closed message. ?>
		</div>
	</td>
	<td>
		<a><?php echo do_shortcode( '[CATEGORY_NAME event_id="' . $event_id . '"]' ); ?></a>
	</td>
	<td>
		<?php
	//Show short descriptions
		if (!empty($event_desc) && isset($org_options['display_short_description_in_event_list']) && $org_options['display_short_description_in_event_list'] == 'Y') {
			$output_desc = explode( " ", $event_desc, 15 );
			if( count( $output_desc ) > 14 ) {
				$output_desc = array_reverse( $output_desc );
				array_shift( $output_desc );
				$output_desc = array_reverse( $output_desc );
				$output_desc[14] = "...";
			}
			$output_desc = implode( " ", $output_desc );
			//var_dump($output_desc);
			?>
			<div class="event-desc">
				<?php echo espresso_format_content($output_desc); ?>
			</div>
			<?php
		}
		?>
	</td>
	<td>
		<?php
			if( $multi_reg && event_espresso_get_status( $event_id ) == 'ACTIVE' ) {
				$params = array(
					// REQUIRED, the id of the event that needs to be added to the cart
					'event_id' => $event_id,
					// REQUIRED, anchor of the link, can use text or image
					'anchor' => __( "Add to Cart", 'event_espresso' ),
					// REQUIRED, if not available at this point, use the next line before this array declaration
					// $event_name = get_event_field( 'event_name', EVENT_DETAILS_TABLE, ' WHERE id = ' . $event_id );
					'event_name' => $event_name,
					// OPTIONAL, will place this term before the link
					'separator' => __( " or ", 'event_espresso' )
				);
				$cart_link = event_espresso_cart_link( $params );
			}
			else {
				$cart_link = false;
			}
			if( $display_reg_form == 'Y' ) {
				// check to see if the Members plugin is installed
				$member_options = get_option( 'events_member_settings' );
				if( function_exists( 'espresso_members_installed' ) && espresso_members_installed() == true && !is_user_logged_in() && ( $member_only == 'Y' || $member_options['member_only_all'] == 'Y' ) ) {
					echo '<p class="ee_member_only">' . __( 'Member Only Event', 'event_espresso' ) . '</p>';
				}
				else {
					?>
					<p id="register_link-<?php echo $event_id; ?>" class="register-link-footer">
						<a id="a_register_link-<?php echo $event_id ?>" href="<?php echo $registration_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'View Details', 'event_espresso' ); ?></a>
						<?php echo isset( $cart_link ) && $externalURL == '' ? $cart_link : ''; ?>
					</p><?php
				}
			}
			else {
				?>
				<p id="register_link-<?php echo $event_id; ?>" class="register-link-footer">
					<a id="a_register_link-<?php echo $event_id ?>" href="<?php echo $registration_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'View Details', 'event_espresso' ); ?></a>
					<?php echo isset( $cart_link ) && $externalURL == '' ? $cart_link : ''; ?>
				</p><?php
			}
		?>
	</td>
	
<!--</div> / .event-data-display -->
<!--</div> / .event-display-boxes -->
