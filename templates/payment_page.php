<?php if (!defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
do_action('action_hook_espresso_log', __FILE__, 'FILE LOADED', '');	
//Confirmation Page Template
?>
  <h3>
		<?php _e('Registration Confirmation', 'event_espresso'); ?>
  </h3>
<?php
	if ( $total_cost == 0 ) {
		unset($_SESSION['espresso_session']['id']);
?>		
		<div class="event-messages ui-state-highlight">
			<span class="ui-icon ui-icon-alert"></span>	  
			<p class="instruct">
				<?php _e('Thank you, ' . $fname . ' ' . $lname . '! Your registration is confirmed for', 'event_espresso');
                 ?>
	    			<b><a href="<?php echo $event_url; ?>"><?php echo stripslashes_deep($event_name) ?></a></b>
			</p>
		</div>
	  	<p>
			<span class="section-title"><?php _e('Your Registration ID: ', 'event_espresso'); ?></span> <?php echo $registration_id ?>
	 	</p>
	  	<p class="instruct">
			<?php _e('A confirmation email has been sent with additional details of your registration.', 'event_espresso'); ?>
	  	</p>

<?php }else{ ?>

		<h2><?php echo $fname ?> <?php echo $lname ?>,</h2>
	  
		<div class="event-messages ui-state-highlight">
			<span class="ui-icon ui-icon-alert"></span>
			<p class="instruct">
				<?php _e('Your registration is not complete until payment is received.', 'event_espresso'); ?>
			</p>
		</div>
		
	  	<p>
			<span class="event_espresso_name section-title"><?php _e('Amount due: ', 'event_espresso'); ?></span> 
			<span class="event_espresso_value"><?php echo isset($org_options['currency_symbol']) ? $org_options['currency_symbol'] : ''; ?><?php echo number_format($total_cost,2); ?></span>
		</p>
	  	
		<p>
			<span class="section-title"><?php _e('Your Registration ID: ', 'event_espresso'); ?></span><?php echo $registration_id ?>
		</p>
		
	  	<p>
			<?php echo $org_options['email_before_payment'] == 'Y' ? __('A confirmation email has been sent with additional details of your registration.', 'event_espresso') : ''; ?>
		</p>

<?php
}
?>