<?php
/*
Template Name: Category Display for Events
Author: Julien law
Version: 0.16
Website:
Description: This is a template file for displaying a list of categories.
Shortcode Usage:   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="current"] to display upcoming events
                   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="past"] to display past events
Requirements: ctlt_espresso_category_registration.php, ctlt_espresso_category_registration_display.php, custom_shortcodes.php, custom_includes.php, custom_functions.php
Notes: This file should be stored in your "/wp-content/uploads/espresso/templates/" folder and you should have downloaded the custom files addon from your event espresso account page
*/

/**
 * ctlt_display_event_espresso_category function
 * This function generates the SQL query statement and determines if the shortcode parameter is for past or current events
 */ 
function ctlt_display_event_espresso_category($event_type){
    global $wpdb;

    // if $event_type is identical to 'past' then assign '<' to $conditional otherwise '>'
    $conditional = ( strtolower( $event_type ) === 'past' ) ? '<' : '>=';
    $date = date( "Y-m-d", time() );
    $time = date( "H:i", time() );
    $sql = "SELECT e.id, min(e.start_date) AS start_date, IFNULL(c.category_name, 'Uncategorized') AS category_name, c.category_desc, IFNULL(c.id, 0) AS cat_id, ese.start_time FROM " . EVENTS_DETAIL_TABLE . " e 
            LEFT JOIN " . EVENTS_START_END_TABLE . " ese ON ese.event_id = e.id
            LEFT JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
            LEFT JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id 
            WHERE e.is_active = 'Y' ";
    $sql .= "AND (e.end_date " . $conditional . "'" . $date . "' ";
    $sql .= "OR (e.end_date = '" . $date ."' AND ese.end_time " . $conditional . "'" . $time . "' )) ";
    $sql .= "AND e.event_status != 'S' ";
    $sql .= "AND e.event_status != 'D' ";
    $sql .= "GROUP BY c.category_name, c.category_desc, c.id ";
    $sql .= "ORDER BY start_date, ese.start_time";

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
    $num_rows = count($events);
    //var_dump($events);
    $category_names = $wpdb->category_name;
    $category_descs = $wpdb->category_desc;
    
    //print( $sql );

    if ($num_rows > 0) {
        foreach ($events as $event) {
            $event_id = $event->id;
            $event_date = $event->start_date;
            $cat_id = $event->cat_id;
            $cat_name = $event->category_name;
            $cat_desc = $event->category_desc;
            $url = ctlt_espresso_category_url( $cat_id, $cat_name );
            $description = ( $cat_id != 0 ) ? espresso_format_content( $cat_desc ) : espresso_format_content( "The events in here do not belong to any particular series." );
            ?>
            <div class="row-fluid">
                <div class="span12 ctlt-espresso-display-event">
                    <div class="media ctlt-espresso-display-padding">
                        <a class="pull-left" href="<?php echo $url; ?>">
                           <?php echo apply_filters( 'filter_hook_espresso_display_featured_image', $event_id, !empty( $event_meta['event_thumbnail_url'] ) ? $event_meta['event_thumbnail_url'] : '' ); ?>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="<?php echo $url; ?>"><?php echo $cat_name; ?></a>
                            </h4>
                            <p>
                                Next event: <?php echo event_date_display( $event_date, get_option( 'date_format' ) ) ?>
                                <br />
                                <div class="event-desc">
                                    <?php echo $description; ?>
                                </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
    }
    else {
        echo __('No events available...', 'event_espresso');
    }
}

/**
 * ctlt_espresso_category_url function
 * This function generates the URL for the event category
 */
function ctlt_espresso_category_url( $cat_id = 0, $cat_name = NULL ) {
    global $org_options;
    if ( $cat_id >= 0 && !empty($cat_name) ) {
        //return espresso_getTinyUrl(home_url().'/?page_id='.$org_options['event_page_id'].'&regevent_action=register&event_id='.$event_id);
        $new_url = add_query_arg('category_id', $cat_id, get_permalink() );
        $new_url = add_query_arg('category_name', $cat_name, $new_url );
        return $new_url;
    }/* else {
      echo 'No event id supplied'; */
    return;
    //}
}
