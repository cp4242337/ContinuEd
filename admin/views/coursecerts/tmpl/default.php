<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<form action="index.php" method="post" name="adminForm"><input
	type="hidden" name="course" value="<?php echo $this->courseid; ?>">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'id' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JText::_( 'Certificate' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->cd_id );
		$published 	= JHTML::_('grid.published',   $row, $i,'tick.png','publish_x.png', 'crct'  );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $k+1; ?></td>
		<td><?php echo $checked; ?></td>
		<td><?php echo $row->crt_name; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
</table>
</div>

<input type="hidden" name="nextnum" value="<?php echo $cq; ?>" /> <input
	type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="coursecert" /></form>
