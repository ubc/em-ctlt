<?php

/*
Template Name: Past Event List
Author: Julien Law
Version: 0.16
Website:
Description: This is a template file for displaying a list of past events
Shortcode Usage: [CTLT_PAST_EVENT_LIST events_per_page=""] events_per_page is an optional parameter where you input the number of events you wish to display per page
Requirements: past_event_list_display.php, past_event_list.php, custom_shortcodes.php, custom_includes.php, custom_functions.php
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account
*/

ctlt_past_event_enqueue_pagination();
add_action( 'wp_ajax_past_events_pagination', 'ctlt_past_event_pagination' );			// ajax functions for pagination
add_action( 'wp_ajax_nopriv_past_events_pagination', 'ctlt_past_event_pagination' );

function ctlt_display_past_events( $attributes ) {

	global $wpdb, $org_options, $events_in_session;

	$template_name = ( 'past_event_list_display.php' );
	$path = locate_template( $template_name );

	$event_page_id = $org_options['event_page_id'];
	$currency_symbol = isset($org_options['currency_symbol']) ? $org_options['currency_symbol'] : '';
	$ee_search = isset($_REQUEST['ee_search']) && $_REQUEST['ee_search'] == 'true' && isset($_REQUEST['ee_name']) && !empty($_REQUEST['ee_name']) ? true : false;
	$ee_search_string = isset($_REQUEST['ee_name']) && !empty($_REQUEST['ee_name']) ? sanitize_text_field( $_REQUEST['ee_name'] ) : '';

	$default_attributes = array(
		'current_page'				=> 1,
		'events_per_page'			=> 5,
		'num_page_links_to_display'	=> 10,
		'use_wrapper'				=> true
	);
	// loop thru default atts
	foreach ($default_attributes as $key => $default_attribute) {
		// check if att exists
		if (!isset($attributes[$key])) {
			$attributes[$key] = $default_attribute;
		}
	}

	// Extract shortcode attributes
	extract( $attributes );

	// Create the query
	$sql = "SELECT e.*, ese.start_time, ese.end_time, p.event_cost ";

	// Venue sql
	isset( $org_options['use_venue_manager'] ) && $org_options['use_venue_manager'] == 'Y' ? $sql .= ", v.name venue_name, v.address venue_address, v.city venue_city, v.state venue_state, v.zip venue_zip, v.country venue_country, v.meta venue_meta " : '';

	// Staff sql
	isset( $org_options['use_personnel_manager'] ) && $org_options['use_personnel_manager'] == 'Y' ? $sql .= ", st.name staff_name " : '';

	$sql .=	"FROM " . EVENTS_DETAIL_TABLE . " e ";
	
	// Venue sql
	isset( $org_options['use_venue_manager'] ) && $org_options['use_venue_manager'] == 'Y' ? $sql .= " LEFT JOIN " . EVENTS_VENUE_REL_TABLE . " vr ON vr.event_id = e.id LEFT JOIN " . EVENTS_VENUE_TABLE . " v ON v.id = vr.venue_id " : '';

	// Staff sql
	isset( $org_options['use_personnel_manager'] ) && $org_options['use_personnel_manager'] == 'Y' ? $sql .= " LEFT JOIN " . EVENTS_PERSONNEL_REL_TABLE . " str ON str.event_id = e.id LEFT JOIN " . EVENTS_PERSONNEL_TABLE . " st ON st.id = str.person_id " : '';
	
	$sql .= " LEFT JOIN " . EVENTS_START_END_TABLE . " ese ON ese.event_id = e.id ";
	$sql .= " LEFT JOIN " . EVENTS_PRICES_TABLE . " p ON p.event_id = e.id ";
	$sql .= " WHERE is_active = 'Y' ";

	// Staff sql
	$sql .= ( $staff_id !== NULL && !empty( $staff_id ) ) ? " AND st.id = '" . $staff_id . "' ": '';

	// User sql
	$sql .= ( isset( $user_id ) && !empty( $user_id ) ) ? " AND wp_user = '" . $user_id . "' " : '';

	$sql .= " AND e.event_status != 'S' "; // do not show waitlist (secondary) events
	$sql .= " AND e.event_status != 'D' "; // do not show deleted events
	$sql .= " AND (e.end_date <= '" . date('Y-m-d') . "') ";

	$sql .= " GROUP BY e.id ";
	$sql .= " ORDER BY date(e.end_date), id ASC ";

	$events = $wpdb->get_results( $sql );

	$category_id = isset($wpdb->last_result[0]->id) ? $wpdb->last_result[0]->id : '';
	$category_name = isset($wpdb->last_result[0]->category_name) ? $wpdb->last_result[0]->category_name : '';
	$category_identifier = isset($wpdb->last_result[0]->category_identifier) ? $wpdb->last_result[0]->category_identifier : '';
	$category_desc = isset($wpdb->last_result[0]->category_desc) ? html_entity_decode(wpautop($wpdb->last_result[0]->category_desc)) : '';
	$display_desc = isset($wpdb->last_result[0]->display_desc) ? $wpdb->last_result[0]->display_desc : '';

	// var_dump( $events );
	// var_dump( $events_per_page );
	$total_events = count($events);
	$total_pages = ceil($total_events/$events_per_page);
	
	$offset = ($current_page-1)*$events_per_page;
	$events = array_slice($events,$offset,$events_per_page);

	// Debug
	// var_dump( $events );

	if ( $use_wrapper ) {
		echo "<div id='event_wrapper'>";
	}
	$page_link_ar = array();
	foreach($attributes as $key=>$attribute) {
		if ( !in_array($key,array('current_page','use_wrapper')) ) {
			$page_link_ar[] = "$key=".urlencode($attribute);
		}
	}
	$page_link = implode('&',$page_link_ar);
	//var_dump( $page_link );
	echo "<div id='event_pagination_wrapper' style='display:none;' data='" . $page_link . "'></div>";
	//css_class='$css_class' allow_override='$allow_override' events_per_page='$events_per_page' num_page_links_to_display='$num_page_links_to_display'></div>";
	echo "<div id='event_container_pagination' >";
	if ( $total_pages > 1 ) {
		
		$mid = ceil($num_page_links_to_display/2);
		
		if ( $num_page_links_to_display%2 == 0) {
			$back = $mid;
		} else {
			$back = $mid -1;
		}			
		$start = $current_page - $back;
		if ( $start < 1 ) {
			$start = 1;
		}
		$end = $start+$num_page_links_to_display;
		if ( $end > $total_pages) {
			$end = $total_pages;
		}
		
		$prev = $current_page - 1;
		$prev_no_more = '';
		if ( $prev < 1 ) {
			$prev = 1;
			$prev_no_more = 'no_more';
		}
		
		$next = $current_page + 1;
		$next_no_more = '';
		if ( $next > $total_pages) {
			$next = $total_pages;
			$next_no_more = 'no_more';
		}
		
		$espresso_paginate = '<div class="pagination pagination-centered">';
		$espresso_paginate .= '<ul>';
		$espresso_paginate .= '<li><a href="#" current_page=1 class="event_paginate ' . $prev_no_more . '">&laquo;</a></li>';
		$espresso_paginate .= '<li><a href="#" current_page=' . $prev . ' class="event_paginate ' . $prev_no_more . '">&lt;</a></li>';
		if ( $start > 1 ) {
			$espresso_paginate .= '<span class="ellipse less">...</span>';
		}
		for ( $i = $start; $i <= $end; $i++ ) {
			$start_bold = '';
			$end_bold = '';
			if ( $i == $current_page ) {
				$start_bold = '<b>';
				$end_bold 	= '</b>';
			}
			$espresso_paginate .= '<li><a class="page_link event_paginate ' . $active_page . '" current_page=' . $i . ' href="#">' . $start_bold . $i . $end_bold . '</a></li>';
		}
		if ( $end < $total_pages ) {
			$espresso_paginate .= '<span class="ellipse more">...</span>';
		}
		$espresso_paginate .= '<li><a href="#" current_page=' . $next . ' class="event_paginate ' . $next_no_more . '">&gt;</a></li>';
		$espresso_paginate .= '<li><a href="#" current_page=' . $total_pages . ' class="event_paginate ' . $next_no_more . '">&raquo;</a></li>';
		$espresso_paginate .= '</ul>';
		$espresso_paginate .= '</div>';
	}

	echo "<div id='event_content' class='event_content'>";
	if( count( $events ) < 1 ) {
		// echo $sql;
		echo __( 'No events available...', 'event_espresso' );
	}
	foreach( $events as $event ) {
		$event_id = $event->id;
		$event_name = $event->event_name;
		$event_desc = stripslashes_deep( $event->event_desc );
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
		$event_desc = array_shift(explode('<!--more-->', $event_desc));
		global $event_meta;
		$event_meta = unserialize($event->event_meta);
		$event_meta['is_active'] = $event->is_active;
		$event_meta['event_status'] = $event->event_status;
		$event_meta['start_time'] = empty($event->start_time) ? '' : $event->start_time;
		$event_meta['start_date'] = $event->start_date;
		$event_meta['registration_start'] = $event->registration_start;
		$event_meta['registration_startT'] = $event->registration_startT;
		$event_meta['registration_end'] = $event->registration_end;
		$event_meta['registration_endT'] = $event->registration_endT;

		// Venue information
		if( $org_options['use_venue_manager'] == 'Y' ) {
			$event_address = empty($event->venue_address) ? '' : $event->venue_address;
			$event_address2 = empty($event->venue_address2) ? '' : $event->venue_address2;
			$event_city = empty($event->venue_city) ? '' : $event->venue_city;
			$event_state = empty($event->venue_state) ? '' : $event->venue_state;
			$event_zip = empty($event->venue_zip) ? '' : $event->venue_zip;
			$event_country = empty($event->venue_country) ? '' : $event->venue_country;

			//Leaving these variables intact, just in case people want to use them
			$venue_title = empty($event->venue_name) ? '' : $event->venue_name;
			$venue_address = $event_address;
			$venue_address2 = $event_address2;
			$venue_city = $event_city;
			$venue_state = $event_state;
			$venue_zip = $event_zip;
			$venue_country = $event_country;
			global $venue_meta;
			$add_venue_meta = array(
				'venue_title' => $venue_title,
				'venue_address' => $event_address,
				'venue_address2' => $event_address2,
				'venue_city' => $event_city,
				'venue_state' => $event_state,
				'venue_country' => $event_country,
			);
			$venue_meta = ( !empty( $event->venue_meta ) && !empty( $add_venue_meta ) ) ? array_merge( unserialize( $event->venue_meta ), $add_venue_meta ) : '';
			// print_r( $venue_meta );
		}

		// Address formatting
		$location = ( !empty( $event_address ) ? $event_address : '' ) . ( !empty( $event_address2 ) ? '<br />' . $event_address2 : '' ) . ( !empty( $event_city ) ? '<br />' . $event_city : '' ) . ( !empty( $event_state ) ? ', ' . $event_state : '' ) . ( !empty( $event_zip ) ? '<br />' . $event_zip : '' ) . ( !empty( $event_country ) ? '<br />' . $event_country : '' );

		// Google map link creation
		$google_map_link = espresso_google_map_link(array('address' => $event_address, 'city' => $event_city, 'state' => $event_state, 'zip' => $event_zip, 'country' => $event_country, 'text' => 'Map and Directions', 'type' => 'text'));
		global $all_meta;
		$all_meta = array(
			'event_id' => $event_id,
			'event_name' => stripslashes_deep($event_name),
			'event_desc' => stripslashes_deep($event_desc),
			'event_address' => $event_address,
			'event_address2' => $event_address2,
			'event_city' => $event_city,
			'event_state' => $event_state,
			'event_zip' => $event_zip,
			'event_country' => $venue_country,
			'venue_title' => $venue_title,
            'venue_address' => $venue_address,
            'venue_address2' => $venue_address2,
            'venue_city' => $venue_city,
            'venue_state' => $venue_state,
            'venue_country' => $venue_country,
			'location' => $location,
			'is_active' => $event->is_active,
			'event_status' => $event->event_status,
			'contact_email' => empty($event->alt_email) ? $org_options['contact_email'] : $event->alt_email,
			'start_time' => empty($event->start_time) ? '' : $event->start_time,
			'registration_startT' => $event->registration_startT,
			'registration_start' => $registration_start,
			'registration_endT' => $event->registration_endT,
			'registration_end' => $registration_end,
			'is_active' => empty($is_active) ? '' : $is_active,
			'event_country' => $event_country,
			'start_date' => event_date_display($start_date, get_option('date_format')),
			'end_date' => event_date_display($end_date, get_option('date_format')),
			'time' => empty($event->start_time) ? '' : $event->start_time,
			'start_time' => empty($event->start_time) ? '' : $event->start_time,
			'end_time' => empty($event->end_time) ? '' : $event->end_time,
			'google_map_link' => $google_map_link,
			'price' => empty($event->event_cost) ? '' : $event->event_cost,
			'event_cost' => empty($event->event_cost) ? '' : $event->event_cost,
		);
		//Debug
		//echo '<p>'.print_r($all_meta).'</p>';
		//These variables can be used with other the espresso_countdown, espresso_countup, and espresso_duration functions and/or any javascript based functions.
		//Warning: May cause additional database queries an should only be used for sites with a small amount of events.
		// $start_timestamp = espresso_event_time($event_id, 'start_timestamp');
		//$end_timestamp = espresso_event_time($event_id, 'end_timestamp');

		// This can be used in place of the registration link if you are using the external URL feature
		$registration_url = $externalURL != '' ? $externalURL : espresso_reg_url($event_id);
		// Serve up the event list

		// Uncomment to show active status array
		// print_r( event_espresso_get_is_active( $event_id ) );
		if( empty( $path ) ) {
			include( $template_name );
		}
		else {
			include( $path );
		}

	} // end foreach
	echo "</div>";
	echo "</div>";
	if( isset( $espresso_paginate ) ) {
		echo $espresso_paginate; // output the pagination links
	}
	if( $use_wrapper ) {
		echo "</div>";
	}
	// Check to see how many database queries were performed
	// echo '<p>Database Queries: ' . get_num_queries() . '</p>';
	espresso_registration_footer();
}

function ctlt_past_event_pagination() {
	$filename = ( 'past_event_list.php' );
	$filename_path = locate_template( $filename );
	event_espresso_require_template( $filename );
	$_REQUEST['use_wrapper'] = false;
	ctlt_display_past_events( $_REQUEST );
	die(); 
}

function ctlt_past_event_enqueue_pagination() {
	wp_register_script( 'ctlt_past_event_ajax_pagination', trailingslashit( EVENT_ESPRESSO_UPLOAD_URL ) . 'templates/js/ajax-paging.js', array('jquery'), '1.0', true );
	$data = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );
	wp_localize_script( 'ctlt_past_event_ajax_pagination', 'ctlt_past_event_ajax_pagination', $data );
	wp_print_scripts( 'ctlt_past_event_ajax_pagination' );
}