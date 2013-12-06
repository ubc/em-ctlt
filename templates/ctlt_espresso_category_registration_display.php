<?php
/*
Template Name: Category Registration for Events
Author: Julien law, Nathan Sidles, Event Espresso
Version: 0.12
Website:
Description: This is a template file for displaying a list of categories.
Requirements: ctlt_espresso_category_display.php, ctlt_espresso_category_registration_display.php, custom_shortcodes.php, custom_includes.php, custom_functions.php
Shortcode Usage:   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="current"] to display upcoming events
                   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="past"] to display past events
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account page
*/
// This is the category event list template page.
// This is a template file for displaying a category event list on a page

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
    <div class="ctlt_event_list_wrapper">
        <div class="ctlt_event_list_date">
        <?php
            if($test != event_date_display($start_date, get_option('date_format'))) { ?>
                    <h4><?php echo event_date_display($start_date, get_option('date_format')); ?></h4>
            <?php }
            $test = event_date_display($start_date, get_option('date_format'));
        ?>
        </div>
    <div class="ctlt_event_list_details">
	<h4 id="event_title-<?php echo $event_id ?>"><a title="<?php echo stripslashes_deep($event_name) ?>" class="a_event_title" id="a_event_title-<?php echo $event_id ?>" href="<?php echo $post_url ?>"><?php echo stripslashes_deep($event_name) ?></a>
		<?php /* These are custom messages that can be displayed based on the event status. Just un-comment the one you want to use. */ ?>
		<?php //echo $status_display; //Turn this on to display the overall status of the event.  ?>
		<?php //echo $status_display_ongoing; //Turn this on to display the ongoing message. ?>
		<?php //echo $status_display_deleted; //Turn this on to display the deleted message. ?>
		<?php //echo $status_display_secondary; //Turn this on to display the waitlist message. ?>
		<?php //echo $status_display_reg_closed; //Turn this on to display the registration closed message. ?>
		<?php //echo $status_display_not_open; //Turn this on to display the secondary message. ?>
		<?php //echo $status_display_open; //Turn this on to display the not open message. ?>
		<?php //echo $status_display_custom_closed; //Turn this on to display the closed message. ?>
	</h4>
	<?php /* Venue details. Un-comment to display. */ ?>
    <?php echo '<p class="ctlt-event-block">'; ?>
    <?php if($end_date != $start_date) { ?>
        <?php echo espresso_event_time($event_id, 'start_date') . ' - ' . espresso_event_time($event_id, 'end_date') . '; '; ?>
    <?php } ?>
    
    <?php echo espresso_event_time($event_id, 'start_time') . ' - ' . espresso_event_time($event_id, 'end_time') . '';
        echo $venue_title != ''?'':'';
        echo $venue_url != ''?'<a href="'.$venue_url.'">':'';
        echo $venue_title != ''? '<br />' . stripslashes_deep($venue_title) . '</p>' : '</p>';
        echo $venue_url != ''?'</a>':''; ?>
	<?php // echo $venue_address != ''?'<p id="event_venue_address-'.$event_id.'" class="event_venue_address">'.stripslashes_deep($venue_address).'</p>':''?>
	<?php // echo $venue_address2 != ''?'<p id="event_venue_address2-'.$event_id.'" class="event_venue_address2">'.stripslashes_deep($venue_address2).'</p>':''?>
	<?php // echo $venue_city != ''?'<p id="event_venue_city-'.$event_id.'" class="event_venue_city">'.stripslashes_deep($venue_city).'</p>':''?>
	<?php // echo $venue_state != ''?'<p id="event_venue_state-'.$event_id.'" class="event_venue_state">'.stripslashes_deep($venue_state).'</p>':''?>
	<?php // echo $venue_zip != ''?'<p id="event_venue_zip-'.$event_id.'" class="event_venue_zip">'.stripslashes_deep($venue_zip).'</p>':''?>
	<?php // echo $venue_country != ''?'<p id="event_venue_country-'.$event_id.'" class="event_venue_country">'.stripslashes_deep($venue_country).'</p>':''
	$event->event_cost = empty($event->event_cost) ? '' : $event->event_cost;
	?>

	<div class="event-meta">
			<?php //Featured image
			echo apply_filters('filter_hook_espresso_display_featured_image', $event_id, !empty($event_meta['event_thumbnail_url']) ? $event_meta['event_thumbnail_url'] : '');?>
	</div>
	<?php
