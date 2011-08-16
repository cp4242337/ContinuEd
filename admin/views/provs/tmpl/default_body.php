<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->prov_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->prov_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=prov.edit&prov_id='.(int) $item->prov_id); ?>">
				<?php echo $this->escape($item->prov_name); ?></a>
		</td>
		<td>
			<?php echo $item->prov_logo; ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'provs.', true);?>
		</td>
		
	
	</tr>
<?php endforeach; ?>

