<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'q.ordering';
	$ordering	= ($listOrder == 'q.ordering');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->q_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->q_id); ?>
		</td>
		<td>
				<a href="<?php echo JRoute::_('index.php?option=com_continued&task=question.edit&q_id='.(int) $item->q_id); ?>">
				<?php echo $item->q_text; ?></a>
		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'questions.', true);?>
		</td>
        <td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->q_course == @$this->items[$i-1]->q_course), 'questions.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->q_course == @$this->items[$i+1]->q_course), 'questions.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->q_course == @$this->items[$i-1]->q_course), 'questions.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->q_course == @$this->items[$i+1]->q_course), 'questions.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

		</td>
		<td>
			<?php echo $item->q_part; ?>
		</td>
		<td>
			<?php 
			switch ($item->q_type) {
				case "textar": echo "Text Box"; break;
				case "textbox": echo "Text Field"; break;
				case "multi": echo "Radio Select"; break;
				case "cbox": echo "Check Box"; break;
				case "mcbox": echo "Multi Checkbox"; break;
				case "yesno": echo "Yes / No"; break;
				case "dropdown": echo "Drop Down"; break;
				case "message": echo "Message"; break;
			}
			?>
		</td>
		<td>
			<?php echo $item->q_cat; ?>
		</td>
		<td>
			<?php 
			if ($item->q_req) echo '<span style="color:#008800">Yes</span>';
			else echo '<span style="color:#880000">No</span>'; 
			?>
		</td>
		<td>
		<?php 
			if ($item->q_type=='multi' || $item->q_type=='mcbox' || $item->q_type=='dropdown') {
				echo '<a href="'.JRoute::_('index.php?option=com_continued&view=answers&filter_question='.$item->q_id).'">Answers'; 
				$db =& JFactory::getDBO();
				$query = 'SELECT count(*) FROM #__ce_questions_opts WHERE opt_question="'.$item->q_id.'"';
				$db->setQuery( $query );
				echo ' ['.$db->loadResult().']</a>'; 
			}
		
		?>
		</td>
		<td>
			<?php echo $item->username; ?>
		</td>
	
	</tr>
<?php endforeach; ?>


