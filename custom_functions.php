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
Function Name: CTLT Display Event Materials List
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Displays a list of materials for events that choose to make them available.
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
        form#your-profile p+h3+table+h3+table+h3+table+h3+table+table+h3+a+table,
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
function modify_contact_methods($contactmethods) {

	// Add new fields
    $contactmethods['event_espresso_phone_number'] = 'Phone Number <span class="description">(required)</span>';
    
    unset($contactmethods['aim']);
    unset($contactmethods['jabber']);
    unset($contactmethods['yim']);

    
	return $contactmethods;
}
add_filter('user_contactmethods', 'modify_contact_methods');


/*
Function Name: CTLT Profile Fields
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Adds profile fields for organization/department/unit
Requirements: none
*/

add_action( 'show_user_profile', 'ctlt_profile_fields' );
add_action( 'edit_user_profile', 'ctlt_profile_fields' );

function ctlt_profile_fields( $user ) {

    $ini_url = "UserInfo.ini";
    $departments = parse_ini_file($ini_url, true);
    
    ?>

    <table class="form-table">
		<tr>
			<th><label for="event_espresso_organization">Organization <span class="description">(required)</span></label></th>
			<td>
				<input type="text" name="event_espresso_organization" id="event_espresso_organization" value="<?php echo esc_attr( get_the_author_meta( 'event_espresso_organization', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter the organization with which you are associated.</span>
			</td>
		</tr>
        <tr>
			<th><label for="event_espresso_faculty">Faculty <span class="description">(required)</span></label></th>
			<td>
                <select name="event_espresso_faculty" id="event_espresso_faculty">
                    <option value=""></option>
                    <?php
                        foreach( $departments['faculty'] as $department ) {
                            echo '<option value="' . $department . '" ';
                            if($department == esc_attr( get_the_author_meta( 'event_espresso_faculty', $user->ID ) ) ) {
                                echo 'selected="selected"';
                            }
                            echo '>' . $department . '</option>';
                        }
                    ?>
                </select>
				<span class="description">Please enter the UBC Faculty with which you are associated.</span>
			</td>
		</tr>
        <tr>
			<th><label for="event_espresso_department">Department <span class="description">(required)</span></label></th>
			<td>
				<input type="text" name="event_espresso_department" id="event_espresso_department" value="<?php echo esc_attr( get_the_author_meta( 'event_espresso_department', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter the UBC department with which you are associated.</span>
			</td>
		</tr>
        <tr>
			<th><label for="event_espresso_other_unit">Other Unit <span class="description">(if none of the above apply)</span></label></th>
			<td>
				<input type="text" name="event_espresso_other_unit" id="event_espresso_other_unit" value="<?php echo esc_attr( get_the_author_meta( 'event_espresso_other_unit', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
	</table>
<?php }

/*
Function Name: CTLT Save Contact Methods
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Saves profile fields for organization/department/unit
Requirements: none
*/

add_action( 'personal_options_update', 'ctlt_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'ctlt_save_extra_profile_fields' );

function ctlt_save_extra_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
    
	update_usermeta( $user_id, 'event_espresso_organization', $_POST['event_espresso_organization'] );
    update_usermeta( $user_id, 'event_espresso_faculty', $_POST['event_espresso_faculty'] );
    update_usermeta( $user_id, 'event_espresso_department', $_POST['event_espresso_department'] );
    update_usermeta( $user_id, 'event_espresso_other_unit', $_POST['event_espresso_other_unit'] );
}

/*
Function Name: CTLT Automatic Email Reminders
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Sends out a standardized reminder email to all registered participants a day before an event occurs
Requirements: none
*/

add_action( 'wp', 'ctlt_automatic_email_schedule' );
function ctlt_automatic_email_schedule() {
	if ( ! wp_next_scheduled( 'ctlt_daily_event_hook' ) ) {
		wp_schedule_event( time(), 'daily', 'ctlt_daily_event_hook');
	}
}

add_action( 'ctlt_daily_event_hook', 'ctlt_automatic_email_reminders' );

