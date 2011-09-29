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
			<?php echo $item->id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=user.edit&id='.(int) $item->id); ?>">
				<?php echo $item->username; ?></a>
		</td>
		<td class="center">
			<?php echo $item->name; ?>
		</td>
		<td class="center">
			<?php echo $item->email; ?>
		</td>
		<td class="center">
			<?php echo $item->ug_name; ?>
		</td>
		<td class="center">
			<?php echo $item->lastvisitDate; ?>
		</td>
		<td class="center">
			<?php echo $item->lastUpdate; ?>
		</td>
		<td class="center">
			<?php echo $item->registerDate; ?>
		</td>
        
	</tr>
<?php endforeach; ?>


