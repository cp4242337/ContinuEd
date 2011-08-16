<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?><tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_PROV_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JHtml::_('grid.sort','COM_CONTINUED_PROV_HEADING_NAME','p.prov_name', $listDirn, $listOrder); ?>
	</th>		
	<th>
		<?php echo JText::_('COM_CONTINUED_PROV_HEADING_LOGO'); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
</tr>

