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
wp_register_script( 'ctlt_event_espresso_sort_table', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/js/espresso_sort_table.js', array('jquery'), '1.0', true );
wp_register_script( 'ctlt_table_sorter_library', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/js/jquery.tablesorter.js', array('jquery'), '1.0', true );
wp_register_style( 'ctlt_table_sorter_style', trailingslashit(EVENT_ESPRESSO_UPLOAD_URL) . 'templates/css/ctlt_event_espresso_list.css' );
// add the above scripts and styles to the table so that the jQuery sorting function can be used
wp_enqueue_script( 'ctlt_event_espresso_sort_table' );
wp_enqueue_script( 'ctlt_table_sorter_library' );
wp_enqueue_style( 'ctlt_table_sorter_style' );

function ctlt_display_event_espresso_category(){
    global $wpdb;
    $sql = "SELECT e.id, d.start_date, d.category_name, d.category_desc
            FROM " . EVENTS_DETAIL_TABLE . " e
            INNER JOIN
            (
                SELECT min(e.start_date) Start_date, c.category_name, c.category_desc
                FROM " . EVENTS_DETAIL_TABLE . " e
                INNER JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
                INNER JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
                GROUP BY c.category_name, c.category_desc
)  d ON e.start_date = d.Start_date
WHERE e.is_active = 'Y'
ORDER BY date(e.start_date)";

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
    <table id="ctlt-jQuery-event-espresso-sort-table" class="table table-bordered table-hover table-condensed tablesorter">
        <thead>
            <tr>
                <th class="header headerSortDown">Date</th>
                <th class="header">Series</th>
            </tr>
        </thead>
        <tbody>
<?php
        foreach ($events as $event){
            $event_id = $event->id;
            $event_date = $event->start_date;
            $cat_name = $event->category_name;
            $cat_desc = $event->category_desc;
            echo '<tr>';
                echo '<td>' . event_date_display( $event_date, get_option( 'date_format' ) ) . '</td>';
                echo '<td><a>' . $cat_name . '</a><br/>' . $cat_desc . '</td>';
            echo '</tr>';
        }
?>
        </tbody>
    </table>
<?php
}

