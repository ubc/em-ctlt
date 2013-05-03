<?php if (!defined('EVENT_ESPRESSO_VERSION')) { exit('No direct script access allowed'); }
do_action('action_hook_espresso_log', __FILE__, 'FILE LOADED', '');
?>
<div class="espresso_payment_overview event-display-boxes ui-widget" >
	<div class="ui-widget-content ui-corner-all" >
		<h3 class="event_title" style="border-bottom: 1px solid #aaa; margin-top: 3px; padding-bottom: 5px;">
			<?php _e('Payment Overview', 'event_espresso'); ?>
		</h3>
		<div class="event-data-display">
			<table>
				<tr>
					<td><strong><?php _e('Class/Event:', 'event_espresso'); ?></strong></td>
					<td><?php echo stripslashes_deep($event_link) ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Primary Registrant:', 'event_espresso'); ?></strong></td>
					<td><?php echo stripslashes_deep($fname . ' ' . $lname) ?></td>
				</tr>
				<tr>
					<?php echo $txn_type == '' ? '' : '<td><strong>' . __('Payment Type:', 'event_espresso') . '</strong></td> <td>' . espresso_payment_type($txn_type) . '</td>'; ?> <?php echo ($payment_date == '' || ($payment_status == 'Pending' && (espresso_payment_type($txn_type) == 'Invoice' || espresso_payment_type($txn_type) == 'Offline payment'))) ? '' : '<tr><td><strong>' . __('Payment Date:', 'event_espresso') . '</strong></td> <td>' . event_date_display($payment_date) . '</td></tr>'; ?>
				</tr>
				<tr>
					<td><strong><?php _e('Amount Paid/Owed:', 'event_espresso'); ?></strong></td>
					<td><?php echo $org_options['currency_symbol'] ?><?php echo $total_cost ?>
						<?php event_espresso_paid_status_icon($payment_status) ?>
					</td>
				</tr>
				<tr>
					<td><strong><?php _e('Payment Status:', 'event_espresso'); ?></strong></td>
					<td><?php echo $payment_status ?></td>
				</tr>
				<tr>
					<td><strong><?php _e('Registration ID:', 'event_espresso'); ?></strong></td>
					<td><?php echo $registration_id ?></td>
				</tr>
				<tr>
					<?php
					echo $txn_id == '' ? '' : '<td>' . __('Transaction ID:', 'event_espresso') . '</td> <td>' . $txn_id . '</td>';
					?>
				</tr>
			</table>
		</div>
	</div>
</div><!-- / .event-display-boxes -->

