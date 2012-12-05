<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'm.ordering';
?>
<tr>
	<th width="5">
		<?php echo JText::_('COM_CONTINUED_MATERIAL_HEADING_ID'); ?>
	</th>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" />
	</th>			
	<th>
		<?php echo JText::_('COM_CONTINUED_MATERIAL_HEADING_TITLE'); ?>
	</th>	
	<?php
	if (ContinuEdHelper::getConfig()->mams) {
		echo '<th>'.JText::_('COM_CONTINUED_MATERIAL_HEADING_DLOADS').'</th>';
	}
	
	?>
	<th width="100">
		<?php echo JText::_('JPUBLISHED'); ?>
	</th>	
	<th width="100">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'm.ordering', $listDirn, $listOrder); ?>
		<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'materials.saveorder'); ?>
	</th>
	<th width="75">
		<?php echo JText::_( 'COM_CONTINUED_MATERIAL_HEADING_TYPE' ); ?>
	</th>
				


</tr>


