<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$cecfg=ContinuEdHelper::getConfig();
	$user		= JFactory::getUser();
	$userId		= $user->get('id');
?>
<?php foreach($this->items as $i => $item): 
	$listOrder	= $this->escape($this->state->get('list.ordering'));
	$listDirn	= $this->escape($this->state->get('list.direction'));
	$saveOrder	= $listOrder == 'c.ordering';
	$ordering	= ($listOrder == 'c.ordering');
	$canCreate	= $user->authorise('core.create',		'com_continued');
	$canEdit	= $user->authorise('core.edit',			'com_continued');
	$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
	$canChange	= $user->authorise('core.edit.state',	'com_continued') && $canCheckin;
	$db =& JFactory::getDBO();
	?>
	
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $item->course_id; ?></td>
		<td><?php echo JHtml::_('grid.id', $i, $item->course_id); ?></td>
		<td>
				<?php if ($item->checked_out) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'courses.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_continued&task=course.edit&course_id='.(int) $item->course_id); ?>">
						<?php echo $this->escape($item->course_name); ?></a>
				<?php else : ?>
						<?php echo $this->escape($item->course_name); ?>
				<?php endif; ?>
						<?php echo '<br />'.$item->course_faculty; ?></td>
        <td><?php 
		if ($item->course_startdate != '0000-00-00 00:00:00') echo 'B: '.date("m.d.y",strtotime($item->course_startdate)).'<br />E: '.date("m.d.y",strtotime($item->course_enddate));
		?></td>
		<td><?php echo 'Add: '.date("m.d.y",strtotime($item->course_dateadded)); ?></td>
		<td><?php
		echo $item->provider_name; 
		?></td>
		<?php 
		if ($cecfg->mams) {
			echo '<td><a href="index.php?option=com_continued&view=coursecats&filter_course='.$item->course_id.'">Cats ';
			$query = 'SELECT count(*) FROM #__ce_coursecat WHERE cc_course="'.$item->course_id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']</a>';
			echo '<br /><a href="index.php?option=com_continued&view=courseauths&filter_course='.$item->course_id.'">Authors ';
			$query = 'SELECT count(*) FROM #__ce_courseauth WHERE ca_course="'.$item->course_id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']</a></td>';
		}
		?>
		<td><?php 
		if ($item->course_prereq) {
			
			echo '<a href="index.php?option=com_continued&view=prereqs&filter_course='.$item->course_id.'">PreReqs ';
			$query = 'SELECT count(*) FROM #__ce_prereqs WHERE pr_course="'.$item->course_id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']</a><br />';
		}
		if ($item->course_hasmat) {
			
			echo '<a href="index.php?option=com_continued&view=materials&filter_course='.$item->course_id.'">Material ';
			$query = 'SELECT count(*) FROM #__ce_material WHERE mat_course="'.$item->course_id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']</a>';
			
		}
		?></td>
		<td><?php 
		if ($item->course_haspre) {
			echo '<a href="index.php?option=com_continued&view=questions&filter_area=pre&filter_course='.$item->course_id.'">Pre';
			$query = 'SELECT count(*) FROM #__ce_questions WHERE q_course="'.$item->course_id.'" && q_area="pre"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
		}
		if ($item->course_hasinter) {
			echo '<a href="index.php?option=com_continued&view=questions&filter_area=inter&filter_course='.$item->course_id.'">Inter';
			$query = 'SELECT count(*) FROM #__ce_questions WHERE q_course="'.$item->course_id.'" && q_area="inter"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
		}
		if ($item->course_qanda != 'none') {
			echo '<a href="index.php?option=com_continued&view=questions&filter_area=qanda&filter_course='.$item->course_id.'">Q an A';
			$query = 'SELECT count(*) FROM #__ce_questions WHERE q_course="'.$item->course_id.'" && q_area="qanda"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
		}
		if ($item->course_haseval) {
			echo '<a href="index.php?option=com_continued&view=questions&filter_area=post&filter_course='.$item->course_id.'">Post';
			$query = 'SELECT count(*) FROM #__ce_questions WHERE q_course="'.$item->course_id.'" && q_area="post"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a>';
		}
		?></td>
		<td><?php 
		if ($item->course_haspre) {
			echo '<a href="index.php?option=com_continued&view=parts&filter_area=pre&filter_course='.$item->course_id.'">Pre';
			echo ' ['.$item->course_preparts.']</a><br />';
		}

		if ($item->course_haseval) {
			echo '<a href="index.php?option=com_continued&view=parts&filter_area=post&filter_course='.$item->course_id.'">Post';
			echo ' ['.$item->course_postparts.']</a>';
		}
		?></td>
		<td class="order">
				<?php if ($saveOrder) :?>
					<?php if ($listDirn == 'asc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->course_cat == @$this->items[$i-1]->course_cat), 'courses.orderup', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->course_cat == @$this->items[$i+1]->course_cat), 'courses.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php elseif ($listDirn == 'desc') : ?>
						<span><?php echo $this->pagination->orderUpIcon($i, ($item->course_cat == @$this->items[$i-1]->course_cat), 'courses.orderdown', 'JLIB_HTML_MOVE_UP', $ordering); ?></span>
						<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, ($item->course_cat == @$this->items[$i+1]->course_cat), 'courses.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering); ?></span>
					<?php endif; ?>
				<?php endif; ?>
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $item->ordering;?>" <?php echo $disabled ?> class="text-area-order" />

		</td>
		<td class="center">
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'courses.', true);?>
		</td>
		<td align="center"><?php echo $item->access_level; ?></td>
		<td><?php 
		if (strlen($item->category_name) > 35) echo substr($item->category_name,0,32).'...';
		else echo $item->category_name;

		?></td>
		<td><?php 
			echo '<a href="index.php?option=com_continued&view=coursereport&cat='.$item->course_cat.'&course='.$item->course_id.'">Activity</a>';
		if ($item->course_haseval || $item->course_haspre || $item->course_hasinter) {
			echo '<br /><a href="index.php?option=com_continued&view=tally&course='.$item->course_id.'">Tally</a>';
		}
		?></td>
		
	
	</tr>
<?php endforeach; ?>


