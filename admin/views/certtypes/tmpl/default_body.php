<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->crt_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->crt_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=certtype.edit&crt_id='.(int) $item->crt_id); ?>">
				<?php echo $this->escape($item->crt_name); ?></a>
		</td>
	
	</tr>
<?php endforeach; ?>

