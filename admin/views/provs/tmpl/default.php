<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<form action="index.php" method="post" name="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'id' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JText::_( 'Provider Name' ); ?></th>
			<th><?php echo JText::_( 'Provider Logo Image' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->pid,'','lid' );
		$published 	= JHTML::_('grid.published',   $row, $i,'tick.png','publish_x.png', 'crct'  );
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=prov&task=edit&question='.$this->questionid.'&lid[]='. $row->pid );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->pid; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->pname; ?></a></td>
		<td><?php echo $row->plogo; ?></td>

	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input
	type="hidden" name="view" value="prov" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="prov" /></form>
