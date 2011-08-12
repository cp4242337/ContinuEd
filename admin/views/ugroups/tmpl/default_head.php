<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
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
	<th width="100">
		<?php echo JHtml::_('grid.sort','JGRID_HEADING_ACCESS','ug.access', $listDirn, $listOrder); ?>
	</th>
</tr>

