<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<form action="index.php" method="post" name="adminForm"><input
	type="hidden" name="course" value="<?php echo $this->courseid; ?>">
<div id="editcell">Only 1 part label for each part will appear in
evaluation.
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'Part' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JText::_( 'Part Name' ); ?></th>
			<th><?php echo JText::_( 'Part Description' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->part_id );
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=part&task=edit&course='.$this->courseid.'&cid[]='. $row->part_id );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->part_part; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->part_name; ?></a>
		</td>
		<td><?php echo $row->part_desc; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="area" value="<?php echo $this->area; ?>" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="part" /></form>