function ctlt_automatic_email_reminders() {
	
    global $wpdb;
    
    $date =  mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"));
    
    $sql = "SELECT third_results.id, event_name, start_date, end_date, venue_id, start_time, end_time, name, address, address2, city, state, zip, country, category_id FROM (";
    $sql .= "SELECT second_results.id, event_name, start_date, end_date, venue_id, start_time, end_time, category_id FROM (";
    $sql .= "SELECT first_results.id, event_name, start_date, end_date, venue_id, category_id FROM (";
    $sql .= "SELECT id, event_name, start_date, end_date, category_id FROM " . $wpdb->prefix . "events_detail WHERE event_status = 'A' AND start_date = '" . date('Y-m-d', $date) . "') as first_results ";
    $sql .= "LEFT JOIN " . $wpdb->prefix . "events_venue_rel ON first_results.id = " . $wpdb->prefix . "events_venue_rel.event_id) as second_results ";
    $sql .= "LEFT JOIN " . $wpdb->prefix . "events_start_end ON second_results.id = " . $wpdb->prefix . "events_start_end.event_id) as third_results ";
    $sql .= "LEFT JOIN " . $wpdb->prefix . "events_venue ON " . $wpdb->prefix . "events_venue.id = third_results.venue_id ";
    
    $daily_events = $wpdb->get_results( $sql, ARRAY_A );

    foreach( $daily_events as $daily_event ) {
    
        $daily_event_subject = "";
        $daily_event_message = "You are currently registered for " . $daily_event['event_name'] . ".\r\n\r\n The event starts tomorrow at " . $daily_event['start_time'];
        if( $daily_event['name'] != '' ) {
            $daily_event_message .= " at " . $daily_event['name'];
            if( $daily_event['address'] != '') {
                $daily_event_message .= " (" . $daily_event['address'];
                if( $daily_event['address2'] != '') {
                    $daily_event_message .= "; " . $daily_event['address2'];
                    if( $daily_event['city'] != '' && $daily_event['state'] != '' ) {
                        $daily_event_message .= "; " . $daily_event['city'] . ", " . $daily_event['state'];
                    }
                }
                $daily_event_message .= ")";
            }
        }
        $daily_event_message .= ".\r\n\r\n";
        $daily_event_message .= "If you have any questions, please contact us at " . get_option('admin_email') . ".";
        $daily_event_message .= "\r\n\r\n";
        $daily_event_message .= "Sincerely,";
        $daily_event_message .= "\r\n\r\n";
        $daily_event_message .= get_option('blogname');
        
        $daily_event_subject = $daily_event['event_name'] . " Reminder";
        
        $headers = "From: " . get_option('blogname') . " <" . get_option('admin_email')  . ">\r\n";
    
        $sql = "SELECT fname, lname, email FROM " . $wpdb->prefix . "events_attendee WHERE event_id = " . $daily_event['id'] . " AND payment_status = 'Completed'";
        $event_registrants = $wpdb->get_results( $sql, ARRAY_A );
        foreach( $event_registrants as $event_registrant ) {
            $event_registrant_name = $event_registrant['fname'];
            $event_registrant_email = $event_registrant['email'];
            $event_registrant_personalized_message = "Dear " . $event_registrant_name . ",\r\n\r\n" . $daily_event_message;
            wp_mail($event_registrant_email, $daily_event_subject, $event_registrant_personalized_message, $headers);
        }
    }
}

/*
Function Name: CTLT Automatic Waitlist Event Creation
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Automatically creates a waitlist event for events
Requirements: none
*/

add_action( 'action_hook_espresso_insert_event_success', 'ctlt_automatic_waitlist_event', 10, 1 );

function ctlt_automatic_waitlist_event($main_event) {

    if( $main_event['_ctlt_espresso_event_waitlisting'] != 'on' ) {
        global $wpdb;

        $main_event_id = $main_event['event_id'];
        $waitlist_event_id = $main_event['event_id']+1;
        $waitlist_event_name = $main_event['event'] . " Waiting List";

        require_once( ABSPATH . "/wp-content/plugins/event-espresso/includes/event-management/copy_event.php" );
        copy_event();
        
        $wpdb->update(EVENTS_DETAIL_TABLE, array( 'allow_overflow' => 'Y', 'overflow_event_id' => $waitlist_event_id ), array( 'ID' => $main_event_id ), array( '%s', '%d' ) );
        $wpdb->update(EVENTS_DETAIL_TABLE, array( 'event_status' => 'S', 'event_name' => $waitlist_event_name, 'reg_limit' => '999999' ), array( 'ID' => $waitlist_event_id ), array( '%s', '%s', '%d' ) );
    }
    
}

