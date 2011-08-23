<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?><tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_CAT_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JHtml::_('grid.sort','COM_CONTINUED_CAT_HEADING_NAME','c.cat_name', $listDirn, $listOrder); ?>
	</th>		
	<th>
		<?php echo JText::_('COM_CONTINUED_CAT_HEADING_TIME'); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
</tr>

