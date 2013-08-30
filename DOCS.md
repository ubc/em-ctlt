# File Structure Explanation
============================

The documentation for custom_includes.php, custom_functions.php and custom_shortcodes.php can be found in the (Event Espresso documentation)[http://eventespresso.com/wiki/custom-files-addon/]. To access the Event Espresso docs, you will need to have an account with them.

## espresso/gateways/gateways_display.php

This file controls what is displayed for the "Please Choose a Payment Option" section when registering for an event. The changes that were made here are marked by the "CTLT START" and "CTLT END" comment sections. These changes were mainly styling changes such as adding a title.

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
