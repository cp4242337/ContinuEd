<?php defined('_JEXEC') or die('Restricted access');
$order = JHTML::_('grid.order', $this->items);
?>
<form action="" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%"></td>
		<td nowrap="nowrap" align="right"><?php
		echo JText::_('Category:').JHTML::_('select.genericlist',$this->cats,'filter_cat','onchange="submitform();"','cid','catname',$this->filter_cat,'filter_cat');
		echo JText::_(' Provider:').JHTML::_('select.genericlist',$this->provs,'filter_prov','onchange="submitform();"','pid','pname',$this->filter_prov,'filter_prov');
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
			<th><?php echo JText::_( 'Course Title<br>Faculty' ); ?></th>
			<th width="70"><?php echo JText::_( 'Order' ).$order; ?></th>
			<th width="50"><?php echo JText::_( 'Added' ); ?></th>
			<th width="50"><?php echo JText::_( 'Avail. Certs.' ); ?></th>
			<th width="80"><?php echo JText::_( 'PreTest<br />Questions' ); ?></th>
			<th width="80"><?php echo JText::_( 'Inter<br />Questions' ); ?></th>
			<th width="80"><?php echo JText::_( 'PostTest<br />Questions' ); ?></th>
			<th width="5"><?php echo JText::_( 'Pub?' ); ?></th>
			<th width="60"><?php echo JText::_( 'Access' ); ?></th>
			<th width="60"><?php echo JText::_( 'Valid' ); ?></th>

			<th width="100"><?php echo JText::_( 'Provider' ); ?></th>
			<th width="100"><?php echo JText::_( 'Category' ); ?></th>
			<th width="100"><?php echo JText::_( 'Report' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$n=count( $this->items );
	for ($i=0;  $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
		$published 	= JHTML::_('grid.published',   $row, $i  );
		
		$link 		= JRoute::_( 'index.php?option=com_continued&controller=course&task=edit&cid[]='. $row->id );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->id; ?></td>
		<td><?php echo $checked; ?></td>
		<td><a href="<?php echo $link; ?>"><?php echo $row->cname.'</a><br />'.$row->faculty; ?></td>
		<td class="order"><span><?php echo $this->pagination->orderUpIcon( $i, ($row->ccat == @$this->items[$i-1]->ccat),'orderup','Move Up',!($this->filter_prov)); ?></span>
		<span><?php echo $this->pagination->orderDownIcon( $i, $n, ($row->ccat == @$this->items[$i+1]->ccat),'orderdown','Move Down',!($this->filter_prov)); ?></span><input
			size="3" type="text" name="order[]" style="text-align: center;"
			value="<?php echo $row->ordering; ?>"
			<?php if($this->filter_prov) echo 'disabled="true"'; ?> /></td>
		<td><?php echo date("m.d.y",strtotime($row->dateadded)); ?></td>
		<td><?php 
		if ($row->haseval && $row->hascertif) {
			echo '<a href="index.php?option=com_continued&view=coursecerts&course='.$row->id.'">';
			echo '<img src="../includes/js/ThemeOffice/edit.png" alt="Edit Available Certificates" border="0">';
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_coursecerts WHERE course_id="'.$row->id.'"';
			$db->setQuery( $query );
			echo ' ['.$db->loadResult().']</a>';
		}
		?></td>
		<td><?php 
		if ($row->course_haspre) {
			echo '<a href="index.php?option=com_continued&view=questions&area=pre&course='.$row->id.'">Questions';
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_questions WHERE course="'.$row->id.'" && q_area="pre"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
			echo '<a href="index.php?option=com_continued&view=parts&area=pre&course='.$row->id.'">Parts';
			echo ' ['.$row->course_preparts.']</a>';
		}
		?></td>
		<td><?php 
		if ($row->course_hasinter) {
			echo '<a href="index.php?option=com_continued&view=questions&area=inter&course='.$row->id.'">Inter';
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_questions WHERE course="'.$row->id.'" && q_area="inter"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
		}
		if ($row->course_qanda != 'none') {
			echo '<a href="index.php?option=com_continued&view=questions&area=qanda&course='.$row->id.'">Q an A';
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_questions WHERE course="'.$row->id.'" && q_area="qanda"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a>';
		}
		?></td>
		<td><?php 
		if ($row->haseval) {
			echo '<a href="index.php?option=com_continued&view=questions&area=post&course='.$row->id.'">Questions';
			$db =& JFactory::getDBO();
			$query = 'SELECT count(*) FROM #__ce_questions WHERE course="'.$row->id.'" && q_area="post"';
			$db->setQuery( $query );
			$numq=$db->loadResult();
			echo ' ['.$numq.']</a><br />';
			echo '<a href="index.php?option=com_continued&view=parts&area=post&course='.$row->id.'">Parts';
			echo ' ['.$row->evalparts.']</a>';
		}
		?></td>
		<td><?php echo $published; ?></td>
		<td align="center"><?php echo $row->access_level; ?></td>
		<td><?php 
		if ($row->startdate != '0000-00-00 00:00:00') echo 'B: '.date("m.d.y",strtotime($row->startdate)).'<br />E: '.date("m.d.y",strtotime($row->enddate));
		?></td>
		<td><?php echo $row->pname; ?></td>
		<td><?php 
		if (strlen($row->catname) > 25) echo substr($row->catname,0,22).'...';
		else echo $row->catname;

		?></td>
		<td><?php 
		if ($row->haseval || $row->course_haspre) {
			/*for ($j=1; $j<=$numq; $j=$j+5) {
			 echo '<a href="index.php?option=com_continued&view=coursereport&course='.$row->id.'&stnum='.$j.'">Q'.$j.'</a>';
			 if (($j + 5)<=$numq) echo '-';
			 }*/
			if ($row->course_haspre) {
				echo 'Pre: <a href="index.php?option=com_continued&view=coursereport&area=pre&course='.$row->id.'">Ans</a>';
				echo ' - <a href="index.php?option=com_continued&view=tally&area=pre&course='.$row->id.'">Tally</a><br />';
			}
			if ($row->course_hasinter) {
				echo 'Inter: <a href="index.php?option=com_continued&view=coursereport&area=inter&course='.$row->id.'">Ans</a>';
				echo ' - <a href="index.php?option=com_continued&view=tally&area=inter&course='.$row->id.'">Tally</a><br />';
			}
			if ($row->haseval) {
				echo 'Post: <a href="index.php?option=com_continued&view=coursereport&area=post&course='.$row->id.'">Ans</a>';
				echo ' - <a href="index.php?option=com_continued&view=tally&area=post&course='.$row->id.'">Tally</a>';
			}
		}
		?></td>
	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<td colspan="16"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="course" /></form>
