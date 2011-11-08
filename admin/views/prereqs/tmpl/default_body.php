<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->pr_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->pr_id); ?>
		</td>
		<td>
				<?php echo $this->escape($item->req_course); ?>
		</td>
	</tr>
<?php endforeach; ?>

