<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->cat_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->cat_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=cat.edit&cat_id='.(int) $item->cat_id); ?>">
				<?php echo $this->escape($item->cat_name); ?></a>
		</td>
		<td>
			<?php echo $item->cat_start.' - '.$item->cat_end; ?>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'cats.', true);?>
		</td>
		
	
	</tr>
<?php endforeach; ?>

