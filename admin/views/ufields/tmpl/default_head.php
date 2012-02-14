<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'f.ordering';
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_UFIELD_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_CONTINUED_UFIELD_HEADING_TITLE'); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
	<th width="100">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'f.ordering', $listDirn, $listOrder); ?>
		<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'ufields.saveorder'); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_TYPE' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_REQUIRED' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_REG' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_PROFILE' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_HIDDEN' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_CHANGE' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_UFIELD_HEADING_OPTIONS' ); ?>
	</th>
</tr>


