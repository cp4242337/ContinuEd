<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'o.ordering';
	$ordering	= ($listOrder == 'o.ordering');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->opt_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->opt_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=uopt.edit&opt_id='.(int) $item->opt_id); ?>">
				<?php echo $this->escape($item->opt_text); ?></a>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'uopts.', true);?>
		</td>
		<td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->opt_field == @$this->items[$i-1]->opt_field), 'uopts.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->opt_field == @$this->items[$i+1]->opt_field), 'uopts.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->opt_field == @$this->items[$i-1]->opt_field), 'uopts.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->opt_field == @$this->items[$i+1]->opt_field), 'uopts.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

		</td>
	
	</tr>
<?php endforeach; ?>