<?php defined('_JEXEC') or die('Restricted access');
if(!$this->filter_part) $order = JHTML::_('grid.order', $this->items);
else $order = '';


?>

<form action="" method="post" name="adminForm"><input type="hidden"
	name="course" value="<?php echo $this->courseid; ?>">
<table>
	<tr>
		<td align="left" width="100%">Course: <b><?php echo $this->cname; ?></b>
		</td>
		<td nowrap="nowrap" align="right"><?php 
		echo JText::_(' Part:').JHTML::_('select.genericlist',$this->parts,'filter_part','onchange="submitform();"','value','text',$this->filter_part,'filter_part').'<br>';
		?></td>
	</tr>
</table>
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="5"><?php echo JText::_( 'id' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th><?php echo JText::_( 'Question' ); ?></th>
			<th width="100"><?php echo JText::_( 'Order/#' ).$order; ?></th>
			<th width="50"><?php echo JText::_( 'Published' ); ?></th>
			<th width="50"><?php echo JText::_( 'Part' ); ?></th>
			<th width="100"><?php echo JText::_( 'Type' ); ?></th>
			<th width="75"><?php echo JText::_( 'Category' ); ?></th>
			<th width="50"><?php echo JText::_( 'Required' ); ?></th>
			<th width="50"><?php echo JText::_( 'Answers' ); ?></th>
			<th width="50"><?php echo JText::_( 'Responses' ); ?></th>
			<th width="75"><?php echo JText::_( 'Added By' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$published 	= JHTML::_('grid.published',   $row, $i );
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=question&task=edit&course='.$this->courseid.'&cid[]='. $row->id );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->id; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->qtext; ?></a></td>
		<td class="order"><span><?php echo $this->pagination->orderUpIcon( $i, true,'orderup','Move Up',!($this->filter_part)); ?></span>
		<span><?php echo $this->pagination->orderDownIcon( $i, $n, true,'orderdown','Move Down',!($this->filter_part)); ?></span><input
			size="3" type="text" name="order[]" style="text-align: center;"
			value="<?php echo $row->ordering; ?>"
			<?php if($this->filter_part) echo 'disabled="true"'; ?> /></td>
		<td><?php echo $published; ?></td>
		<td><?php echo $row->qsec; ?></td>
		<td><?php 
		if ($row->qtype == 'multi') echo 'Radio Select';
		if ($row->qtype == 'textbox') echo 'Text Field';
		if ($row->qtype == 'cbox') echo 'Checkbox';
		if ($row->qtype == 'mcbox') echo 'Multi Checkbox';
		if ($row->qtype == 'textar') echo 'Text Box';
		if ($row->qtype == 'yesno') echo 'Yes/No Select';
		if ($row->qtype == 'dropdown') echo 'Drop Down';
		if ($row->qtype == 'qanda') echo 'Q and A';

		?></td>
		<td><?php 
		if ($row->qcat== 'eval') echo 'Evaulation';
		if ($row->qcat== 'assess') echo 'Assessment';
		if ($row->qcat == 'qanda') echo 'Q and A';

		?></td>
		<td><?php 
		if ($row->qreq) echo '<span style="color:#008000">Yes</span>';
		else echo '<span style="color:#800000">No</span>';

		?></td>
		<td><?php if ($row->qtype == 'multi' || $row->qtype == 'mcbox' || $row->qtype == 'dropdown') { ?><a
			href="index.php?option=com_continued&view=answers&area=<?php echo $this->area; ?>&course=<?php echo $this->courseid; ?>&question=<?php echo $row->id; ?>"><img
			src="../includes/js/ThemeOffice/edit.png" alt="Edit Answers"
			border="0"><?php
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_questions_opts WHERE question="'.$row->id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']';


		}
		?></a></td>
		<td><?php if ($this->area != 'qanda') { ?><a
			href="index.php?option=com_continued&view=ansquest&area=<?php echo $this->area; ?>&course=<?php echo $this->courseid; ?>&question=<?php echo $row->id; ?>"><img
			src="../includes/js/ThemeOffice/query.png" alt="View Responses"
			border="0"> [View]</a><?php } ?></td>
		<td><?php echo $row->username; ?></td>
	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->ordering+1;
	}
	?>
	<tfoot>
		<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>
</table>

</div>

<input type="hidden" name="nextnum" value="<?php echo $cq; ?>" /> <input
	type="hidden" name="area" value="<?php echo $this->area; ?>" /> <input
	type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="question" /></form>
