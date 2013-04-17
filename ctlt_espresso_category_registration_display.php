<?php
/*
Template Name: Category Registration for Events
Author: Julien law
Version: 0.11
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
if( $multi_reg && event_espresso_get_status( $event_id ) == 'ACTIVE' ) {
	$params = array(
			// REQUIRED, the id of the event that needs to be added to the cart
			'event_id' => $event_id,
			// REQUIRED, anchor of the link, can use text or image
			'anchor' => __( "Add to cart", 'event_espresso' ),
			// REQUIRED, if not available at this point, use the next line before this array declaration
			// $event_name = get_event_field( 'event_name', EVENT_DETAILS_TABLE, ' WHERE id = ' . $event_id );
			'event_name' => $event_name
		);
	$cart_link = event_espresso_cart_link( $params );
}
else {
	$cart_link = false;
} ?>
		<!--<p id="register_link-<?php echo $event_id; ?>" class="register-link-footer">
			<?php echo isset( $cart_link ) && $externalURL == '' ? $cart_link : ''; ?>
		</p>-->
	<div class="row-fluid">
		<div class="span12" style="border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
			<div class="media" style="padding: 10px;">
				<a class="pull-left" href="<?php echo $post_url?>">
					<?php echo apply_filters( 'filter_hook_espresso_display_featured_image', $event_id, !empty( $event_meta['event_thumbnail_url '] ) ? $event_meta['event_thumbnail_url'] : '' ); ?>
				</a>
				<div class="media-body">
					<h4 class="media-heading">
						<div id="event_data-<?php echo $event_id; ?>"><a title="<?php echo stripslashes_deep( $event_name ) ?>" class="a_event_title" id="a_event_title-<?php echo $event_id ?>" href="<?php echo $post_url ?>"><?php echo stripslashes_deep( $event_name ) ?></a></div>
						<div class="pull-right" style="margin-right: 20px;">
							<?php
							if( isset( $cart_link ) && $externalURL == '' && $cart_link) {
								?>
								<?php echo $cart_link; ?>
								<?php
							}
							else { 
								?>
								<a id="a_register_link-<?php echo $event_id; ?>" title="<?php echo stripslashes_deep( $event_name ) ?>" href="<?php echo $post_url; ?>"><?php _e( 'Register', 'event_espresso') ?></a>
								<?php
							} ?>
						</div>
					</h4>
					<p>
						<i class="icon-calendar"></i> <?php echo event_date_display( $start_date, get_option('date_format') ); ?> |
						<i class="icon-time"></i> <span class="label label-inverse"><?php echo espresso_event_time( $event_id, 'start_time' ) . ' - ' . espresso_event_time( $event_id, 'end_time' ) ?></span>
						<br /><?php echo stripslashes_deep( $event_desc );?>
					</p>
				</div>
			</div>
		</div>
	</div>
