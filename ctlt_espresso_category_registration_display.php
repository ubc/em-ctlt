<?php
/*
Template Name: Category Registration for Events
Author: Julien law
Version: 0.9
Website:
Description: This is a template file for displaying a list of categories.
Requirements: ctlt_espresso_category_display.php, ctlt_espresso_category_registration_display.php, custom_shortcodes.php, custom_includes.php, custom_functions.php
Shortcode Usage:   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="current"] to display upcoming events
                   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="past"] to display past events
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account page
*/
// This is the category event list template page.
// This is a template file for displaying a category event list on a page

global $this_event_id;
$this_event_id = $event_id;
?>
	<tr>
		<td>
			<div class="event-meta">
				<p id="event_date-<?php echo $event_id; ?>">
					<?php
						echo event_date_display( $start_date, get_option('date_format') ) . '<br/>';
						//echo apply_filters( 'filter_hook_espresso_display_ical', $all_meta ) . '<br/>';
						//echo espresso_event_time( $event_id, 'start_time' ) . ' - ' . espresso_event_time( $event_id, 'end_time' );
					?>
				</p>
			</div>
		</td>
		<td>
			<div id="event_data-<?php echo $event_id; ?>">
				<!--<?php if( !$multi_reg ) { ?>
				<a title="<?php echo stripslashes_deep( $event_name ); ?>" class="a_event_title" id="a_event_title-<?php echo $event_id ?>" href="<?php echo $post_url;?>"><?php echo stripslashes_deep( $event_name ); ?></a>
				<?php }
				else {
					echo stripslashes_deep( $event_name );
				} ?>-->
				<?php echo stripslashes_deep( $event_name ); ?>
			</div>
		</td>
		<td>
			<div class="event-desc">
				<?php echo espresso_format_content( $event_desc ); ?>
			</div>
		</td>
		<td>
		<?php 
			if( $multi_reg && event_espresso_get_status( $event_id ) == 'ACTIVE' ) { 
				$params = array(
					// REQUIRED, The id of the event that needs to be added to the cart
					'event_id' => $event_id,
					// REQUIRED, Anchor of the link, can use text or image
					'anchor' => __( "Add to Cart", 'event_espresso' ),
					// REQUIRED, If not available at this point, use the next line before this array declaration
					// $event_name = get_event_field( 'event_name', EVENT_DETAILS_TABLE, ' WHERE id = ' . $event_id );
					'event_name' => $event_name,
					// OPTIONAL, Will place this term before the link
					'separator' => __( " or ", 'event_espresso' )
				);
				$cart_link = event_espresso_cart_link( $params );
			}
			else {
				$cart_link = false;
			} 
			if( $display_reg_form == 'Y' ) {
				// Check to see if the Members plugin is installed
				$member_options = get_option( 'events_member_settings' );
				if( function_exists( 'espresso_members_installed' ) && espresso_members_installed() == true && !is_user_logged_in() && ( $member_only == 'Y' || $member_options['member_only_all'] == 'Y' ) ) {
					echo '<p class="ee_member_only">' . __( 'Member Only Event', 'event_espresso' ) . '</p>';
				}
				else {
				?>
					<p id="register_link-<?php echo $event_id; ?>" class="register-link-footer">
						<!--<a class="a_register_link ui-button ui-button-big ui-priority-primary ui-state-default ui-state-hover ui-state-focus ui-corner-all" id="a_register_link-<?php echo $event_id; ?>" href="<?php echo $post_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'Register', 'event_espresso' ); ?></a>-->
						<a id="a_register_link-<?php echo $event_id; ?>" href="<?php echo $post_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'Register', 'event_espresso' ); ?></a>
						<?php echo isset( $cart_link ) && $externalURL == '' ? $cart_link : ''; ?>
					</p>
				<?php
				}
			}
			else { ?>
				<p id="register_link-<?php echo $event_id; ?>" class="register-link-footer">
					<!--<a class="a_register_link ui-button ui-button-big ui-priority-primary ui-state-default ui-state-hover ui-state-focus ui-corner-all" id="a_register_link-<?php echo $event_id ?>" href="<?php echo $post_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'View Details', 'event_espresso' ); ?></a>-->
					<a id="a_register_link-<?php echo $event_id ?>" href="<?php echo $post_url; ?>" title="<?php echo stripslashes_deep( $event_name ); ?>"><?php _e( 'View Details', 'event_espresso' ); ?></a>
					<?php echo isset( $cart_link ) && $externalURL == '' ? $cart_link : ''; ?>
				</p>
				<?php	
			}

		?>
		</td>
	</tr>