em-ctlt
=======

events

* IMPORTANT: THIS IS NOT A PLUGIN OR THEME REPO. 
* This is only to work on template customization for Events Espresso. (see https://eventespresso.com/wiki/put-custom-templates/)

== changelog ==

v.0.17 - changed formatting of registration_display, event_list_display, payment_page, and confirmation_display to better conform to CLF standards

v0.16 - added a past events shortcode to display a list of all the past events inside custom_shortcodes.php
      - added some javascript to handle pagination for the past events (ajax-paging.js)
      - added a function to handle all the script enqueuing in custom_functions.php
      - new files: past_event_list.php and past_event_list_display.php
      - cleaned up some useless lines of code in ctlt_espresso_category_display.php, event_list.php and event_list_display.php

v0.15 - made some changes so that the registration page will still show up when an event is expired but registration is disabled

v0.15 - made major changes to the file stucture
      - added a gateways folder (this folder handles the files regarding payments)
      
v0.14 - added payment page templates and formatted to be more consistent with other pages

v0.13.2 - fixed a ui bug where the add to cart button will still appear for events that are full
        - fixed a bug in the series pages where event waitlist appears with the main events as well

v0.13.1 - fixed a bug where an uncategorized event will not show up in the series view
        - fixed an issue with the series registration page not showing uncategorized events
        - changed the way category name is grabbed for individual events in series view

v0.13 - added multi-registration php files
      - made some UI changes to the event-list PHP files and the registration-page PHP files

v0.12 
      - added confirmation_display.php and payment_page.php to the templates
      - made some minor ui changes to the shopping cart

v0.11 
      - added the template file for the shopping cart
      
      - minor fix in the event_registration_display.php file

v0.10.1 
        - fixed an issue with single series page only displaying the first upcoming event in the series

v0.10 
      - changed the styling of the event listing for all the event views

v0.9.1 
       - added ability to show ONLY past events in the event_list view

v0.9 
     - added parameters to the shortcode to show past or upcoming events
     
     - updated shortcode usage in the custom_shortcodes.php file
     
     - updated info for all commited files
     
     - the ctlt_espresso_category_* php files will now parse the parameter in the shortcode and determine whether to show past or upcoming events

v0.81 
      - fixed the address layout issue when no venue or address is provided
     
      - fixed a bug where the proceeding page was not being loaded when clicking on a series name
     
      - fixed a bug where past events were showing up in the current events section for series
     
      - recurring events now only show the first upcoming one in the series view

v0.8 
     - added multi-event registration to the series view and events view
     
     - added a paired file to ctlt_espresso_category_registration.php
     
     - paired filename is ctlt_espresso_category_registration_display.php

v0.7 
     - created singular series page that shows all the upcoming events under that series

v0.6 
     - created the series page to better reflect the old events site

v0.5 
     - created shortcode for viewing by category

v0.4 
     - added facilitators to the registration page

v0.3 
     - changed the registration page to a more similar format to the old events site

v0.2 
     - changed the single events view to a table format

v0.1 
     - initial commit
