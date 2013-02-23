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

// registering scripts and styles here for sorting the table
wp_register_script( 'ctlt_event_espresso_sort_table', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/js/espresso_sort_table.js', array('jquery')

function ctlt_display_event_espresso_category(){
    global $wpdb;
    $sql = "SELECT c.category_name, c.category_desc FROM "
    . EVENTS_CATEGORY_TABLE . " c ";

    ctlt_event_espresso_get_category_list_table($sql);
}

//Events Custom Table Listing - Shows the events on your page in matching table.
function ctlt_event_espresso_get_category_list_table($sql){
    event_espresso_session_start();
    if(!isset($_SESSION['event_espresso_sessionid'])){
        $sessionid = (mt_rand(100,999).time());
        $_SESSION['event_espresso_sessionid'] = $sessionid;
    }
    //echo $_SESSION['event_espresso_sessionid'];
    global $wpdb;
    //echo 'This page is located in ' . EVENT_ESPRESSO_TEMPLATE_DIR;
    $events = $wpdb->get_results($sql);
    $category_names = $wpdb->category_name;
    $category_descs = $wpdb->category_desc;
    //$display_desc = $wpdb->last_result[0]->display_desc;

    // if($display_desc == 'Y'){
       // echo '<p>' . htmlspecialchars_decode($category_name) . '</p>';
       // echo '<p>' . htmlspecialchars_decode($category_desc) . '</p>';
    // }
?>
    <table class="table table-bordered table-hover table-condensed">
        <thead>
            <tr>
                <th class="header headerSortDown">Series</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($events as $event){
            echo '<tr>';
                echo '<td>' . $event->category_name . '</td>';
                echo '<td>' . $event->category_desc . '</td>';
            echo '</tr>';
        }
?>
        </tbody>
    </table>
<?php
}
