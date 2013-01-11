<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'q.ordering';
	$user		= JFactory::getUser();
	$userId		= $user->get('id');
?>
<?php 

foreach($this->items as $i => $item): 
	$ordering	= ($listOrder == 'q.ordering');
	$canCreate	= $user->authorise('core.create',		'com_continued');
	$canEdit	= $user->authorise('core.edit',			'com_continued');
	$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
	$canChange	= $user->authorise('core.edit.state',	'com_continued') && $canCheckin;
		
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td>
			<?php echo $item->q_id; ?>
		</td>
		<td>
			<?php echo JHtml::_('grid.id', $i, $item->q_id); ?>
		</td>
		<td>
				<?php if ($item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'weblinks.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_continued&task=question.edit&q_id='.(int) $item->q_id); ?>">
						<?php echo $this->escape($item->q_text); ?></a>
				<?php else : ?>
						<?php echo $this->escape($item->q_text); ?>
				<?php endif; ?>
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
			<?php 
			switch ($item->q_cat) {
				case "eval": echo "Evaluation"; break;
				case "assess": echo "Assessment"; break;
				case "message": echo "Message"; break;
			}
			?>
		</td>
		<td>
			<?php echo $item->qg_name; ?>
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
			<?php echo $item->usersname.'<br />'.$item->username.' ('.$item->userid.')<br />'.$item->q_added; ?>
		</td>
	
	</tr>
<?php endforeach; ?>


