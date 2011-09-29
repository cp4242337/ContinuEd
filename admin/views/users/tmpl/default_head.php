<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_USER_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_CONTINUED_USER_HEADING_USERNAME'); ?>
	</th>	
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_USERSNAME' ); ?>
	</th>
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_EMAIL' ); ?>
	</th>
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_GROUP' ); ?>
	</th>
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_VISIT' ); ?>
	</th>
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_UPDATE' ); ?>
	</th>
	<th width="150">
		<?php echo JText::_( 'COM_CONTINUED_USER_HEADING_REGISTERED' ); ?>
	</th>
</tr>


