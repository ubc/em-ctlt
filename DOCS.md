# File Structure Explanation
============================

The documentation for custom_includes.php, custom_functions.php and custom_shortcodes.php can be found in the (Event Espresso documentation)[http://eventespresso.com/wiki/custom-files-addon/]. To access the Event Espresso docs, you will need to have an account with them.

Additional functions added in custom_functions.php are:

- CTLT Display Event Espresso Search
This function creates a customized autocomplete search tool (unused).

- Espresso Include JS for Templates
This function enqueues scripts for custom templates.

- CTLT Display Event Materials List
This function displays event materials for events that have set them. It is called by the [CTLT_EVENT_MATERIALS_LIST] short code.

- CTLT Profile Fields Hiding
This function hides irrelevant fields in the WordPress user profile page.

- CTLT Modify Contact Methods
This function removes irrelevant fields in the WordPress user profile page.

- CTLT Profile Fields
The function creates custom CTLT profile fields (organization, faculty, department) for registrants and attendees in their WordPress user profile. It depends on the js/ctlt_profile_information.js script.

- CTLT Save Contact Methods
This function allows extra user profile fields to be saved in the user profile.

- CTLT Automatic Email Reminders
This function creates email reminders sent out two days before an event occurs to registered participants, reminding them of the time and date of the event.

- CTLT Automatic Waitlist Event Creation 
This function creates a waitlist event associated with an administrator- or manager-created event. This function depends on changes made in event-espresso/includes/event-management/update_event.php (see below Changes to Core Plugins section, below).

- CTLT Automatic Waitlist Transfer for Administrative Deletion
This function transfers a registrant from a waitlist to the main event if the event manager or administrator deletes a registrant for the main event, thus opening up a spot. This function depends on changes made in event-espresso/includes/admin-reports/event_list_attendees.php (see below Changes to Core Plugins section, below).

- CTLT Automatic Waitlist Transfer for User Deletion
This function transfers a registrant from a waitlist to the main event if a registrant in the main event deletes their reservation, thus opening up a spot. This function depends on changes made in event-espresso/includes/admin-reports/event_list_attendees.php (see below Changes to Core Plugins section, below).

- CTLT Send Automatic Email Cancellation Message
This function sends an email to all registered attendees if an event has been canceled.

- CTLT Transfer Email
This function sends an email to an attendee who originally signed up for a waitlist, confirming that they have been transferred to the main event (either by an administrator or by a user canceling an event). 

- CTLT Event Espresso Update Notice
This function sends an email to all registered attendees if an event updates, notifying them that the event has updated but not its details.

- CTLT Event Update Post Tags
This function updates the post tags for an event post (if one is created) as the event's categories.

- CTLT Event Creation Admin Notification
This function sends an email to the Event Espresso admin email account whenever an event is created. If an event manager creates a draft email that needs approval before publishing, this email will alert the admin.

- CTLT Attendees Export to HTML
This function exports attendee information to a printer-friendly HTML format for easy sign-in sheets. This function depends on changes made in event-espresso/includes/admin-reports/event_list_attendees.php (see below Changes to Core Plugins section, below).

============================

Additional shortcodes in custom_shortcodes.php are:

- [CTLT_EVENT_MATERIALS_LIST]
Displays a list of materials for past events (unless they have set that they do not want these materials displayed).

- [CTLT_EVENT_SEARCH]
Displays a search box to allow you to dynamically search for events

============================

New CTLT templates used are:

## espresso/templates/confirmation_display.php

This file controls what is displayed for the confirmation page when registering for an event. The changes made to this file are mainly styling changes.

## espresso/templates/ctlt_espresso_category_display.php

This file controls displaying event categories. If an event is created and assigned a category, a category will be created for the category page, otherwise the event will be under the uncategorized category, which also shows up in the category page. The category pages can be displayed using a shortcode, which is described in the template file.

## espresso/templates/ctlt_espresso_category_registration.php

This file controls all of the logic to display a singular category view of events. All events under the category will be displayed in the singular category page. This file is paired with the ctlt_espresso_category_registration.php file.

## espresso/templates/ctlt_espresso_category_registration_display.php

This file controls the displaying of the events for the singular category page. Each of the events displayed are a quick overview of the actual event shown in the registration page. This file is paired with the ctlt_espresso_category_registration.php file. TODO: make the category clickable

## espresso/templates/event_list.php

This file controls all of the logic to display each of the events. This file is paired with the event_list_display.php file.

## espresso/templates/event_list_display.php

This file controls the displaying of the events for the event listing page. Each of the events displayed are a quick overview of the actual event shown in the registration page. This file is paired with the event_list.php file. TODO: make the category clickable

## espresso/templates/multi_registration_page.php

This file controls the registration page when registering multiple events at once. This file only comes into play when the multi event registration addon for Event Espresso is enabled.

## espresso/templates/multi_registration_page_display.php

This file controls the displaying of each event when registering multiple events from the shopping cart. This file is paired with the multi_registration_page.php file.

## espresso/templates/past_event_list.php

This file controls all of the logic to display each of the events that have expired. This will only display events that are marked as expired. This file is paired with the past_event_list_display.php file.

## espresso/templates/past_event_list_display.php

This file controls the display of each of the expired events. Each of the events displayed are a quick overview of the actual event shown in it's registration page. This file is paired with the past_event_list.php file.

## espresso/templates/payment_overview.php

This file controls the displaying of the payment overview when first registering for an event. The changes made to this file are mainly styling.

## espresso/templates/payment_page.php

This file controls the displaying of the payment overview when viewing a registered event as an attendee. The changes made to this file are mainly styling to be consistent with the UBC CLF.

## espresso/templates/pending_approval.php

This file displays the attendee's status in the event they have registered for. For example, if an attendee registers for an event that requires administrative approval, this page will be displayed when the attendee views the event in the event list.

## espresso/templates/registration_page.php

This file controls the logic for the single event registration page. No changes have been made to this file. This file is paired with the registration_page_display.php file.

## espresso/templates/registration_page_display.php

This file controls the displaying of the single event registration page. Changes made to this file include styling changes and additional information being displayed depending on the user capabilities. This file is paired with the registration_page_display.php file.

## espresso/templates/shopping_cart.php

This file is responsible for displaying the contents of the user's current shopping cart for when events are added to it. This file will only be used when the multi event registration addon for Event Espresso is enabled.

============================

Changes to Core Plugins

## event-espresso/includes/process-registration/add_attendees_to_db.php

These changes do two things: adding a check to allow event managers and administrators to allow event overloading and updating the user's meta information (faculty, staff, organization) when registering for an event, if that information is incomplete.

The first change is in function espresso_verify_sufficient_remaining_tickets:

if( $available_spaces >= $tickets_requested || $data_source['admin'] == true){
   return true;

The second change is in function event_espresso_add_attendees_to_db:

$user_id = get_current_user_id();
if( isset($_POST['event_espresso_organization']) ) {
   update_user_meta( $user_id, 'event_espresso_organization', $_POST['event_espresso_organization'] );
}
if( isset($_POST['event_espresso_faculty']) ) {
   update_user_meta( $user_id, 'event_espresso_faculty', $_POST['event_espresso_faculty'] );
}
if( isset($_POST['event_espresso_department']) ) {
   update_user_meta( $user_id, 'event_espresso_department', $_POST['event_espresso_department'] );
}


## event-espresso/includes/admin-reports/add_new_attendee.php

This change adds code to enforce single registration just before the end of the field, before the function event_espresso_additional_attendees is called:

$number_available_spaces = 1;


## espresso-permissions-pro/espresso-permissions-pro.php

This change adds a new default question for new users: Attending As. Event Espresso creates a new set of questions for each new user. This will enforce question uniformity among Event Managers as they are created.

The change is in the function espresso_add_default_questions in the "if (sizeof($questions) == 0)" clause:

$wpdb->insert($wpdb->prefix . "events_question", array('wp_user' => $user_id, 'question' => 'Attending As', 'question_type' => 'DROPDOWN', 'response' => 'Staff, Student, Assistant Professor, Associate Professor, Faculty, Head Instructor, Instructor, Lecturer, Professor, Professor of Teaching, Senior Instructor, Senior Lecturer, Sessional Instructor Other', 'system_name' => 'email', 'required' => 'Y', 'sequence' => '2'), array('%s', '%s', '%s', '%s', '%s'));


## event-espresso/includes/admin-reports/event_list_attendees.php

These changes add two buttons (one for exporting events to a printer-friendly format, one for transferring selected attendees from a waitlist event to a main event), the implementation of the transfer button, and replaces Event Espresso's existing administrative deletion option with a reporting-friendly setting of their registration status to 'Cancelled'.

All changes are in function event_list_attendees.

The first change is just after the "if ($EVT_ID)" clause concludes:

if (isset($_POST['transfer_customer']) && !empty($_POST['transfer_customer'])) {
    if (is_array($_POST['checkbox'])) {
    
        while (list( $att_id, $value ) = each($_POST['checkbox'])) {

            $sql = "SELECT event_id, id FROM " . EVENTS_ATTENDEE_TABLE . " WHERE id = %d ";
            $sql_results = $wpdb->get_row($wpdb->prepare($sql, $att_id));
            $overflow_event_id = $sql_results->event_id;
            $attendee_id = $sql_results->id;
            
            $sql = "SELECT id FROM " . EVENTS_DETAIL_TABLE . " WHERE overflow_event_id = %d";
            $event_id = $wpdb->get_row($wpdb->prepare($sql, $overflow_event_id));
            $event_id = $event_id->id;

            if( $event_id != 0 ) {
                $wpdb->update( EVENTS_ATTENDEE_TABLE, array( 'event_id' => $event_id ), array( 'id' => $attendee_id ), array( '%d' ) );
                $wpdb->update( EVENTS_MEMBER_REL_TABLE, array( 'event_id' => $event_id ), array( 'attendee_id' => $attendee_id ), array( '%d' ) );
                ctlt_transfer_email( $attendee_id, $event_id );
            }
            
        }
    }
}

The second change is inside the "while (list( $att_id, $value ) = each($_POST['checkbox']))" loop before the do_action function call:

// Old Event Espresso Code
//hook for before delete
// do_action('action_hook_espresso_before_delete_attendee_event_list', $att_id, $EVT_ID);

// $SQL = "DELETE FROM " . EVENTS_ATTENDEE_TABLE . " WHERE id = '%d'";
// $wpdb->query($wpdb->prepare($SQL, $att_id));
// $SQL = "DELETE FROM " . EVENTS_ATTENDEE_META_TABLE . " WHERE attendee_id = '%d'";
// $wpdb->query($wpdb->prepare($SQL, $att_id));
// $SQL = "DELETE FROM " . EVENTS_ANSWER_TABLE . " WHERE attendee_id = '%d'";
// $wpdb->query($wpdb->prepare($SQL, $att_id));

$upd_success = $wpdb->query(
    $wpdb->prepare(
        "UPDATE " . EVENTS_ATTENDEE_TABLE . " SET payment_status = 'Cancelled' WHERE id = %d", $att_id
    )
);

if ( $upd_success === FALSE ) {
    $notifications['error'][] = __('An error occured. The registration was not cancelled.', 'event_espresso');
}

The third change (the transfer button) is among the sequence of inputs tags: 

<input name="transfer_customer" type="submit" class="button-secondary" id="transfer_customer" value="<?php _e('Transfer Attendee(s) to Main Event', 'event_espresso'); ?>" style="margin:10px 0 0 10px;" />

The fourth change (the export button) is just after:

<?php wp_nonce_field('ctlt_espresso_nonce_check','ctlt_espresso_nonce_field'); ?>
<input name="ctlt_export_to_html" type="submit" class="button-secondary" id="ctlt_export_to_html" value="<?php _e('Export to HTML/Printable', 'event_espresso'); ?>" style="margin:10px 0 0 10px;" />


## event-espresso/includes/event-management/insert_event.php

This change allow custom CTLT event information to be saved when an event is created and occurs just after the

"//Process blog or custom post
if ( isset($_REQUEST['create_post']) && $_REQUEST['create_post'] == 'Y' )"

clause concludes:

do_action( 'ctlt_espresso_insert_event', $last_event_id );


## event-espresso/includes/event-management/update_event.php

These changes allow custom CTLT event information to be saved when an event is updated, as well as allowing events with a registration limit of 0.

The first change replaces the entire "if (isset($reg_limit) && empty($reg_limit))" clause with:

if (isset($reg_limit) && empty($reg_limit)) {
    // CTLT Begin
    // Allows for events with a registration limit of 0, used in manual waitlists
    if ( empty($reg_limit) ) {
        $reg_limit = 0;
    } else {
        $reg_limit = 999999;
    }
    // CTLT End
}

The second change is after the "if ( isset( $_REQUEST[ 'create_post' ] ) ) " clause:

do_action( 'ctlt_espresso_update_event', $event_id );


## event-espresso/includes/event-management/edit_event.php

This change allows event managers to edit events only before they are published. It occurs just after the "$values = array(" assignment clause:

if (function_exists('espresso_is_my_event') && espresso_is_my_event($event_id) != true && $event->event_status != 'P' ) {
	echo '<h2>' . __('Sorry, you do not have permission to edit this event.', 'event_espresso') . '</h2>';
	return;
}

## event-espresso/includes/functions/admin.php

This change removes some extra comments created by event-espresso that lists directory and files (just commented out line 1419).

//add_action('wp_footer', 'espresso_files_in_uploads', 20);
