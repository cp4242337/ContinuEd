<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->part_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->part_id); ?>
		</td>
		<td>
			<?php echo $item->part_part; ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=part.edit&part_id='.(int) $item->part_id); ?>">
				<?php echo $this->escape($item->part_name); ?></a>
		</td>
	</tr>
<?php endforeach; ?>

