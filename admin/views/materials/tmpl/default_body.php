<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'm.ordering';
	$ordering	= ($listOrder == 'm.ordering');
	$db =& JFactory::getDBO();
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->mat_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->mat_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=material.edit&mat_id='.(int) $item->mat_id); ?>">
				<?php echo $item->mat_title; ?></a>
		</td>
		<?php
			if (ContinuEdHelper::getConfig()->mams) {
				echo '<td>';
				echo '<a href="index.php?option=com_continued&view=matdloads&filter_material='.$item->mat_id.'">Downloads ';
				$query = 'SELECT count(*) FROM #__ce_matdl WHERE published >= 1 && md_mat="'.$item->mat_id.'"';
				$db->setQuery( $query );
				$num_md=$db->loadResult();
				echo ' ['.$num_md.']</a><br />';
				echo '</td>';
			}
		
		?>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'materials.', true);?>
		</td>
        <td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->mat_course == @$this->items[$i-1]->mat_course), 'materials.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->mat_course == @$this->items[$i+1]->mat_course), 'materials.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->mat_course == @$this->items[$i-1]->mat_course), 'materials.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->mat_course == @$this->items[$i+1]->mat_course), 'materials.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

		</td>
		<td>
			<?php 
			switch ($item->mat_type) {
				case "text": echo "Text\HTML"; break;
				case "articulate": echo "Articulate"; break;
				case "accordent": echo "Accordent"; break;
			}
			?>
		</td>
	</tr>
<?php endforeach; ?>


