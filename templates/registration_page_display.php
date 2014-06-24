<?php
//This is the registration form.
//This is a template file for displaying a registration form for an event on a page.
//There should be a copy of this file in your wp-content/uploads/espresso/ folder.
?>

    <style>
    /**
 * selectize.default.css (v0.10.1) - Default Theme
 * Copyright (c) 2013 Brian Reavis & contributors
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not use this
 * file except in compliance with the License. You may obtain a copy of the License at:
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under
 * the License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF
 * ANY KIND, either express or implied. See the License for the specific language
 * governing permissions and limitations under the License.
 *
 * @author Brian Reavis <brian@thirdroute.com>
 */
.selectize-control.plugin-drag_drop.multi > .selectize-input > div.ui-sortable-placeholder {
  visibility: visible !important;
  background: #f2f2f2 !important;
  background: rgba(0, 0, 0, 0.06) !important;
  border: 0 none !important;
  -webkit-box-shadow: inset 0 0 12px 4px #ffffff;
  box-shadow: inset 0 0 12px 4px #ffffff;
}
.selectize-control.plugin-drag_drop .ui-sortable-placeholder::after {
  content: '!';
  visibility: hidden;
}
.selectize-control.plugin-drag_drop .ui-sortable-helper {
  -webkit-box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
.selectize-dropdown-header {
  position: relative;
  padding: 5px 8px;
  border-bottom: 1px solid #d0d0d0;
  background: #f8f8f8;
  -webkit-border-radius: 3px 3px 0 0;
  -moz-border-radius: 3px 3px 0 0;
  border-radius: 3px 3px 0 0;
}
.selectize-dropdown-header-close {
  position: absolute;
  right: 8px;
  top: 50%;
  color: #303030;
  opacity: 0.4;
  margin-top: -12px;
  line-height: 20px;
  font-size: 20px !important;
}
.selectize-dropdown-header-close:hover {
  color: #000000;
}
.selectize-dropdown.plugin-optgroup_columns .optgroup {
  border-right: 1px solid #f2f2f2;
  border-top: 0 none;
  float: left;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.selectize-dropdown.plugin-optgroup_columns .optgroup:last-child {
  border-right: 0 none;
}
.selectize-dropdown.plugin-optgroup_columns .optgroup:before {
  display: none;
}
.selectize-dropdown.plugin-optgroup_columns .optgroup-header {
  border-top: 0 none;
}
.selectize-control.plugin-remove_button [data-value] {
  position: relative;
  padding-right: 24px !important;
}
.selectize-control.plugin-remove_button [data-value] .remove {
  z-index: 1;
  /* fixes ie bug (see #392) */
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 17px;
  text-align: center;
  font-weight: bold;
  font-size: 12px;
  color: inherit;
  text-decoration: none;
  vertical-align: middle;
  display: inline-block;
  padding: 2px 0 0 0;
  border-left: 1px solid #0073bb;
  -webkit-border-radius: 0 2px 2px 0;
  -moz-border-radius: 0 2px 2px 0;
  border-radius: 0 2px 2px 0;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
}
.selectize-control.plugin-remove_button [data-value] .remove:hover {
  background: rgba(0, 0, 0, 0.05);
}
.selectize-control.plugin-remove_button [data-value].active .remove {
  border-left-color: #00578d;
}
.selectize-control.plugin-remove_button .disabled [data-value] .remove:hover {
  background: none;
}
.selectize-control.plugin-remove_button .disabled [data-value] .remove {
  border-left-color: #aaaaaa;
}
.selectize-control {
  position: relative;
}
.selectize-dropdown,
.selectize-input,
.selectize-input input {
  color: #303030;
  font-family: inherit;
  font-size: 13px;
  line-height: 18px;
  -webkit-font-smoothing: inherit;
}
.selectize-input,
.selectize-control.single .selectize-input.input-active {
  background: #ffffff;
  cursor: text;
  display: inline-block;
}
.selectize-input {
  border: 1px solid #d0d0d0;
  padding: 8px 8px;
  display: inline-block;
  width: 100%;
  overflow: hidden;
  position: relative;
  z-index: 1;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.1);
  box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.1);
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
}
.selectize-control.multi .selectize-input.has-items {
  padding: 5px 8px 2px;
}
.selectize-input.full {
  background-color: #ffffff;
}
.selectize-input.disabled,
.selectize-input.disabled * {
  cursor: default !important;
}
.selectize-input.focus {
  -webkit-box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15);
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15);
}
.selectize-input.dropdown-active {
  -webkit-border-radius: 3px 3px 0 0;
  -moz-border-radius: 3px 3px 0 0;
  border-radius: 3px 3px 0 0;
}
.selectize-input > * {
  vertical-align: baseline;
  display: -moz-inline-stack;
  display: inline-block;
  zoom: 1;
  *display: inline;
}
.selectize-control.multi .selectize-input > div {
  cursor: pointer;
  margin: 0 3px 3px 0;
  padding: 2px 6px;
  background: #1da7ee;
  color: #ffffff;
  border: 1px solid #0073bb;
}
.selectize-control.multi .selectize-input > div.active {
  background: #92c836;
  color: #ffffff;
  border: 1px solid #00578d;
}
.selectize-control.multi .selectize-input.disabled > div,
.selectize-control.multi .selectize-input.disabled > div.active {
  color: #ffffff;
  background: #d2d2d2;
  border: 1px solid #aaaaaa;
}
.selectize-input > input {
  padding: 0 !important;
  min-height: 0 !important;
  max-height: none !important;
  max-width: 100% !important;
  margin: 0 1px !important;
  text-indent: 0 !important;
  border: 0 none !important;
  background: none !important;
  line-height: inherit !important;
  -webkit-user-select: auto !important;
  -webkit-box-shadow: none !important;
  box-shadow: none !important;
}
.selectize-input > input::-ms-clear {
  display: none;
}
.selectize-input > input:focus {
  outline: none !important;
}
.selectize-input::after {
  content: ' ';
  display: block;
  clear: left;
}
.selectize-input.dropdown-active::before {
  content: ' ';
  display: block;
  position: absolute;
  background: #f0f0f0;
  height: 1px;
  bottom: 0;
  left: 0;
  right: 0;
}
.selectize-dropdown {
  position: absolute;
  z-index: 10;
  border: 1px solid #d0d0d0;
  background: #ffffff;
  margin: -1px 0 0 0;
  border-top: 0 none;
  -webkit-box-sizing: border-box;
  -moz-box-sizing: border-box;
  box-sizing: border-box;
  -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  -webkit-border-radius: 0 0 3px 3px;
  -moz-border-radius: 0 0 3px 3px;
  border-radius: 0 0 3px 3px;
}
.selectize-dropdown [data-selectable] {
  cursor: pointer;
  overflow: hidden;
}
.selectize-dropdown [data-selectable] .highlight {
  background: rgba(125, 168, 208, 0.2);
  -webkit-border-radius: 1px;
  -moz-border-radius: 1px;
  border-radius: 1px;
}
.selectize-dropdown [data-selectable],
.selectize-dropdown .optgroup-header {
  padding: 5px 8px;
}
.selectize-dropdown .optgroup:first-child .optgroup-header {
  border-top: 0 none;
}
.selectize-dropdown .optgroup-header {
  color: #303030;
  background: #ffffff;
  cursor: default;
}
.selectize-dropdown .active {
  background-color: #f5fafd;
  color: #495c68;
}
.selectize-dropdown .active.create {
  color: #495c68;
}
.selectize-dropdown .create {
  color: rgba(48, 48, 48, 0.5);
}
.selectize-dropdown-content {
  overflow-y: auto;
  overflow-x: hidden;
  max-height: 200px;
}
.selectize-control.single .selectize-input,
.selectize-control.single .selectize-input input {
  cursor: pointer;
}
.selectize-control.single .selectize-input.input-active,
.selectize-control.single .selectize-input.input-active input {
  cursor: text;
}
.selectize-control.single .selectize-input:after {
  content: ' ';
  display: block;
  position: absolute;
  top: 50%;
  right: 15px;
  margin-top: -3px;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 5px 5px 0 5px;
  border-color: #808080 transparent transparent transparent;
}
.selectize-control.single .selectize-input.dropdown-active:after {
  margin-top: -4px;
  border-width: 0 5px 5px 5px;
  border-color: transparent transparent #808080 transparent;
}
.selectize-control.rtl.single .selectize-input:after {
  left: 15px;
  right: auto;
}
.selectize-control.rtl .selectize-input > input {
  margin: 0 4px 0 -2px !important;
}
.selectize-control .selectize-input.disabled {
  opacity: 0.5;
  background-color: #fafafa;
}
.selectize-control.multi .selectize-input.has-items {
  padding-left: 5px;
  padding-right: 5px;
}
.selectize-control.multi .selectize-input.disabled [data-value] {
  color: #999;
  text-shadow: none;
  background: none;
  -webkit-box-shadow: none;
  box-shadow: none;
}
.selectize-control.multi .selectize-input.disabled [data-value],
.selectize-control.multi .selectize-input.disabled [data-value] .remove {
  border-color: #e6e6e6;
}
.selectize-control.multi .selectize-input.disabled [data-value] .remove {
  background: none;
}
.selectize-control.multi .selectize-input [data-value] {
  text-shadow: 0 1px 0 rgba(0, 51, 83, 0.3);
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  background-color: #1b9dec;
  background-image: -moz-linear-gradient(top, #1da7ee, #178ee9);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#1da7ee), to(#178ee9));
  background-image: -webkit-linear-gradient(top, #1da7ee, #178ee9);
  background-image: -o-linear-gradient(top, #1da7ee, #178ee9);
  background-image: linear-gradient(to bottom, #1da7ee, #178ee9);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff1da7ee', endColorstr='#ff178ee9', GradientType=0);
  -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.2),inset 0 1px rgba(255,255,255,0.03);
  box-shadow: 0 1px 0 rgba(0,0,0,0.2),inset 0 1px rgba(255,255,255,0.03);
}
.selectize-control.multi .selectize-input [data-value].active {
  background-color: #0085d4;
  background-image: -moz-linear-gradient(top, #008fd8, #0075cf);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#008fd8), to(#0075cf));
  background-image: -webkit-linear-gradient(top, #008fd8, #0075cf);
  background-image: -o-linear-gradient(top, #008fd8, #0075cf);
  background-image: linear-gradient(to bottom, #008fd8, #0075cf);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff008fd8', endColorstr='#ff0075cf', GradientType=0);
}
.selectize-control.single .selectize-input {
  -webkit-box-shadow: 0 1px 0 rgba(0,0,0,0.05), inset 0 1px 0 rgba(255,255,255,0.8);
  box-shadow: 0 1px 0 rgba(0,0,0,0.05), inset 0 1px 0 rgba(255,255,255,0.8);
  background-color: #f9f9f9;
  background-image: -moz-linear-gradient(top, #fefefe, #f2f2f2);
  background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#fefefe), to(#f2f2f2));
  background-image: -webkit-linear-gradient(top, #fefefe, #f2f2f2);
  background-image: -o-linear-gradient(top, #fefefe, #f2f2f2);
  background-image: linear-gradient(to bottom, #fefefe, #f2f2f2);
  background-repeat: repeat-x;
  filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fffefefe', endColorstr='#fff2f2f2', GradientType=0);
}
.selectize-control.single .selectize-input,
.selectize-dropdown.single {
  border-color: #b8b8b8;
}
.selectize-dropdown .optgroup-header {
  padding-top: 7px;
  font-weight: bold;
  font-size: 0.85em;
}
.selectize-dropdown .optgroup {
  border-top: 1px solid #f0f0f0;
}
.selectize-dropdown .optgroup:first-child {
  border-top: 0 none;
}
    </style>

    <div id="espresso-event-id-<?php echo $event_id; ?>">
    <div id="event_espresso_registration_form" class="event-display-boxes ui-widget">
    <?php
    $ui_corner = 'ui-corner-all'; 
    //This tells the system to hide the event title if we only need to display the registration form.
    if ($reg_form_only == false) { 
    ?>
        <h3 id="event_title-<?php echo $event_id; ?>">
            <?php echo $event_name ?> <?php echo $is_active['status'] == 'EXPIRED' ? ' - <span class="expired_event">Event Expired</span>' : ''; ?> <?php echo $is_active['status'] == 'PENDING' ? ' - <span class="expired_event">Event is Pending</span>' : ''; ?> <?php echo $is_active['status'] == 'DRAFT' ? ' - <span class="expired_event">Event is a Draft</span>' : ''; ?>
        <?php
            if( $all_meta['event_status'] == 'D' ) {
                ?>
                <span style="color:red">- Cancelled</span>
                <?php
            }
        ?>
        </h3>
        <p class="ctlt-event-category">
        <?php
            if( is_null( $category_ids[0]->category_id ) == FALSE) {
                foreach($categories as $category) {
                    $category_id = $category->id;
                    $category_name = $category->category_name;
                    $category_url = get_permalink( $org_options['event_page_id'] );
                    $category_url = add_query_arg('category_name', $category_name, $category_url );
                    echo '<a href="' . $category_url . '">' . $category_name . '</a>';
                    if ($category != end($categories))
                        echo ', ';
                }
        } ?>
        </p>
        
    <?php 
        $ui_corner = 'ui-corner-bottom';
    }
    ?>
        <?php //Featured image
            echo apply_filters('filter_hook_espresso_display_featured_image', $event_id, !empty($event_meta['event_thumbnail_url']) ? $event_meta['event_thumbnail_url'] : '');?>

        <?php /* Venue details. Un-comment first and last lines & any venue details you wish to display or use the provided shortcodes. */ ?>
        <?php // echo '<div id="venue-details-display">'; ?>
        <?php // echo '<p class="section-title">' . __('Venue Details', 'event_espresso') . '</p>'; ?>
        <?php // echo $venue_address != ''?'<p id="event_venue_address-'.$event_id.'" class="event_venue_address">'.stripslashes_deep($venue_address).'</p>':''?>
        <?php // echo $venue_address2 != ''?'<p id="event_venue_address2-'.$event_id.'" class="event_venue_address2">'.stripslashes_deep($venue_address2).'</p>':''?>
        <?php // echo $venue_city != ''?'<p id="event_venue_city-'.$event_id.'" class="event_venue_city">'.stripslashes_deep($venue_city).'</p>':''?>
        <?php // echo $venue_state != ''?'<p id="event_venue_state-'.$event_id.'" class="event_venue_state">'.stripslashes_deep($venue_state).'</p>':''?>
        <?php // echo $venue_zip != ''?'<p id="event_venue_zip-'.$event_id.'" class="event_venue_zip">'.stripslashes_deep($venue_zip).'</p>':''?>
        <?php // echo $venue_country != ''?'<p id="event_venue_country-'.$event_id.'" class="event_venue_country">'.stripslashes_deep($venue_country).'</p>':''?>
        <?php // echo '</div>'; ?>
        <?php /* end venue details block */ ?>

        <?php if ($display_desc == "Y") { //Show the description or not ?>
        <div>
            <p><?php echo espresso_format_content($event_desc); //Code to show the actual description. The Wordpress function "wpautop" adds formatting to your description.   ?></p>
            
        </div>
        <?php
        }//End display description
        ?>

        <?php
            if( is_user_logged_in() && $admin_notes['_ctlt_espresso_do_not_handout'] == 'no' ) {
                $handout = ctlt_display_event_materials_list( $event_id );
                if( $handout != null ) {
                    $upload_base_dir = wp_upload_dir();
                    echo '<a href="' . $upload_base_dir['baseurl'] . '/' . $handout[0]->attachment_url . '">Event Materials</a>';
                }
            }
        ?>

        <h4>Event Details:</h4>
        <p><?php echo event_date_display($start_date, get_option('date_format')); ?>
        <?php if ($end_date !== $start_date) : ?> - <?php echo event_date_display($end_date, get_option('date_format')); ?>
        <?php endif; ?>

        <?php
            if( $ctlt_noncontiguous == 'yes' ) {
                ?>
                <br />NOTE: This event occurs on non-consecutive days and/or locations. See the event description for details.
                <?php
            }
        ?>
        </p>
        <p><?php echo espresso_event_time($event_id, 'start_time'); ?> - <?php echo espresso_event_time($event_id, 'end_time'); ?></p>

        <?php
        
        echo $venue_url != ''?'<a href="'.$venue_url.'">':'';
        echo $venue_title != ''?'<p id="event_venue_name-'.$event_id.'" class="event_venue_name">'.stripslashes_deep($venue_title).'</p>':'';
        echo $venue_url != ''?'</a>':'';
        
        if ( is_user_logged_in() ) {

        switch ($is_active['status']) {
        
            case 'EXPIRED': 
            
                //only show the event description.
                echo '<h3 class="expired_event">' . __('This event has passed.', 'event_espresso') . '</h3>';
                break;

            case 'REGISTRATION_CLOSED': 
            
                //only show the event description.
                // if todays date is after $reg_end_date
    ?>
        <div class="event-registration-closed event-messages ui-corner-all ui-state-highlight">
            <span class="ui-icon ui-icon-alert"></span>
            <p class="event_full">
                <strong>
                    <?php _e('We are sorry but registration for this event is now closed.', 'event_espresso'); ?>
                </strong>
            </p>
            <p class="event_full">
                <strong>
                    <?php  _e('Please ', 'event_espresso');?><a href="contact" title="<?php  _e('contact us ', 'event_espresso');?>"><?php  _e('contact us ', 'event_espresso');?></a><?php  _e('if you would like to know if spaces are still available.', 'event_espresso'); ?>
                </strong>
            </p>
        </div>
    <?php
                break;

            case 'REGISTRATION_NOT_OPEN': 
                //only show the event description.
                // if todays date is after $reg_end_date
                // if todays date is prior to $reg_start_date
    ?>
        <div class="event-registration-pending event-messages ui-corner-all ui-state-highlight">
            <span class="ui-icon ui-icon-alert"></span>
                <p class="event_full">
                    <strong>
                        <?php _e('We are sorry but this event is not yet open for registration.', 'event_espresso'); ?>
                    </strong>
                </p>
                <p class="event_full">
                    <strong>
                        <?php echo  __('You will be able to register starting ', 'event_espresso') . ' ' . event_espresso_no_format_date($reg_start_date, 'F d, Y'); ?>
                    </strong>
                </p>
            </div>
    <?php
            break;
        
            case 'PENDING':
                //only show the event description.
                // if todays date is after $reg_end_date
                // if todays date is prior to $reg_start_date
    ?>
        <div class="event-registration-pending event-messages ui-corner-all ui-state-highlight">
            <span class="ui-icon ui-icon-alert"></span>
                <p class="event_full">
                    <strong>
                        <?php _e('We are sorry but this event is not yet open for registration.', 'event_espresso'); ?>
                    </strong>
                </p>
            </div>
    <?php
            break;

            default: //This will display the registration form
                ?>

                <?php
                    if( $all_meta['event_status'] == 'D' ) {
                        ?>
                        <h4>This event has been cancelled and is no longer available for registration.</h4>
                        <?php
                    } else
                    if( $already_registered == TRUE ) {
                        ?>
                        <h4>You are already registered for this event or waitlist.</h4>
                        <p>Select "My Events" to view or change your registration details.</p>
                        <?php
                    }
                    else
                    if ( $reg_limit == 0 ) {
                        ?>
                        <p>This event requires an application to join.</p>
                        <p id="register_link-<?php echo $overflow_event_id ?>" class="register-link-footer"><a class="btn" id="a_register_link-<?php echo $overflow_event_id ?>" href="<?php echo espresso_reg_url($overflow_event_id); ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('Apply to Attend', 'event_espresso'); ?></a>
                        </p>
                        <?php
                    }
                    else
                    if ($num_attendees >= $reg_limit) {
                    ?>
                    <div class="espresso_event_full event-display-boxes" id="espresso_event_full-<?php echo $event_id; ?>">
                            <p class="event_full"><strong><?php _e('We are sorry but this event has reached the maximum number of attendees!', 'event_espresso'); ?></strong></p>
                            <p class="event_full"><strong><?php _e('Please check back in the event someone cancels.', 'event_espresso'); ?></strong></p>
                            <p class="num_attendees"><?php _e('Current Number of Attendees:', 'event_espresso'); ?> <?php echo $num_attendees ?></p>
                    <?php
                    $num_attendees = get_number_of_attendees_reg_limit($event_id, 'num_attendees'); //Get the number of attendees. Please visit http://eventespresso.com/forums/?p=247 for available parameters for the get_number_of_attendees_reg_limit() function.
                    if (($num_attendees >= $reg_limit) && ($allow_overflow == 'Y' && $overflow_event_id != 0)) {
                        ?>
                            <p>If you still wish to attend this event, you can join the waiting list.</p>
                            <p id="register_link-<?php echo $overflow_event_id ?>" class="register-link-footer"><a class="btn" id="a_register_link-<?php echo $overflow_event_id ?>" href="<?php echo espresso_reg_url($overflow_event_id); ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('Join Waiting List', 'event_espresso'); ?></a></p>
                        <?php } ?>
                    </div>

                        <?php
                    } else {
    ?>
        <div class="event_espresso_form_wrapper">
            <form method="post" action="<?php echo get_permalink( $event_page_id );?>" id="registration_form">
        <?php

                // * * This section shows the registration form if it is an active event * *

                    if ($display_reg_form == 'Y') {
        ?>
                    <p class="event_time">
        <?php
                            //This block of code is used to display the times of an event in either a dropdown or text format.
                            if (isset($time_selected) && $time_selected == true) {//If the customer is coming from a page where the time was preselected.
                                echo event_espresso_display_selected_time($time_id); //Optional parameters start, end, default
                            } else {
                            }//End time selected
        ?>
                    </p>
        <?php

                        // Added for seating chart addon
                        $display_price_dropdown = TRUE;

                        if (defined('ESPRESSO_SEATING_CHART')) {
                            $seating_chart_id = seating_chart::check_event_has_seating_chart($event_id);
                            if ($seating_chart_id !== FALSE) {
                                $display_price_dropdown = FALSE;
                            }
                        }

                        if ($display_price_dropdown == TRUE) {
                            $price_label = '<span class="section-title">'.__('Choose an Option: ', 'event_espresso').'</span>';
        ?>
                            <p class="event_prices">
                                <?php do_action( 'espresso_price_select', $event_id, array('show_label'=>TRUE, 'label'=>$price_label) );?>
                            </p>
        <?php
                        } else {
        ?>
                            <p class="event_prices">
                                <?php do_action( 'espresso_seating_price_select_action', $event_id );?>
                            </p>
        <?php
                            // Seating chart selector
                            do_action('espresso_seating_chart_select', $event_id);
                                
                        }
        ?>
                    
                    <div id="event-reg-form-groups">
                        
        <?php
                        //Outputs the custom form questions. This function can be overridden using the custom files addon
                        echo event_espresso_add_question_groups( $question_groups, '', NULL, FALSE, array( 'attendee_number' => 1 ), 'ee-reg-page-questions' );
        ?>
                    </div>

        <?php					
                        //Coupons
        ?>
                    <input type="hidden" name="use_coupon[<?php echo $event_id; ?>]" value="<?php echo $use_coupon_code; ?>" />
        <?php
                        if ( $use_coupon_code == 'Y' && function_exists( 'event_espresso_coupon_registration_page' )) {
                            echo event_espresso_coupon_registration_page($use_coupon_code, $event_id);
                        }
                        //End coupons display
                                
                        //Groupons
        ?>
                    <input type="hidden" name="use_groupon[<?php echo $event_id; ?>]" value="<?php echo $use_groupon_code; ?>" />
        <?php
                        if ( $use_groupon_code == 'Y' && function_exists( 'event_espresso_groupon_registration_page' )) {
                            echo event_espresso_groupon_registration_page($use_groupon_code, $event_id);
                        }
                        //End groupons display
        ?>
                    <input type="hidden" name="regevent_action" id="regevent_action-<?php echo $event_id; ?>" value="post_attendee">
                    <input type="hidden" name="event_id" id="event_id-<?php echo $event_id; ?>" value="<?php echo $event_id; ?>">
                    
        <?php
                        //Multiple Attendees
                        if ( $allow_multiple == "Y" && $number_available_spaces > 1 ) {					
                            //This returns the additional attendee form fields. Can be overridden in the custom files addon.
                            echo event_espresso_additional_attendees($event_id, $additional_limit, $number_available_spaces, __('Number of Tickets', 'event_espresso'), true, $event_meta);
                        } else {				
        ?>
                    <input type="hidden" name="num_people" id="num_people-<?php echo $event_id; ?>" value="1">
        <?php
                        }					
                        //End allow multiple	
                        
                        wp_nonce_field('reg_nonce', 'reg_form_nonce');
                        
                        //Recaptcha portion
                        if ( $org_options['use_captcha'] == 'Y' && empty($_REQUEST['edit_details']) && ! is_user_logged_in()) {
                        
                            if ( ! function_exists('recaptcha_get_html')) {
                                require_once(EVENT_ESPRESSO_PLUGINFULLPATH . 'includes/recaptchalib.php');
                            }
                            # the response from reCAPTCHA
                            $resp = null;
                            # the error code from reCAPTCHA, if any
                            $error = null;
        ?>
                    <p class="event_form_field" id="captcha-<?php echo $event_id; ?>">
                        <?php _e('Anti-Spam Measure: Please enter the following phrase', 'event_espresso'); ?>
                        <?php echo recaptcha_get_html($org_options['recaptcha_publickey'], $error, is_ssl() ? true : false); ?>
                    </p>
                        
        <?php 
                        } 
                        //End use captcha  
        ?>
                    <p>
                        <?php
                        
                            if( $user_organization == "" || $user_organization == null ) {
                                ?>
                                    <br />Enter your organization (required): <br />
                                    <p>
                                        <input type="radio" name="organization" value="UBC"> UBC<br />
                                        <input type="radio" name="organization" value="Not UBC"> Not Associated w/ UBC
                                    </p>

                                    <p class="not-ubc-org" style="display: none;"><input type="text" name="not_ubc_organization" placeholder="Organization Name" id="not_ubc_organization" /></p>

                                    <input name="event_espresso_organization" id="event_espresso_organization" type="hidden" value="">
                                <?php
                            }
                            if( $user_faculty == "" || $user_faculty == null ) {
                                ?>
                                    <div class="select-faculty">
                                        <br />Select your faculty (required):
                                        <select name="event_espresso_faculty" id="event_espresso_faculty">
                                            <option value=""></option>
                                            <?php
                                                foreach( $org_data as $organization_key => $organization_value ) {
                                                    foreach( $organization_value as $faculty_key => $faculty_value ) {
                                                        echo '<option value="' . $faculty_key . '" ';
                                                        if(esc_attr($faculty_key) == esc_attr( $user_faculty ) ) {
                                                            echo 'selected="selected"';
                                                        }
                                                        echo '>' . $faculty_key . '</option>';
                                                    }
                                                }
                                            ?>
                                        <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                <?php
                            }
                            if( $user_department == "" || $user_department == null ) {
                                ?>
                                    <div class="select-department">
                                        <br />Enter your department (required):
                                        <select name="event_espresso_department" id="event_espresso_department">
                                            <option value=""></option>
                                            <?php
                                                foreach( $org_data as $organization_key => $organization_value ) {
                                                    foreach( $organization_value as $faculty_key => $faculty_value ) {
                                                        if(esc_attr($faculty_key) == esc_attr( $user_faculty ) ) {
                                                                    foreach( $faculty_value as $department_key => $department_value ) {
                                                                        if( substr( $department_value['name'] ,0 ,3 ) != "(i)" ) {
                                                                            echo '<option value="' . $department_value['name'] . '" ';
                                                                            if(esc_attr($department_value['name']) == esc_attr( $user_department ) ) {
                                                                                echo 'selected="selected"';
                                                                            }
                                                                            echo '>' . $department_value['name'] . '</option>';
                                                                        }
                                                                    }
                                                        }
                                                    }
                                                }
                                            ?>
                                        <option value="N/A">N/A</option>
                                        </select>
                                    </div>
                                <?php
                            }
                        ?>

                    </p>
                    <p class="event_form_submit" id="event_form_submit-<?php echo $event_id; ?>">
                    <br />
                        <input class="btn" id="event_form_field-<?php echo $event_id; ?>" type="submit" name="Submit" value="<?php _e('Confirm', 'event_espresso'); ?>">
                    </p>
                    
        <?php }
            do_action('action_hook_espresso_social_display_buttons', $event_id);
        ?>

            </form>
        </div>
        
    <?php
                            }
                    break;
                    
                }
                //End Switch statement to check the status of the event

            if (isset($ee_style['event_espresso_form_wrapper_close'])) {
                echo $ee_style['event_espresso_form_wrapper_close']; 
            }			
    ?>
    <p class="edit-link-footer"><?php echo espresso_edit_this($event_id) ?></p>
    <?php } else { ?>

        <?php

            $loginURL           = 'https://em.ctlt.ubc.ca/wp-login.php?redirect_to=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $subscribeURL       = 'https://www.cwl.ubc.ca/SignUp/cwlsubscribe/SelfSubscribeIndex.do';
            $preButtonMessage   = 'At this time we require everyone - UBC affiliated or otherwise - to register for the CTLT events system. If you already have a CWL please <a href="' . $loginURL . '" title="">log in</a>. However, if you do not have a campus-wide login, then <a href="' . $subscribeURL . '" target="_new">please register for a BASIC cwl account</a> (you will see basic as the bottom option on the 3rd screen)';
            $logInCWLButtonSRC  = 'https://em.ctlt.ubc.ca/wp-content/uploads/2014/06/CWL_login_button.gif';

        ?>
        <p class="reg-required-message" style="background: #e6e6e6; padding: 15px; overflow: hidden; clear: both; margin: 30px 0;">
            <span style="display: block; width: 80%; float: left;" class="reg-message"><?php echo $preButtonMessage; ?></span><a href="<?php echo $loginURL; ?>" title="" style="display: block; float: right; width: 20%; text-align: right;"><img src="<?php echo $logInCWLButtonSRC; ?>" class="alignnone" /></a>
        </p>

        <?php //echo '<p>To register: <a id="cwl-login-buttom" href="https://em.ctlt.ubc.ca/wp-login.php?redirect_to=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']. '"><img class="alignnone size-full wp-image-1187" src="https://em.ctlt.ubc.ca/wp-content/uploads/2013/09/cwl_login.png" alt="" width="83" height="35" /></a></p>';
        ?>
    <?php } ?>
    </div>
    </div>