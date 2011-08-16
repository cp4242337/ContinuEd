<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
?><tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_CERTIF_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>		
	<th>
		<?php echo JText::_('COM_CONTINUED_CERTIF_HEADING_CERTTYPE'); ?>
	</th>		
	<th>
		<?php echo JText::_('COM_CONTINUED_CERTIF_HEADING_PROVIDER'); ?>
	</th>	
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
</tr>

