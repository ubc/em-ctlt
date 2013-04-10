<?php
/*
Template Name: Category Display for Events
Author: Julien law
Version: 0.10
Website:
Description: This is a template file for displaying a list of categories.
Shortcode Usage:   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="current"] to display upcoming events
                   [CTLT_ESPRESSO_CATEGORY_DISPLAY event_type="past"] to display past events
Requirements: ctlt_espresso_category_registration.php, ctlt_espresso_category_registration_display.php, custom_shortcodes.php, custom_includes.php, custom_functions.php
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

function ctlt_display_event_espresso_category($event_type){
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

    // if $event_type is identical to 'past' then assign '<' to $conditional otherwise '>'
    $conditional = ( $event_type === 'past' ) ? '<' : '>';

    //var_dump($conditional); 

    $sql = "SELECT e.id, min(e.start_date) AS start_date, c.category_name, c.category_desc, c.id AS cat_id
            FROM " . EVENTS_DETAIL_TABLE . " e
            JOIN " . EVENTS_CATEGORY_REL_TABLE . " r ON r.event_id = e.id
            JOIN " . EVENTS_CATEGORY_TABLE . " c ON c.id = r.cat_id
            WHERE e.is_active = 'Y'
            AND e.end_date " . $conditional . " CURDATE()
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
    <!--<table id="ctlt-jQuery-event-espresso-sort-table" class="table table-bordered table-hover table-condensed tablesorter">
        <thead>
            <tr>
                <th class="header headerSortDown">Date</th>
                <th class="header">Series</th>
            </tr>
        </thead>
        <tbody>-->
<?php
        foreach ($events as $event) {
            $event_id = $event->id;
            $event_date = $event->start_date;
            $cat_id = $event->cat_id;
            $cat_name = $event->category_name;
            $cat_desc = $event->category_desc;
            $url = ctlt_espresso_category_url( $cat_id, $cat_name );

            ?>
            <!--<tr>
                <td><?php echo event_date_display( $event_date, get_option( 'date_format' ) );?></td>
                <td><a href="<?php echo $url ?>"><?php echo $cat_name; ?></a><br/><?php echo $cat_desc; ?></td>
            </tr>-->
            <div class="row-fluid">
                <div class="span12" style="border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;">
                    <div class="media" style="padding: 10px;">
                        <a class="pull-left" href="<?php echo $url; ?>">
                            <img class="media-object" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAEAAAABACAYAAACqaXHeAAACtUlEQVR4Xu2Y24upYRTGl0lGmNQUhXCDmRqTKCmH8q87C2mmqYkiLpTcIGfGYfaziuxmz+x8tv1dWOtG38e73nc96/DLq+n3+zu6YtOIAFIB0gIyA654BpIMQaGAUEAoIBQQClyxAoJBwaBgUDAoGLxiCMifIcGgYFAwKBgUDAoGr1iBszFYr9ep2+3Sbrcjm81GDw8PpNFoDpL+ogy9v7+TwWCgYDD423ff6X4Jn9/tdZYAtVqNOp0OabVa9r9er8ntdpPX6+VniJJKpfi9TqejeDxONzc3P9bbJXz+tKFiATabDaXTac5oIpGgj48ParfbdHd3Rw6Hg/dsNBr8DqbX6ykWi9F0OqXX11cWLRQK0WQyIQR9e3tLz8/PlM1mT/Z5XHGndrNiAVarFR92u91y0IvFglvA5/PxGebzOeVyOXK5XDQajWg2m7FQOGylUqHhcEj39/e8Dt+harBeqc9TA9//XrEA6O1qtcp+kE2UOQxBPD09cZDj8ZiSySSVSiVaLpcHAY7Fwxqz2UzhcJjO8fnfBUCG8/k8l240GuUsFotF7nW/38/iGI1Gcjqd1Gw2uVI8Hg8/w1qtFr+HRSIRrqJzfSoRQXEFIOOYAehtCLB/hgCPj4/08vLy5TwYgKgIDMdMJnOoGovFQoFA4OBDic+/DdfvxFEsAIJABSBrdrudPweDAVmtVq4AlDkMByuXyxwcMo3g3t7eqNfrcYVgHaoDAxBrlfpUkn2sUSwAFmOAFQoFAhFgaAf0MoI8NrQGBAAFMBAhyJ4eEALcxxzZ0+RUn6pQ4DhAoAxmMpmUJuLLukv4/NPhzqqAfxatio5EALkRkhshuRGSGyEVh7DqWwsFhAJCAaGAUED1UaziAYQCQgGhgFBAKKDiEFZ9a6GAUEAoIBQQCqg+ilU8gFDg2inwCQx0jZ8W40NiAAAAAElFTkSuQmCC">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a href="<?php echo $url; ?>"><?php echo $cat_name; ?></a>
                            </h4>
                            <p>
                                <?php echo $cat_desc; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        <?php }
?>
        <!--</tbody>
    </table>-->
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
