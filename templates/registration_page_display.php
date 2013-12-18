<?php
//This is the registration form.
//This is a template file for displaying a registration form for an event on a page.
//There should be a copy of this file in your wp-content/uploads/espresso/ folder.
?>
    <div id="espresso-event-id-<?php echo $event_id; ?>">
    <div id="event_espresso_registration_form" class="event-display-boxes ui-widget">
    <?php
    $ui_corner = 'ui-corner-all'; 
    //This tells the system to hide the event title if we only need to display the registration form.
    if ($reg_form_only == false) { 
    ?>
        <h3 id="event_title-<?php echo $event_id; ?>">
            <?php echo $event_name ?> <?php echo $is_active['status'] == 'EXPIRED' ? ' - <span class="expired_event">Event Expired</span>' : ''; ?> <?php echo $is_active['status'] == 'PENDING' ? ' - <span class="expired_event">Event is Pending</span>' : ''; ?> <?php echo $is_active['status'] == 'DRAFT' ? ' - <span class="expired_event">Event is a Draft</span>' : ''; ?>
        Registration
        <?php
            if($cancellation_status == TRUE) {
                echo '<span class="ctlt_cancelled">- CANCELLED</span>';
            }
        ?>
        </h3>
        <p class="ctlt-event-category">
        <?php
            if( is_null( $category_ids[0]->category_id ) == FALSE) {
                foreach($categories as $category) {
                    $category_id = $category->id;
                    $category_name = $category->category_name;
                    $category_url = add_query_arg('category_id', $category_id, get_permalink($categories_url) );
                    $category_url = add_query_arg('category_name', $category, $category_url );
                    echo '<a href="'. $category_url .'">' . $category_name . '</a>';
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

            default: //This will display the registration form
                if($cancellation_status == FALSE) {
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
                            <p>If you still wish to attend this event, you can join the waiting list and you will be assigned a spot as soon as one is available on a first come first serve basis.</p>
                            <p id="register_link-<?php echo $overflow_event_id ?>" class="register-link-footer"><a class="btn" id="a_register_link-<?php echo $overflow_event_id ?>" href="<?php echo espresso_reg_url($overflow_event_id); ?>" title="<?php echo stripslashes_deep($event_name) ?>"><?php _e('Join Waiting List', 'event_espresso'); ?></a></p>
                        <?php } ?>
                    </div>

                        <?php
                    } else {
    ?>
        <div class="event_espresso_form_wrapper">
            <form method="post" action="<?php echo get_permalink( $event_page_id );?>" id="registration_form">
                    <h4>Event Details:</h4>
                    <p><?php echo event_date_display($start_date, get_option('date_format')); ?>
        <?php if ($end_date !== $start_date) : ?>
                    - <?php echo event_date_display($end_date, get_option('date_format')); ?>
        <?php endif; ?>
                    <p><?php echo espresso_event_time($event_id, 'start_time'); ?> - <?php echo espresso_event_time($event_id, 'end_time'); ?></p>
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
            
                    // CTLT: Get user information
                    $user_info = get_userdata(get_current_user_id());
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
                    <p class="event_form_submit" id="event_form_submit-<?php echo $event_id; ?>">
                    <br />
                        <input class="btn" id="event_form_field-<?php echo $event_id; ?>" type="submit" name="Submit" value="<?php _e('Confirm Registration', 'event_espresso'); ?>">
                    </p>
                    
        <?php }
            do_action('action_hook_espresso_social_display_buttons', $event_id);
        ?>

            </form>
        </div>
        
    <?php
                            }
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

        <?php echo '<p>To register: <a id="cwl-login-buttom" href="https://em.ctlt.ubc.ca/wp-login.php?redirect_to=' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']. '"><img class="alignnone size-full wp-image-1187" src="https://em.ctlt.ubc.ca/wp-content/uploads/2013/09/cwl_login.png" alt="" width="83" height="35" /></a></p>';
        ?>
    <?php } ?>
    </div>
    </div>