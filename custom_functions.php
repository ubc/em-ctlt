<?php
/*
Function Name: Maximum Date Display
Author: Seth Shoultes
Contact: seth@smartwebutah.com
Website: http://shoultes.net
Description: This function is used in the Events Table Display template file to show events for a maximum number of days in the future
Usage Example: 
Requirements: Events Table Display template file
Notes: 
*/
function display_event_espresso_date_max($max_days="null"){
	global $wpdb;
	//$org_options = get_option('events_organization_settings');
	//$event_page_id =$org_options['event_page_id'];
	if ($max_days != "null"){
		if ($_REQUEST['show_date_max'] == '1'){
			foreach ($_REQUEST as $k=>$v) $$k=$v;
		}
		$max_days = $max_days;
		$sql  = "SELECT * FROM " . EVENTS_DETAIL_TABLE . " WHERE ADDDATE('".date ( 'Y-m-d' )."', INTERVAL ".$max_days." DAY) >= start_date AND start_date >= '".date ( 'Y-m-d' )."' AND is_active = 'Y' ORDER BY date(start_date)";
		event_espresso_get_event_details($sql);//This function is called from the event_list.php file which should be located in your templates directory.

	}				
}

/*
Function Name: Event Status
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This function is used to display the status of an event.
Usage Example: Can be used to display custom status messages in your events.
Requirements: 
Notes: 
*/
if (!function_exists('espresso_event_status')) {
	function espresso_event_status($event_id){
		$event_status = event_espresso_get_is_active($event_id);
		
		//These messages can be uesd to display the status of the an event.
		switch ($event_status['status']){
			case 'EXPIRED':
				$event_status_text = __('This event is expired.','event_espresso');
			break;
			
			case 'ACTIVE':
				$event_status_text = __('This event is active.','event_espresso');
			break;
			
			case 'NOT_ACTIVE':
				$event_status_text = __('This event is not active.','event_espresso');
			break;
			
			case 'ONGOING':
				$event_status_text = __('This is an ongoing event.','event_espresso');
			break;
			
			case 'SECONDARY':
				$event_status_text = __('This is a secondary/waiting list event.','event_espresso');
			break;
			
		}
		return $event_status_text;
	}
}

/*
Function Name: Custom Event List Builder
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This function creates lists of events using custom templates.
Usage Example: Create a page or widget template to show events.
Requirements: Template files must be stored in your wp-content/uploads/espresso/templates directory
Notes: 
*/
if (!function_exists('espresso_list_builder')) {
	function espresso_list_builder($sql, $template_file, $before, $after){
		
		global $wpdb, $org_options;
		//echo 'This page is located in ' . get_option( 'upload_path' );
		$event_page_id = $org_options['event_page_id'];
		$currency_symbol = $org_options['currency_symbol'];
		$events = $wpdb->get_results($sql);
		$category_id = $wpdb->last_result[0]->id;
		$category_name = $wpdb->last_result[0]->category_name;
		$category_desc = html_entity_decode( wpautop($wpdb->last_result[0]->category_desc) );
		$display_desc = $wpdb->last_result[0]->display_desc;
		
		if ($display_desc == 'Y'){
			echo '<p id="events_category_name-'. $category_id . '" class="events_category_name">' . stripslashes_deep($category_name) . '</p>';
			echo wpautop($category_desc);				
		}
		
		foreach ($events as $event){
			$event_id = $event->id;
			$event_name = $event->event_name;
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
			
			$allow_overflow = $event->allow_overflow;
			$overflow_event_id = $event->overflow_event_id;
			
			//Address formatting
			$location = ($event_address != '' ? $event_address :'') . ($event_address2 != '' ? '<br />' . $event_address2 :'') . ($event_city != '' ? '<br />' . $event_city :'') . ($event_state != '' ? ', ' . $event_state :'') . ($event_zip != '' ? '<br />' . $event_zip :'') . ($event_country != '' ? '<br />' . $event_country :'');
			
			//Google map link creation
			$google_map_link = espresso_google_map_link(array( 'address'=>$event_address, 'city'=>$event_city, 'state'=>$event_state, 'zip'=>$event_zip, 'country'=>$event_country, 'text'=> 'Map and Directions', 'type'=> 'text') );
			
			//These variables can be used with other the espresso_countdown, espresso_countup, and espresso_duration functions and/or any javascript based functions.
			$start_timestamp = espresso_event_time($event_id, 'start_timestamp', get_option('time_format'));
			$end_timestamp = espresso_event_time($event_id, 'end_timestamp', get_option('time_format'));
			
			//This can be used in place of the registration link if you are usign the external URL feature
			$registration_url = $externalURL != '' ? $externalURL : get_option('siteurl') . '/?page_id='.$event_page_id.'&regevent_action=register&event_id='. $event_id;
		
			if (!is_user_logged_in() && get_option('events_members_active') == 'true' && $member_only == 'Y') {
				//Display a message if the user is not logged in.
				 //_e('Member Only Event. Please ','event_espresso') . event_espresso_user_login_link() . '.';
			}else{
	//Serve up the event list
	//As of version 3.0.17 the event lsit details have been moved to event_list_display.php
				echo $before = $before == ''? '' : $before;
				include('templates/'. $template_file);
				echo $after = $after == ''? '' : $after;
			} 
		}
	//Check to see how many database queries were performed
	//echo '<p>Database Queries: ' . get_num_queries() .'</p>';
	}
}

