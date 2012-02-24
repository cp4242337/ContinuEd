<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'ug.ordering';
?><tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_UGROUP_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JHtml::_('grid.sort','COM_CONTINUED_UGROUP_HEADING_NAME','ug.ug_name', $listDirn, $listOrder); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
	<th width="10%">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'ug.ordering', $listDirn, $listOrder); ?>
		<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'ugroups.saveorder'); ?>
	</th>
	<th width="100">
		<?php echo JHtml::_('grid.sort','JGRID_HEADING_ACCESS','ug.access', $listDirn, $listOrder); ?>
	</th>
</tr>

