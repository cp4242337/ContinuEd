<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<tr>
	<th width="5">
		<?php echo JHtml::_('grid.sort', 'COM_CONTINUED_USER_HEADING_ID', 'u.id', $listDirn, $listOrder); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JHtml::_('grid.sort', 'COM_CONTINUED_USER_HEADING_USERNAME', 'u.username', $listDirn, $listOrder); ?>
	</th>	
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_USERSNAME' , 'u.name', $listDirn, $listOrder); ?>
	</th>
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_EMAIL' , 'u.email', $listDirn, $listOrder); ?>
	</th>
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_GROUP' , 'g.ug_name', $listDirn, $listOrder); ?>
	</th>
	<th class="nowrap" width="5%">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_ENABLED', 'u.block', $listDirn, $listOrder); ?>
	</th>
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_VISIT' , 'u.lastvisitDate', $listDirn, $listOrder); ?>
	</th>
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_UPDATE' , 'ug.lastUpdate', $listDirn, $listOrder); ?>
	</th>
	<th width="150">
		<?php echo JHtml::_('grid.sort',  'COM_CONTINUED_USER_HEADING_REGISTERED' , 'u.registerDate', $listDirn, $listOrder); ?>
	</th>
</tr>


