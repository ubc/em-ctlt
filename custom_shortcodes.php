<?php
/*This is a custom shortcodes file. Please visit the Wordpress Shortcode API [http://codex.wordpress.org/Shortcode_API] for the latest information for making custom shortcodes. */

/*
Shortcode Name: Date Range Display
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This shortcode displays events in a table format and allows registrants to choose an event within a certain date range.
Usage Example: [EVENT_DATE_RANGE date_1="2009-12-22" date_2="2009-12-31"]
Requirements: date_range_display.php - template file
Notes: 
*/
function show_event_date_range($atts) {
	extract(shortcode_atts(array('date_1' => __('No Date Supplied','event_espresso'), 'date_2' => __('No Date Supplied','event_espresso') ), $atts));
	$date_1 = "{$date_1}";
	$date_2 = "{$date_2}";
	ob_start();
	display_event_espresso_date_range($date_1, $date_2);
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('EVENT_DATE_RANGE', 'show_event_date_range');

/*
Shortcode Name: Event Table Display
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This code displays events in a table
Usage Example: [EVENT_TABLE_DISPLAY]
Requirements: table_display.php - template file
Notes: 
*/
function show_event_table_display($atts) {
	ob_start();
	display_event_espresso_table();
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('EVENT_TABLE_DISPLAY', 'show_event_table_display');

/*
Shortcode Name: Upcoming Events
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: Only show events within a certain number number of days into the future. The example below only shows events that start within 30 days from the current date.
Usage Example: [EVENT_DATE_MAX_DAYS max_days="30"]
Requirements: display_event_espresso_date_max() function in the custom_functions.php file
Notes: 
*/
function show_event_date_max($atts) {
	extract(shortcode_atts(array('max_days' => 'No Date Supplied'), $atts));
	$max_days = "{$max_days}";
	ob_start();
	display_event_espresso_date_max($max_days);
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('EVENT_DATE_MAX_DAYS', 'show_event_date_max');

/*
Shortcode Name: Upcoming Events
Author: Leland Zaremba
Contact: 
Website: 
Description: Only show events in a CATEGORY within a certain number number of days into the future. The example below only shows events in a certain category that start within 30 days from the current date.
Usage Example: [EVENT_CAT_DATE_MAX_DAYS max_days="30" event_category_id="1"]
Requirements: event_list_table.php - template file
Notes: 
*/
function show_cat_event_date_max($atts) {
	extract(shortcode_atts(array('max_days' => 'No Date Supplied', 'event_category_id' => __('No Category ID Supplied','event_espresso')), $atts));
	$max_days = "{$max_days}";
	$event_category_id = "{$event_category_id}";
	ob_start();
	display_event_espresso_cat_date_max($max_days, $event_category_id);
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('EVENT_CAT_DATE_MAX_DAYS', 'show_cat_event_date_max');

/*
Shortcode Name: Espresso Movie Table
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://www.eventespresso.com
Description: Displays a movie listing like table. Allows you to show events in a CATEGORY within a certain number number of days into the future and a qty. The example below only shows events in a certain category that start within 30 days from the current date.
Usage Example: [ESPRESSO_MOVIE_TABLE max_days="30" qty="3" category_id="gracecard" ]
Custom CSS for the table display
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have the custom_includes.php files installed in your "/wp-content/uploads/espresso/" directory.
*/
function espresso_movie_table($atts) {
	extract(shortcode_atts(array('max_days' => '', 'qty' => '10','category_id' => 'No Category ID Supplied'), $atts));
	$max_days = "{$max_days}";
	$qty = "{$qty}";
	$category_id = "{$category_id}";
	ob_start();
	espresso_display_movie_table($max_days, $qty, $category_id);
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('ESPRESSO_MOVIE_TABLE', 'espresso_movie_table');

/*
Shortcode Name: Espresso Table
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://www.eventespresso.com
Description: Shows events in a table for showing classes etc. Only show events in a CATEGORY within a certain number number of days into the future and a qty. The example below only shows events in a certain category that start within 30 days from the current date.
Usage Example: [ESPRESSO_TABLE max_days="30" qty="3" category_id="gracecard" order_by="state"]
Custom CSS for the table display
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have the custom_includes.php files installed in your "/wp-content/uploads/espresso/" directory.
*/
function espresso_table($atts) {
	ob_start();
	espresso_display_table($atts);
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode('ESPRESSO_TABLE', 'espresso_table');

/** CTLT START **/
/*
Shortcode Name: CTLT Espresso Category Display
Author: Julien Law
Contact: julienlaw@alumni.ubc.ca
Website: 
Description: Shows all the categories in the category manager.
Usage Example:  [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="current"] to display upcoming events 
				[CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="past"] to display past events
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have the custom_includes.php files installed in your "/wp-content/uploads/espresso/" directory.
*/
function ctlt_espresso_category_display($atts) {
	extract( shortcode_atts( array( 'event_type' => 'current' ), $atts ) );
	$event_type = "{$event_type}";
	ob_start();
	if( isset( $_REQUEST['category_id'], $_REQUEST['category_name'] ) ):
		ctlt_display_event_espresso_category_registration( $event_type );
	else:
		ctlt_display_event_espresso_category( $event_type );
	endif;
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode( 'CTLT_ESPRESSO_CATEGORY_DISPLAY', 'ctlt_espresso_category_display' );

/*
Shortcode name: CTLT Bootstrap Espresso Event Search
Author: Julien law
Contact: julienlaw@alumni.ubc.ca
Website:
Description: A Twitter Bootstrap version of the Event Espresso Search
Usage Example: [CTLT_BOOTSTRAP_EVENT_SEARCH]
Notes: This file should be stored in your "/wp-content/uploads/espresso/" directory.
*/
function ctlt_bootstrap_espresso_event_search() {
    global $wpdb, $espresso_manager, $current_user, $org_options;
    $array = array( 'ee_search' => 'true' );
    $url = add_query_arg( $array, get_permalink( $org_options['event_page_id'] ) );
    ob_start();
    ctlt_display_event_espresso_search( $url );
    $buffer = ob_get_contents();
    ob_end_clean();
    return $buffer;
}
add_shortcode( 'CTLT_BOOTSTRAP_EVENT_SEARCH', 'ctlt_bootstrap_espresso_event_search' );

/*
Shortcode Name: CTLT Past Event List
Author: Julien Law
Contact: julienlaw@alumni.ubc.ca
Website:
Description: Shortcode to display a list of past events
Usage Example: [CTLT_PAST_EVENT_LIST events_per_page=""] events_per_page is an optional parameter where you input the number of events you wish to display per page
Notes: This file should be stored in your "/wp-content/uploads/espresso/" directory.
*/
function ctlt_past_event_list( $attributes ) {
	ob_start();
	ctlt_display_past_events( $attributes );
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
}
add_shortcode( 'CTLT_PAST_EVENT_LIST', 'ctlt_past_event_list');

/*
Shortcode Name: CTLT Event Materials List
Author: Nathan Sidles
Contact: nsidles@ubc.ca
Website:
Description: Shortcode to display a list of event materials
Usage Example: [CTLT_EVENT_MATERIALS_LIST]
Notes: This file should be stored in your "/wp-content/uploads/espresso/" directory.
*/
function ctlt_event_materials_list( $event_id ) {
    ob_start();
    $events = ctlt_display_event_materials_list( $event_id );
	ob_end_clean();
    
    $materials_list = "";
    $upload_base_dir = wp_upload_dir();
    
    foreach($events as $event) {
        $materials_list .= "<p>";
        $materials_list .= "<strong>" . $event->event_name . "</strong> - ";
        $materials_list .= date("F j, Y", strtotime("$event->start_date"));
        $upload_base_dir['baseurl'] .= "<br />";
        $materials_list .= '<a href="' . $upload_base_dir['baseurl'] . '/' . $event->attachment_url . '">Media</a> (right-click to download)';
        $materials_list .= "</p>";
    }
    
    return $materials_list;
}
add_shortcode( 'CTLT_EVENT_MATERIALS_LIST', 'ctlt_event_materials_list');

/*
Shortcode Name: CTLT Event Search
Author: Nathan Sidles
Contact: nsidles@ubc.ca
Website:
Description: Shortcode to display a list of event materials
Usage Example: [CTLT_EVENT_MATERIALS_LIST]
Notes: This file should be stored in your "/wp-content/uploads/espresso/" directory.
*/
function ctlt_autocomplete_search(){
	global $wpdb, $espresso_manager, $current_user, $org_options;
	$array = array('ee_search' => 'true');
	$url = add_query_arg($array, get_permalink($org_options['event_page_id']));
	ob_start();
	?>
	<div id="espresso-search-form-dv" class="ui-widget">
		<form name="form" method="post" action="<?php echo $url ?>">
			<input id="ee_autocomplete" name="ee_name" />
			<input id="ee_search_submit" name="ee_search_submit" class="btn" type="submit" value="Search" />
			<input id="event_id" name="event_id" type="hidden">
		</form>
	</div>
<?php 
	$ee_autocomplete_params = array();
	$SQL = "SELECT e.*";
	if ( isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ) {
		$SQL .= ", v.city venue_city, v.state venue_state, v.name venue_name, v.address venue_address, v.city venue_city, v.state venue_state, v.zip venue_zip, v.country venue_country, v.meta venue_meta ";		
	}
	$SQL .= " FROM " . EVENTS_DETAIL_TABLE . " e ";
	if ( isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ) {
		$SQL .= " LEFT JOIN " . EVENTS_VENUE_REL_TABLE . " vr ON vr.event_id = e.id LEFT JOIN " . EVENTS_VENUE_TABLE . " v ON v.id = vr.venue_id ";
	}
	$SQL .= " WHERE e.is_active = 'Y' ";
	$SQL .= " AND e.event_status != 'D' AND e.event_status != 'S' ";
	//echo '<p>$sql = '.$sql.'</p>';							
	$events = $wpdb->get_results($SQL);
	$num_rows = $wpdb->num_rows;
								
	if ($num_rows > 0) {
		foreach ($events as $event){
			$venue_city = !empty($event->venue_city) ? stripslashes_deep($event->venue_city)  : '';
			$venue_state = !empty($event->venue_state) ?  (!empty($event->venue_city) ? ', ' : '') .stripslashes_deep($event->venue_state)  : '';

			$venue_name = !empty($event->venue_name) ?' @' . stripslashes_deep($event->venue_name)  . ' - ' . $venue_city . $venue_state . ''  : '';
			//An Array of Objects with label and value properties:
			$ee_autocomplete_params[] = array( 
							'url' => espresso_reg_url($event->id), 
							'value' => stripslashes_deep($event->event_name) . $venue_name, 
							'id' => $event->id
					);
			//echo '{ url:"'.espresso_reg_url($event->id).'", value: "'.stripslashes_deep($event->event_name) . $venue_name .'", id: "'.$event->id.'" },';
		}
	}
	wp_register_script('espresso_autocomplete', (EVENT_ESPRESSO_PLUGINFULLURL . "scripts/espresso_autocomplete.js"), array( 'jquery-ui-autocomplete' ), '1.0.0', TRUE );
	wp_enqueue_script('espresso_autocomplete');
	wp_localize_script( 'espresso_autocomplete', 'ee_autocomplete_params', $ee_autocomplete_params );
	//Load scripts
	add_action('wp_footer', 'ee_load_jquery_autocomplete_scripts');	
	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;		
}
add_shortcode('CTLT_EVENT_SEARCH', 'ctlt_autocomplete_search');


/** CTLT END **/
