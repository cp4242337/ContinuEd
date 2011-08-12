<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->ug_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->ug_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=ugroup.edit&ug_id='.(int) $item->ug_id); ?>">
				<?php echo $this->escape($item->ug_name); ?></a>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'ugroups.', true);?>
		</td>
		<td>
			<?php echo $item->access_level; ?>
		</td>
		
	
	</tr>
<?php endforeach; ?>