/*
Function Name: CTLT Automatic Waitlist Transfer for Administrative Deletion
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Automatically transfers people into events when a site administrator deletes them
Requirements: none
*/

add_action( 'action_hook_espresso_after_delete_attendee_event_list', 'ctlt_automatic_waitlist_transfer_deletion_by_admin', 10, 2 );

function ctlt_automatic_waitlist_transfer_deletion_by_admin( $attendee_id, $event_id ) {
    
    global $wpdb;
    
    $sql = "SELECT SUM(quantity) AS quantity FROM " . EVENTS_ATTENDEE_TABLE . " WHERE (payment_status='Completed' OR payment_status='Pending' OR payment_status='Refund') AND event_id = '%d'";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $event_id ) );
    
    $current_attendees = $sql_results[0]->quantity;
    
    $sql = "SELECT reg_limit, overflow_event_id FROM " . EVENTS_DETAIL_TABLE . " WHERE id = '%d'";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $event_id ) );
    
    $max_attendees = $sql_results[0]->reg_limit;
    $overflow_event_id = $sql_results[0]->overflow_event_id;
    
    $sql = "SELECT id FROM " . EVENTS_ATTENDEE_TABLE . " WHERE event_id = %d LIMIT 1";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $overflow_event_id ) );
    
    $waitlisted_attendee = $sql_results[0]->id;
    
    if( $current_attendees < $max_attendees && $waitlisted_attendee != NULL ) {
        $wpdb->update( EVENTS_ATTENDEE_TABLE, array( 'event_id' => $event_id ), array( 'ID' => $waitlisted_attendee ), array( '%d' ) );
        $wpdb->update( EVENTS_MEMBER_REL_TABLE, array( 'event_id' => $event_id ), array( 'attendee_id' => $waitlisted_attendee ), array ( '%d' ) );
    }
    
}

/*
Function Name: CTLT Automatic Waitlist Transfer for User Deletion
Author: Nathan Sidles
Contact: nsidles@gmail.com
Website:
Description: Automatically transfers people into events when they delete themselves
Requirements: events-members plugin
*/

add_action( 'action_hook_espresso_after_registration_cancellation', 'ctlt_automatic_waitlist_transfer_for_user', 10, 1 );

function ctlt_automatic_waitlist_transfer_for_user( $attendee_id ) {
    
    global $wpdb;
    
    $sql = "SELECT event_id FROM " . EVENTS_ATTENDEE_TABLE . " WHERE id = '%d'";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $attendee_id ) );
    
    $event_id = $sql_results[0]->event_id;
    
    $sql = "SELECT SUM(quantity) AS quantity FROM " . EVENTS_ATTENDEE_TABLE . " WHERE (payment_status='Completed' OR payment_status='Pending' OR payment_status='Refund') AND event_id = '%d'";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $event_id ) );
    
    $current_attendees = $sql_results[0]->quantity;
    
    $sql = "SELECT reg_limit, overflow_event_id FROM " . EVENTS_DETAIL_TABLE . " WHERE id = '%d'";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $event_id ) );
    
    $max_attendees = $sql_results[0]->reg_limit;
    $overflow_event_id = $sql_results[0]->overflow_event_id;
    
    $sql = "SELECT id FROM " . EVENTS_ATTENDEE_TABLE . " WHERE event_id = %d LIMIT 1";
    
    $sql_results = $wpdb->get_results( $wpdb->prepare( $sql, $overflow_event_id ) );
    
    $waitlisted_attendee = $sql_results[0]->id;
    
    if( $current_attendees < $max_attendees && $waitlisted_attendee != NULL ) {
        $wpdb->update( EVENTS_ATTENDEE_TABLE, array( 'event_id' => $event_id ), array( 'ID' => $waitlisted_attendee ), array( '%d' ) );
        $wpdb->update( EVENTS_MEMBER_REL_TABLE, array( 'event_id' => $event_id ), array( 'attendee_id' => $waitlisted_attendee ), array ( '%d' ) );
    }
    
}