<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<table>
	<tr>
		<td align="left" width="100%">Question: <b><?php echo $this->ques; ?></b>
		</td>
		<td nowrap="nowrap" align="right">Course: <b><?php echo $this->cname; ?></b>
		</td>
	</tr>
</table>
<form action="" method="post" name="adminForm"><input type="hidden"
	name="question" value="<?php echo $this->questionid; ?>"> <input
	type="hidden" name="course" value="<?php echo $this->courseid; ?>">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'id' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JText::_( 'Option Text' ); ?></th>
			<th><?php echo JText::_( 'Explination' ); ?></th>
			<th width="50"><?php echo JText::_( 'Correct' ); ?></th>
			<th width="70"><?php echo JText::_( 'Order' ).$order; ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$published 	= JHTML::_('grid.published',   $row, $i,'tick.png','publish_x.png', 'crct'  );
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=answer&task=edit&course='.$this->courseid.'&area='.JRequest::getVar('area').'&question='.$this->questionid.'&cid[]='. $row->id );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->id; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->opttxt; ?></a></td>
		<td><?php echo $row->optexpl; ?></td>
		<td><?php echo $published; ?></td>
		<td class="order"><span><?php echo $this->pagination->orderUpIcon( $i, true,'orderup','Move Up',true); ?></span>
		<span><?php echo $this->pagination->orderDownIcon( $i, $n, true,'orderdown','Move Down',true); ?></span><input
			size="3" type="text" name="order[]" style="text-align: center;"
			value="<?php echo $row->ordering; ?>"
			<?php if($this->filter_part) echo 'disabled="true"'; ?> /></td>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->ordering+1;
	}
	?>
	<tfoot>
		<td colspan="6"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>

</table>
</div>

<input type="hidden" name="nextnum" value="<?php echo $cq; ?>" /> <input
	type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="answer" /></form>
