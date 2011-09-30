<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->gc_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->gc_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=groupcert.edit&gc_id='.(int) $item->gc_id); ?>">
				<?php echo $this->escape($item->crt_name); ?></a>
		</td>
		<td>
			<?php echo $item->ug_name; ?>
		</td>
	</tr>
<?php endforeach; ?>

