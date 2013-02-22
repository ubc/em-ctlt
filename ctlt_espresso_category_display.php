<?php
/*
Template Name: Category Display for Events
Author: Julien law
Contact:
Website:
Description: This is a template file for displaying a list of categories.
Shortcode: [CTLT_ESPRESSO_CATEGORY_DISPLAY]
Requirements:
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account page
*/
function ctlt_display_event_espresso_category(){
    global $wpdb;
    $sql = "SELECT e.*, c.category_name, c.category_desc, ese.start_time FROM " . EVENTS_DETAIL_TABLE . " e
    JOIN " . EVENTS_START_END_TABLE . " ese ON ese.event_id = e.id
    JOIN " . EVENTS_CATERORY_REL_TABLE . " r ON r.event_id = e.id
    JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
    WHERE e.is_active = 'Y'
    ORDER BY ese.start_time";

    ctlt_event_espresso_get_category_list_table($sql);
}

//Events Custom Table Listing - Shows the events on your page in matching table.
function ctlt_event_espresso_get_category_list_table($sql){
    event_espresso_session_start();
    if(!isset($_SESSION['event_espresso_sessionid'])){
        $sessionid = (mt_rand(100,999).time());
        $_SESSION['event_espresso_sessionid'] = $sessionid;
    }
    echo $_SESSION['event_espresso_sessionid'];
}
