<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->purchase_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->purchase_id); ?>
		</td>
		<td>
				<?php echo $item->course_name; ?>
		</td>
		<td>
			<?php echo $item->user_name.' ('.$item->username.')'; ?>
		</td>
		<td>
			<?php echo $item->user_email; ?>
		</td>
		<td>
			<?php 
			switch ($item->purchase_type) {
				case "paypal": echo "PayPal"; break;
				case "redeem": echo "Code"; break;
				case "admin": echo "Admin"; break;
				case "google": echo "Google"; break;
			}
			?>
		</td>
		<td>
			<?php echo $item->purchase_transid; ?>
		</td>
		<td>
			<?php echo $item->purchase_time; ?>
		</td>
		<td>
			<?php echo $item->purchase_ip; ?>
		</td>
		<td>
			<?php 
			
			switch ($item->purchase_status) {
				
				case "notyetstarted": echo "Not Yet Started"; break;
				case "verified": echo "Assessment"; break;
				case "canceled": echo "Canceled"; break;
				case "accepted": echo "Accepted"; break;
				case "pending": echo "Pending"; break;
				case "started": echo "Started"; break;
				case "denied": echo "Denied"; break;
				case "refunded": echo "Refunded"; break;
				case "failed": echo "Failed"; break;
				case "pending": echo "Pending"; break;
				case "reversed": echo "Reversed"; break;
				case "canceled_reversal": echo "Canceled Dispute"; break;
				case "expired": echo "Expired"; break;
				case "voided": echo "Voided"; break;
				case "completed": echo "Completed"; break;
				case "dispute": echo "Dispute"; break;
			}
			
			?>
		</td>
	
	</tr>
<?php endforeach; ?>


