<?php

//This is a template file for displaying a list of events on a page. These functions are used with the [ESPRESSO_EVENTS] shortcode.
//This is an group of functions for querying all of the events in your databse.
//This file should be stored in your "/wp-content/uploads/espresso/templates/" directory.
//Note: All of these functions can be overridden using the "Custom Files" addon. The custom files addon also contains sample code to display ongoing events

if (!function_exists('display_all_events')) {
	function display_all_events() {
		event_espresso_get_event_details(array());
	}

}

if (!function_exists('display_event_espresso_categories')) {
	function display_event_espresso_categories($event_category_id=NULL, $css_class=NULL) {
		event_espresso_get_event_details(array('category_identifier' => $event_category_id, 'css_class' => $css_class));
	}
}

if (!function_exists('event_espresso_get_event_details_ajx')) {
	function event_espresso_get_event_details_ajx($attributes) {
	}
}

//Events Listing - Shows the events on your page.
if (!function_exists('event_espresso_get_event_details')) {

	function event_espresso_get_event_details( $attributes ) {

		global $wpdb, $org_options, $events_in_session;
		
		$event_page_id = $org_options['event_page_id'];
		$currency_symbol = isset($org_options['currency_symbol']) ? $org_options['currency_symbol'] : '';
		$ee_search = isset($_REQUEST['ee_search']) && $_REQUEST['ee_search'] == 'true' && isset($_REQUEST['ee_name']) && !empty($_REQUEST['ee_name']) ? true : false;
		$ee_search_string = isset($_REQUEST['ee_name']) && !empty($_REQUEST['ee_name']) ? sanitize_text_field( $_REQUEST['ee_name'] ) : '';
		
		
		//Check for Multi Event Registration
		$multi_reg = false;
			$category_name = '';
		if (function_exists('event_espresso_multi_reg_init')) {
			$multi_reg = true;
		}
		
		$default_attributes = array('category_identifier' => NULL
		 							, 'staff_id' => NULL
									, 'allow_override' => 0
									, 'show_expired' => 'false'
									, 'show_secondary' => 'false'
									, 'show_deleted' => 'false'
									, 'show_recurrence' => 'true'
									, 'limit' => '0'
									, 'order_by' => 'NULL'
									, 'sort' => 'ASC'
									, 'css_class' => 'NULL'
									, 'current_page' => 1
									, 'events_per_page' => 50
									, 'num_page_links_to_display'=>10
									, 'use_wrapper' => true);
		// loop thru default atts
		foreach ($default_attributes as $key => $default_attribute) {
			// check if att exists
			if (!isset($attributes[$key])) {
				$attributes[$key] = $default_attribute;
			}
		}
		
		// now extract shortcode attributes
		extract($attributes);
		
		//Create the query
		$DISTINCT = $ee_search == true ? "DISTINCT" : '';
		$sql = "SELECT $DISTINCT e.*, ese.start_time, ese.end_time, p.event_cost ";
		
		//Category sql
		$sql .= ($category_identifier != NULL && !empty($category_identifier))? ", c.category_name, c.category_desc, c.display_desc, c.category_identifier": '';
		
		//Venue sql
		isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ? $sql .= ", v.name venue_name, v.address venue_address, v.city venue_city, v.state venue_state, v.zip venue_zip, v.country venue_country, v.meta venue_meta " : '';
		
		//Staff sql
		isset($org_options['use_personnel_manager']) && $org_options['use_personnel_manager'] == 'Y' ? $sql .= ", st.name staff_name " : '';
		
		
		$sql .= " FROM " . EVENTS_DETAIL_TABLE . " e ";
		$sql .= ($category_identifier != NULL && !empty($category_identifier))? " JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id  JOIN " . EVENTS_CATEGORY_TABLE . " c ON  c.id = r.cat_id ":'';
		
		//Venue sql
		isset($org_options['use_venue_manager']) && $org_options['use_venue_manager'] == 'Y' ? $sql .= " LEFT JOIN " . EVENTS_VENUE_REL_TABLE . " vr ON vr.event_id = e.id LEFT JOIN " . EVENTS_VENUE_TABLE . " v ON v.id = vr.venue_id " : '';
		
		//Venue sql
		isset($org_options['use_personnel_manager']) && $org_options['use_personnel_manager'] == 'Y' ? $sql .= " LEFT JOIN " . EVENTS_PERSONNEL_REL_TABLE . " str ON str.event_id = e.id LEFT JOIN " . EVENTS_PERSONNEL_TABLE . " st ON st.id = str.person_id " : '';
		
		$sql .= " LEFT JOIN " . EVENTS_START_END_TABLE . " ese ON ese.event_id= e.id ";
		$sql .= " LEFT JOIN " . EVENTS_PRICES_TABLE . " p ON p.event_id=e.id ";
		$sql .= " WHERE is_active = 'Y' ";
		
		//Category sql
		$sql .= ($category_identifier !== NULL  && !empty($category_identifier))? " AND c.category_identifier = '" . $category_identifier . "' ": '';
		
		//Staff sql
		$sql .= ($staff_id !== NULL  && !empty($staff_id))? " AND st.id = '" . $staff_id . "' ": '';
		
		$sql .= $show_expired == 'false' ? " AND (e.start_date >= '" . date('Y-m-d') . "' OR e.event_status = 'O' OR e.registration_end >= '" . date('Y-m-d') . "') " : '';
		if  ($show_expired == 'true'){
			$allow_override = 1;
		}
		
		//If using the [ESPRESSO_VENUE_EVENTS] shortcode
		$sql .= isset($use_venue_id) && $use_venue_id == true ? " AND v.id = '".$venue_id."' " : '';
		
		$sql .= $show_secondary == 'false' ? " AND e.event_status != 'S' " : '';
		$sql .= $show_deleted == 'false' ? " AND e.event_status != 'D' " : " AND e.event_status = 'D' ";
		if  ($show_deleted == 'true'){
			$allow_override = 1;
		}
		//echo '<p>'.$order_by.'</p>';
		$sql .= $show_recurrence == 'false' ? " AND e.recurrence_id = '0' " : '';
		
		//Search query
		if ( $ee_search ){
			// search for full original string within bracketed search options
			$sql .= " AND ( e.event_name LIKE '%$ee_search_string%' ";
			// array of common words that we don't want to waste time looking for
			$words_to_strip = array( ' the ', ' a ', ' or ', ' and ' );
			$words = str_replace( $words_to_strip, ' ', $ee_search_string );
			// break words array into individual strings
			$words = explode( ' ', $words );
			// search for each word  as an OR statement
			foreach ( $words as $word ) {
				$sql .= " OR e.event_name LIKE '%$word%' ";			
			}
			// close the search options
			$sql .= " ) ";
		}
		
		$sql .= " GROUP BY e.id ";
		$sql .= $order_by != 'NULL' ? " ORDER BY " . $order_by . " ".$sort." " : " ORDER BY date(start_date), id ASC ";
		$sql .= $limit > 0 ? ' LIMIT 0, '.$limit : '';  
		
		//echo $sql;
		//echo 'This page is located in ' . get_option( 'upload_path' );
		
		$events = $wpdb->get_results( $sql );

		$category_id = isset($wpdb->last_result[0]->id) ? $wpdb->last_result[0]->id : '';
		$category_name = isset($wpdb->last_result[0]->category_name) ? $wpdb->last_result[0]->category_name : '';
		$category_identifier = isset($wpdb->last_result[0]->category_identifier) ? $wpdb->last_result[0]->category_identifier : '';
		$category_desc = isset($wpdb->last_result[0]->category_desc) ? html_entity_decode(wpautop($wpdb->last_result[0]->category_desc)) : '';
		$display_desc = isset($wpdb->last_result[0]->display_desc) ? $wpdb->last_result[0]->display_desc : '';
        
		
		$total_events = count($events);
		$total_pages = ceil($total_events/$events_per_page);
		
		$offset = ($current_page-1)*$events_per_page;
		$events = array_slice($events,$offset,$events_per_page);
		
		//Debug
		//var_dump($events);
		
		if ( $use_wrapper ) {
			echo "<div id='event_wrapper'>";
		}
		$page_link_ar = array();
		foreach($attributes as $key=>$attribute) {
			if ( !in_array($key,array('current_page','use_wrapper'))) {
				$page_link_ar[] = "$key=".urlencode($attribute);
			}
		}
		$page_link = implode('&',$page_link_ar);
		echo "<div id='event_search_code' style='display:none;' data='$page_link'></div>";
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
			
			$espresso_paginate = "<div class='page_navigation'>";
			$espresso_paginate .= "<a href='#' current_page=1 class='event_paginate $prev_no_more ui-icon ui-icon-seek-first'>&lt;&lt;</a>";
			$espresso_paginate .= "<a href='#' current_page=$prev class='event_paginate $prev_no_more ui-icon ui-icon-seek-prev'>&lt;</a>";
			if ( $start > 1) {
				$espresso_paginate .= "<span class='ellipse less'>...</span>";
			}
			for($i = $start; $i <= $end; $i++) {
				$active_page = '';
				if ( $i == $current_page) {
					$active_page = 'active_page';
				}
				$espresso_paginate .= "<a class='page_link event_paginate $active_page ' current_page=$i href='#' style='display: block; '>$i</a>";
			}
			if ( $end < $total_pages) {
				$espresso_paginate .= "<span class='ellipse more'>...</span>";
			}
			$espresso_paginate .= "<a href='#' current_page=$next class='event_paginate $next_no_more ui-icon ui-icon-seek-next'>&gt;</a>";
			$espresso_paginate .= "<a href='#' current_page=$total_pages class='event_paginate $next_no_more ui-icon ui-icon-seek-end'>&gt;&gt;</a>";
			$espresso_paginate .= "</div>";	
		}
		echo "<div id='event_content' class='event_content'>"; // begin event_content
			if ( count($events) < 1 ) {
				//echo $sql;
				echo __('No events available...', 'event_espresso');
			}
			if ( $display_desc == 'Y' ) {
				echo '<p id="events_category_name-' . $category_id . '" class="events_category_name">' . stripslashes_deep($category_name) . '</p>';
				echo espresso_format_content($category_desc);
			}
			echo '<table class="table table-bordered table-hover table-condensed">';
					echo "<tr>";
						echo "<th>Date</th><th>Title</th><th>Description</th>";
					echo "</tr>";
					for($i = 0; $i < count($events); $i++) {
						echo "<tr>";
						for($j = 1; $j <= 3; $j++) {
							echo "<td>";
								echo $j;
							echo "</td>";
						}
						echo "</tr>";
					}
			echo "</table>";
		echo "</div>";	// end event_content
		echo "</div>";
		if ( isset( $espresso_paginate ) ) {
			echo $espresso_paginate; // spit out the pagination links
		}
		if ( $use_wrapper ) {
			echo "</div>";
		}
		//Check to see how many database queries were performed
		//echo '<p>Database Queries: ' . get_num_queries() .'</p>';
		espresso_registration_footer();
	}

}