//Show short descriptions
	if (!empty($event_desc) && isset($org_options['display_short_description_in_event_list']) && $org_options['display_short_description_in_event_list'] == 'Y') {
		?>
		<div class="event-desc">
			<?php echo espresso_format_content($event_desc); ?>
		</div>
		<?php
	}
	?>

	<?php if ( (isset($location) && $location != '' ) && (isset($org_options['display_address_in_event_list']) && $org_options['display_address_in_event_list'] == 'Y') ) { ?>
		<p class="event_address" id="event_address-<?php echo $event_id ?>"><span class="section-title"><?php echo __('Address:', 'event_espresso'); ?></span> <br />
			
			<span class="address-block">
			<?php echo stripslashes_deep($venue_title); ?><br />
			<?php echo stripslashes_deep($location); ?>
				<span class="google-map-link"><?php echo $google_map_link; ?></span></span>
		</p>
		<?php
	}

	$num_attendees = get_number_of_attendees_reg_limit($event_id, 'num_attendees'); //Get the number of attendees. Please visit http://eventespresso.com/forums/?p=247 for available parameters for the get_number_of_attendees_reg_limit() function.
	if ($num_attendees >= $reg_limit) {
		?>
		<p id="available_spaces-<?php echo $event_id ?>" class="available-spaces"><span class="section-title"><?php _e('Available Spaces:', 'event_espresso') ?> </span><?php echo get_number_of_attendees_reg_limit($event_id, 'available_spaces', 'All Seats Reserved') ?></p>
		<?php if ($overflow_event_id != '0' && $allow_overflow == 'Y') { ?>
			<p id="register_link-<?php echo $overflow_event_id ?>" class="register-link-footer"><a class="a_register_link ui-button ui-button-big ui-priority-primary ui-state-default ui-state-hover ui-state-focus ui-corner-all" id="a_register_link-<?php echo $overflow_event_id ?>" href="<?php echo espresso_reg_url($overflow_event_id); ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('Join Waiting List', 'event_espresso'); ?></a></p>
			<?php
		}
	} else {
		if ($display_reg_form == 'Y' && $externalURL == '' && $reg_limit < 999999) {
			?>			<p id="available_spaces-<?php echo $event_id ?>" class="spaces-available"><span class="section-title"><?php _e('Available Spaces:', 'event_espresso') ?></span> <?php echo get_number_of_attendees_reg_limit($event_id, 'available_spaces') ?> / <?php echo get_number_of_attendees_reg_limit($event_id, 'reg_limit') ?></p>
			<?php
		}

		/**
		 * Load the multi event link.
		 * */
		//Un-comment these next lines to check if the event is active
		//echo event_espresso_get_status($event_id);
		//print_r( event_espresso_get_is_active($event_id));

        if( is_user_logged_in() ) {
            if ($multi_reg && event_espresso_get_status($event_id) == 'ACTIVE'/* && $display_reg_form == 'Y'*/) {
            // Uncomment && $display_reg_form == 'Y' in the line above to hide the add to cart link/button form the event list when the registration form is turned off.

                $params = array(
                    //REQUIRED, the id of the event that needs to be added to the cart
                    'event_id' => $event_id,
                    //REQUIRED, Anchor of the link, can use text or image
                    'anchor' => __("Add to Cart", 'event_espresso'), //'anchor' => '<img src="' . EVENT_ESPRESSO_PLUGINFULLURL . 'images/cart_add.png" />',
                    //REQUIRED, if not available at this point, use the next line before this array declaration
                    // $event_name = get_event_field('event_name', EVENTS_DETAIL_TABLE, ' WHERE id = ' . $event_id);
                    'event_name' => $event_name,
                    //OPTIONAL, will place this term before the link
                    'separator' => __(" or ", 'event_espresso')
                );

                $cart_link = event_espresso_cart_link($params);
            }else{
                $cart_link = false;
            }
            if ($display_reg_form == 'Y') {
                //Check to see if the Members plugin is installed.
                $member_options = get_option('events_member_settings');
                if ( function_exists('espresso_members_installed') && espresso_members_installed() == true && !is_user_logged_in() && ($member_only == 'Y' || $member_options['member_only_all'] == 'Y') ) {
                    echo '<p class="ee_member_only">'.__('Member Only Event', 'event_espresso').'</p>';
                }else{
                ?>
                    <p id="register_link-<?php echo $event_id ?>" class="register-link-footer">
                        <a class="btn" id="a_register_link-<?php echo $event_id ?>" href="<?php echo $registration_url; ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('Register', 'event_espresso'); ?></a>
                        <?php echo isset($cart_link) && $externalURL == '' ? $cart_link : ''; ?>
                    </p>
        <?php 
                }
            } else { 
        ?>
                <p id="register_link-<?php echo $event_id ?>" class="register-link-footer">
                    <a class="a_register_link ui-button ui-button-big ui-priority-primary ui-state-default ui-state-hover ui-state-focus ui-corner-all" id="a_register_link-<?php echo $event_id ?>" href="<?php echo $registration_url; ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('View Details', 'event_espresso'); ?></a> <?php echo isset($cart_link) && $externalURL == '' ? $cart_link : ''; ?>
                </p>
                
            <?php
            }
        } else { ?>
        <?php } ?>
        </div>
        </div><?php
	}
	?>
