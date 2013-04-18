<?php if (!defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
do_action('action_hook_espresso_log', __FILE__, 'FILE LOADED', '');	
//Confirmation Page Template
?>

<div class="espresso_payment_overview event-display-boxes ui-widget" >
	<div class="ui-widget-content ui-corner-all">
		<h3 class="event_title" style="border-bottom: 1px solid #aaa; margin-top: 3px; padding-bottom: 5px;">
			<?php _e('Payment Overview', 'event_espresso'); ?>
		</h3>
		<div class="event-data-display" >
	<?php
		if ( $total_cost == 0 ) {
			unset($_SESSION['espresso_session']['id']);
	?>
			<h2><?php echo $fname ?> <?php echo $lname ?>,</h2>
			
			<div class="event-messages ui-state-highlight">
				<p class="instruct">
					<i class="icon-exclamation-sign"></i> 
					<?php _e('Thank you! Your registration is confirmed for', 'event_espresso'); ?>
		    		<b><?php echo stripslashes_deep($event_name) ?></b>
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
				<p class="instruct">
					<i class="icon-exclamation-sign"></i>
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
		
		</div><!-- / .event-data-display -->
	</div>
</div><!-- / .event-display-boxes -->
