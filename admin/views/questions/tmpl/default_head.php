<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'q.ordering';
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_QUESTION_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_CONTINUED_QUESTION_HEADING_TITLE'); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
	<th width="100">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'q.ordering', $listDirn, $listOrder); ?>
		<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'questions.saveorder'); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_PART' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_TYPE' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_CAT' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_QGROUP' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_REQUIRED' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_ANSWERS' ); ?>
	</th>
	<th width="130">
		<?php echo JText::_( 'COM_CONTINUED_QUESTION_HEADING_ADDEDBY' ); ?>
	</th>
				


</tr>


