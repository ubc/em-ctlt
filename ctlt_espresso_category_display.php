<?php
/*
Template Name: Category Display for Events
Author: Julien law
Version: 0.8
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
    /*$sql = "SELECT e.id, d.start_date, d.category_name, d.category_desc, d.cat_id
            FROM " . EVENTS_DETAIL_TABLE . " e
            INNER JOIN
            (
                SELECT min(e.start_date) Start_date, c.category_name, c.category_desc, c.id AS cat_id
                FROM " . EVENTS_DETAIL_TABLE . " e
                JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
                JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
                WHERE e.is_active = 'Y'
                GROUP BY c.category_name, c.category_desc, c.id
            )  d ON e.start_date = d.Start_date
            WHERE e.is_active = 'Y'
            ORDER BY date(e.start_date)";*/
    $sql = "SELECT e.id, min(e.start_date) AS start_date, c.category_name, c.category_desc, c.id AS cat_id
            FROM " . EVENTS_DETAIL_TABLE . " e
            JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
            JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
            WHERE e.is_active = 'Y'
            GROUP BY c.category_name, c.category_desc, c.id";

    ctlt_event_espresso_get_category_list_table($sql);
}

//Events Custom Table Listing - Shows the events on your page in matching table.
function ctlt_event_espresso_get_category_list_table($sql){
    event_espresso_session_start();
    if(!isset($_SESSION['event_espresso_sessionid'])){
        $sessionid = (mt_rand(100,999).time());
        $_SESSION['event_espresso_sessionid'] = $sessionid;
    }
    global $wpdb;
    $events = $wpdb->get_results($sql);
    $category_names = $wpdb->category_name;
    $category_descs = $wpdb->category_desc;

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
        foreach ($events as $event) {
            $event_id = $event->id;
            $event_date = $event->start_date;
            $cat_id = $event->cat_id;
            $cat_name = $event->category_name;
            $cat_desc = $event->category_desc;
            $url = ctlt_espresso_category_url( $cat_id, $cat_name );
            ?>
            <tr>
                <td><?php echo event_date_display( $event_date, get_option( 'date_format' ) );?></td>
                <td><a href="<?php echo $url ?>"><?php echo $cat_name; ?></a><br/><?php echo $cat_desc; ?></td>
            </tr>
        <?php }
?>
        </tbody>
    </table>
<?php
}

function ctlt_espresso_category_url( $cat_id = 0, $cat_name = NULL ) {
    global $org_options;
    if ( $cat_id > 0 && !empty($cat_name) ) {
        //return espresso_getTinyUrl(home_url().'/?page_id='.$org_options['event_page_id'].'&regevent_action=register&event_id='.$event_id);
        $new_url = add_query_arg('category_id', $cat_id, get_permalink() );
        $new_url = add_query_arg('category_name', $cat_name, $new_url );
        return $new_url;
    }/* else {
      echo 'No event id supplied'; */
    return;
    //}
}