/*
Function Name: CTLT Display Event Espresso Search
Author: Julien Law
Contact: julienlaw@alumni.ubc.ca
Website:
Description: Creates an autocomplete search tool using Twitter Bootstrap
Requirements: A theme that uses Twitter Bootstrap and the custom files addon
*/
function ctlt_display_event_espresso_search( $url ) {
    global $wpdb, $espresso_manager, $current_user, $org_options;
    ?>
	<div id="espresso-search-form-dv" class="ui-widget">
		<form name="form" method="post" action="<?php echo $url ?>">
			<input id="ee_autocomplete" name="ee_name" placeholder="Search Events Here..." />
			<input id="ee_search_submit" name="ee_search_submit" class="btn" type="submit" value="Search" />
			<input id="event_id" name="event_id" type="hidden">
		</form>
	</div>
	<?php 
	$ee_autocomplete_params = array();
	$SQL = "SELECT e.*";
	$SQL .= ", c.category_name cat_name, c.category_desc cat_desc";
	if ( isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ) {
		$SQL .= ", v.city venue_city, v.state venue_state, v.name venue_name, v.address venue_address, v.city venue_city, v.state venue_state, v.zip venue_zip, v.country venue_country, v.meta venue_meta ";		
	}
	$SQL .= " FROM " . EVENTS_DETAIL_TABLE . " e ";
	$SQL .= " LEFT JOIN " . EVENTS_CATEGORY_REL_TABLE . " cr ON cr.event_id = e.id LEFT JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = cr.cat_id";
	if ( isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ) {
		$SQL .= " LEFT JOIN " . EVENTS_VENUE_REL_TABLE . " vr ON vr.event_id = e.id LEFT JOIN " . EVENTS_VENUE_TABLE . " v ON v.id = vr.venue_id ";
	}

	$SQL .= " WHERE e.is_active = 'Y' ";
	$SQL .= " AND e.event_status != 'D' ";
	//echo '<p>$sql = '.$sql.'</p>';							
	$events = $wpdb->get_results($SQL);
	$num_rows = $wpdb->num_rows;
								
	if ($num_rows > 0) {
		foreach ($events as $event){
			$venue_city = !empty($event->venue_city) ? stripslashes_deep($event->venue_city)  : '';
			$venue_state = !empty($event->venue_state) ?  (!empty($event->venue_city) ? ', ' : '') .stripslashes_deep($event->venue_state)  : '';

			$venue_name = !empty($event->venue_name) ?' @' . stripslashes_deep($event->venue_name)  . ' - ' . $venue_city . $venue_state . ''  : '';
			$cat_name = !empty($event->cat_name) ? ' - category: ' . stripslashes_deep($event->cat_name) . '' : '';
			//An Array of Objects with label and value properties:
			$ee_autocomplete_params[] = array( 
							'url' => espresso_reg_url($event->id), 
							'value' => stripslashes_deep($event->event_name) . $venue_name . $cat_name, 
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
}
/*
Function Name: Espresso Include JS for Templates
Author: Julien Law
Contact: julienlaw@alumni.ubc.ca
Website:
Description: Just a simple funciton to enqueue scripts for custom templates
Requirements: Needs the custom files addon
*/
function espresso_include_js_for_templates() {

	/*wp_register_script( 'reCopy', ( EVENT_ESPRESSO_PLUGINFULLURL . "scripts/reCopy.js"), false, '1.1.0' );
	wp_print_scripts( 'reCopy' );

	wp_register_script( 'jquery.validate.js', ( EVENT_ESPRESSO_PLUGINFULLURL . "scripts/jquery.validate.min.js" ), false, '1.8.1' );
	wp_print_scripts( 'jquery.validate.js' );

	wp_register_script( 'validation', ( EVENT_ESPRESSO_PLUGINFULLURL . "scripts/validation.js" ), false, EVENT_ESPRESSO_VERSION );
	wp_print_scripts( 'validation' );*/

	wp_register_script( 'ee_pagination_plugin', ( EVENT_ESPRESSO_PLUGINFULLURL . "scripts/jquery.pajinate.min.js" ), false, EVENT_ESPRESSO_VERSION );
	wp_enqueue_script( 'ee_pagination_plugin' );

	/*wp_register_script( 'ee_pagination', ( EVENT_ESPRESSO_PLUGINFULLURL . "scripts/pagination.js" ), false, EVENT_ESPRESSO_VERSION );
	$data = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'ee_pagination', 'ee_pagination', $data );
	wp_print_scripts( 'ee_pagination' );*/
	// registering scripts and styles here for sorting the table
	wp_register_script( 'ctlt_event_espresso_sort_table', trailingslashit( EVENT_ESPRESSO_UPLOAD_URL ) . 'templates/js/espresso_sort_table.js', array('jquery'), '1.0', true );
	wp_register_script( 'ctlt_table_sorter_library', trailingslashit( EVENT_ESPRESSO_UPLOAD_URL ) . 'templates/js/jquery.tablesorter.js', array('jquery'), '1.0', true );
	wp_register_style( 'ctlt_table_sorter_style', trailingslashit( EVENT_ESPRESSO_UPLOAD_URL ) . 'templates/css/ctlt_event_espresso_list.css' );
	// add the above scripts and styles to the table so that the jQuery sorting function can be used
	wp_enqueue_script( 'ctlt_event_espresso_sort_table' );
	wp_enqueue_script( 'ctlt_table_sorter_library' );
	wp_enqueue_style( 'ctlt_table_sorter_style' );
}

/*
Function Name: CTLT Profile Fields Hiding
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Hides profile fields in Profile page for WordPress users
Requirements: none
*/
function ctlt_display_event_materials_list() {
    
    global $wpdb;
    $sql = "SELECT * FROM (SELECT start_date, event_name, do_not_handout, MAX(CASE WHEN meta_key = '_wp_attached_file' THEN meta_value END) as 'attachment_url' FROM (SELECT event_name, event_id, start_date, MAX(CASE WHEN meta_key = '_ctlt_espresso_handouts_upload' THEN meta_value END) as 'attachment_key', MAX(CASE WHEN meta_key = '_ctlt_espresso_do_not_handout' THEN meta_value END) as 'do_not_handout' FROM (SELECT id, event_name, start_date FROM " . EVENTS_DETAIL_TABLE . ") AS first_results INNER JOIN " . CTLT_ESPRESSO_EVENTS_META . " ON " . CTLT_ESPRESSO_EVENTS_META . ".event_id = first_results.id GROUP BY event_id) AS second_results INNER JOIN " . $wpdb->prefix . "postmeta ON " . $wpdb->prefix . "postmeta.post_id = second_results.attachment_key GROUP BY post_id ORDER BY start_date) as third_results WHERE do_not_handout != 'yes' OR do_not_handout IS NULL";
    $events = $wpdb->get_results($sql);
    
    return $events;
}

/*
Function Name: CTLT Profile Fields Hiding
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Hides profile fields in Profile page for WordPress users
Requirements: none
*/
function remove_profile_fields( $hook ) {
    ?>
    <style type="text/css">
        form#your-profile h3,
        form#your-profile p+h3+table,
        form#your-profile p+h3+table+h3+table+h3+table+h3+table+h3+a+table,
        form#your-profile label[for=url],
        form#your-profile #url { display:none!important;visibility:hidden!important; }
        form#your-profile table { margin: 0; }
    </style>
    <?php
}
add_action( 'admin_print_styles-profile.php', 'remove_profile_fields' );
add_action( 'admin_print_styles-user-edit.php', 'remove_profile_fields' );

/*
Function Name: CTLT Modify Contact Methods
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Adds contact methods to Profile page for WordPress users
Requirements: none
*/
function modify_contact_methods($profile_fields) {

	// Add new fields
    $profile_fields['event_espresso_phone_number'] = 'Phone Number <span class="description">(required)</span>';
	$profile_fields['event_espresso_organization'] = 'Institution <span class="description">(required)</span>';
	$profile_fields['event_espresso_faculty'] = 'Faculty <span class="description">(required)</span>';
	$profile_fields['event_espresso_department'] = 'Department <span class="description">(required)</span>';
    
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);

    
	return $profile_fields;
}
add_filter('user_contactmethods', 'modify_contact_methods');