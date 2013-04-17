em-ctlt
=======

events

* IMPORTANT: THIS IS NOT A PLUGIN OR THEME REPO. 
* This is only to work on template customization for Events Espresso. (see https://eventespresso.com/wiki/put-custom-templates/)

== changelog ==

v0.11 - added the template file for the shopping cart
      - minor fix in the event_registration_display.php file

v0.10.1 - fixed an issue with single series page only displaying the first upcoming event in the series

v0.10 - changed the styling of the event listing for all the event views

v0.9.1 - added ability to show ONLY past events in the event_list view

v0.9 - added parameters to the shortcode to show past or upcoming events
     - updated shortcode usage in the custom_shortcodes.php file
     - updated info for all commited files
     - the ctlt_espresso_category_* php files will now parse the parameter in the shortcode and determine whether to show past or upcoming events

v0.81 - fixed the address layout issue when no venue or address is provided
      - fixed a bug where the proceeding page was not being loaded when clicking on a series name
      - fixed a bug where past events were showing up in the current events section for series
      - recurring events now only show the first upcoming one in the series view

v0.8 - added multi-event registration to the series view and events view
     - added a paired file to ctlt_espresso_category_registration.php
     - paired filename is ctlt_espresso_category_registration_display.php

v0.7 - created singular series page that shows all the upcoming events under that series

v0.6 - created the series page to better reflect the old events site

v0.5 - created shortcode for viewing by category

v0.4 - added facilitators to the registration page

v0.3 - changed the registration page to a more similar format to the old events site

v0.2 - changed the single events view to a table format

v0.1 - initial commit
