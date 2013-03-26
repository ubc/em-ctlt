<?php
/*
Template Name: Category Registration for Events
Author: Julien law
Contact:
Website:
Description: This is a template file for displaying a list of categories.
Requirements: ctlt_espresso_category_display.php
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account page
*/

// registering scripts and styles here for sorting the table
wp_register_script( 'ctlt_event_espresso_sort_table', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/js/espresso_sort_table.js', array('jquery'), '1.0', true );
wp_register_script( 'ctlt_table_sorter_library', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/js/jquery.tablesorter.js', array('jquery'), '1.0', true );
wp_register_style( 'ctlt_table_sorter_style', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/css/ctlt_event_espresso_list.css' );
// add the above scripts and styles to the table so that the jQuery sorting function can be used
wp_enqueue_script( 'ctlt_event_espresso_sort_table' );
wp_enqueue_script( 'ctlt_table_sorter_library' );
wp_enqueue_style( 'ctlt_table_sorter_style' );

function ctlt_display_event_espresso_category_registration() {
	// need to find a way to make this more secure
	$url_cat_id = $_GET['category_id'];
	global $wpdb;
	$sql = "SELECT e.*, c.category_name, c.category_desc, c.display_desc, ese.start_time FROM " . EVENTS_DETAIL_TABLE . " e
	JOIN " . EVENTS_START_END_TABLE . " ese ON ese.event_id = e.id
	JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
	JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
	WHERE e.is_active = 'Y'
	AND c.id = '" . $url_cat_id . "'
	ORDER BY date(e.start_date), ese.start_time";

	ctlt_event_espresso_get_category_registration_view( $sql );
}

// Events Category Registration form listing
function ctlt_event_espresso_get_category_registration_view( $sql ) {
	$cat_sql = "SELECT * FROM " . EVENTS_CATEGORY_TABLE . "
	WHERE id = '" . $_GET['category_id'] . "'";
	event_espresso_session_start();
	if( !isset($_SESSION['event_espresso_sessionid'])) {
		$sessionid = (mt_rand(100,999).time());
		$_SESSION['event_espresso_sessionid'] = $sessionid;
	}
	$template_name = ( 'ctlt_espresso_category_registration_display.php' );
	$path = locate_template( $template_name );

	global $wpdb, $org_options;
	$events = $wpdb->get_results( $sql );
	$categories = $wpdb->get_results( $cat_sql );
	$num_rows = $wpdb->num_rows;

	// TODO: generate content and the category information
	//	var_dump( $categories );
	foreach( $categories as $category ) {
		$cat_name = $category->category_name;
		$cat_desc = $category->category_desc;
		?>
		<h3><?php echo $cat_name;?></h3>
		<p class="section-title">
			<?php _e('Series Description: ', 'event_espresso'); ?>
		</p>
		<div class="event_description clearfix">
			<?php echo espresso_format_content($cat_desc); ?>
		</div>
	<?php
	}
	if( $num_rows > 0 ) {
		// do some stuff here
		?>

		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th>Date</th><th>Title</th><th>Description</th><th>test column</th>
				</tr>
			</thead>
			<tbody>
		<?php	foreach( $events as $event ) {
					$event_id = $event->id;
					$event_name = $event->event_name;
					$event_desc = stripslashes_deep($event->event_desc);
					$event_identifier = $event->event_identifier;
					$active = $event->is_active;
					$registration_start = $event->registration_start;
					$registration_end = $event->registration_end;
					$start_date = $event->start_date;
					$end_date = $event->end_date;
					$reg_limit = $event->reg_limit;
					$event_address = $event->address;
					$event_address2 = $event->address2;
					$event_city = $event->city;
					$event_state = $event->state;
					$event_zip = $event->zip;
					$event_country = $event->country;
					$member_only = $event->member_only;
					$externalURL = $event->externalURL;
					$recurrence_id = $event->recurrence_id;
					$display_reg_form = $event->display_reg_form;
					$allow_overflow = $event->allow_overflow;
					$overflow_event_id = $event->overflow_event_id;
					if( !empty($event_desc) ) {
						$event_desc = explode( " ", $event_desc, 15 );
						if( count( $event_desc ) > 14 ) {
							$event_desc = array_reverse( $event_desc );
							array_shift( $event_desc );
							$event_desc = array_reverse( $event_desc );
							$event_desc[14] = "...";
						}
						$event_desc = implode( " ", $event_desc );
					}
					$post_url = get_site_url() . '?page_id=' . $org_options['event_page_id'] . '&regevent_action=register&event_id=' . $event_id;
					?>
					<tr>
						<td><?php echo event_date_display($start_date, get_option('date_format')); ?></td>
						<td><a href="<?php echo $post_url; ?>" id="event-reg-id-<?php echo $event_id; ?>"><?php echo $event_name; ?></a></td>
						<td><?php echo $event_desc; ?></td>
						<td><input type="checkbox" name="<?php echo $event_name; ?>" value="<?php echo $event_name; ?>" id="<?php echo $event_id; ?>"></td>
					</tr>
					<?php
				}?>
			</tbody>
		</table>
		<button class="btn" type="button">Register</button>
		<?php

	}
	else {
		?><p>There are no events available in this category...</p><?php
	}
}