<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_PURCHASE_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_CONTINUED_PURCHASE_HEADING_COURSE'); ?>
	</th>	
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_TYPE' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_TRANSINFO' ); ?>
	</th>
	<th width="100">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_USER' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_TIME' ); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_IP' ); ?>
	</th>
	<th width="50">
		<?php echo JText::_( 'COM_CONTINUED_PURCHASE_HEADING_STATUS' ); ?>
	</th>
				


</tr>


