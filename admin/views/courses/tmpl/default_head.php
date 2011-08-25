<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'c.ordering';
?>
<tr>
	<th width="5"><?php echo JText::_( 'id' ); ?></th>
	<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
	<th><?php echo JHtml::_('grid.sort',  'Course Title<br />Faculty', 'c.course_name', $listDirn, $listOrder); ?></th>	
	<th width="100">
		<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'c.ordering', $listDirn, $listOrder); ?>
		<?php echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'courses.saveorder'); ?>
	</th>
	<th width="50"><?php echo JText::_( 'Added' ); ?></th>
	<th width="50"><?php echo JText::_( 'Avail. Certs.' ); ?></th>
	<th width="80"><?php echo JText::_( 'PreTest<br />Questions' ); ?></th>
	<th width="80"><?php echo JText::_( 'Inter<br />Questions' ); ?></th>
	<th width="80"><?php echo JText::_( 'PostTest<br />Questions' ); ?></th>
	<th width="5"><?php echo JText::_( 'Pub?' ); ?></th>
	<th width="60"><?php echo JText::_( 'Access' ); ?></th>
	<th width="100"><?php echo JText::_( 'Valid' ); ?></th>

	<th width="100"><?php echo JText::_( 'Provider' ); ?></th>
	<th width="100"><?php echo JText::_( 'Category' ); ?></th>
	<th width="100"><?php echo JText::_( 'Report' ); ?></th>

</tr>


