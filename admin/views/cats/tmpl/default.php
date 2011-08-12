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
			<th><?php echo JText::_( 'Category Name' ); ?></th>
			<th><?php echo JText::_( 'Validity' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->cid,'','lid' );
		$published 	= JHTML::_('grid.published',   $row, $i,'tick.png','publish_x.png', 'crct'  );
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=cat&task=edit&question='.$this->questionid.'&lid[]='. $row->cid );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->cid; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->catname; ?></a></td>
		<td><?php 
		if ($row->cat_start != '0000-00-00') echo 'Begin: '.date("m.d.y",strtotime($row->cat_start)).' End: '.date("m.d.y",strtotime($row->cat_end ));
		?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="cat" /></form>
