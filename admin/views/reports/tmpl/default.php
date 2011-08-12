<?php defined('_JEXEC') or die('Restricted access');
$pf[0]=JHTML::_('select.option','pass','Pass');
$pf[1]=JHTML::_('select.option','fail','Fail');
$pf[2]=JHTML::_('select.option','','Both');
global $cecfg;
?>
<form action="" method="post" name="adminForm">

<table>
	<tr>
		<td align="left" width="100%"></td>
		<td nowrap="nowrap" align="right"><?php
		echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
		echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"').'<br />';
		echo JText::_('Category:').JHTML::_('select.genericlist',$this->cats,'cat','onchange="submitform();"','cid','catname',$this->cat,'cat');
		echo JText::_(' Provider:').JHTML::_('select.genericlist',$this->provs,'prov','onchange="submitform();"','pid','pname',$this->prov,'prov');
		echo JText::_(' Pass/Fail:').JHTML::_('select.genericlist',$pf,'pf','onchange="submitform();"','value','text',$this->pf,'pf').'<br>';
		echo JText::_(' Course:').JHTML::_('select.genericlist',$this->courses,'course','onchange="submitform();"','id','cname',$this->course,'course');
		?></td>
	</tr>
</table>

<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th width="20"><?php echo JText::_( '#' ); ?></th>
			<th width="20"><input type="checkbox" name="toggle" value=""
				onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
			<th width="100"><?php echo JText::_( 'Name' ); ?></th>
			<th width="80"><?php echo JText::_( 'When' ); ?></th>
			<?php if ($cecfg['NEEDS_DEGREE']) echo '<th width="40">'.JText::_( 'Degr' ).'</th>'; ?>
			<th><?php echo JText::_( 'Course' ); ?></th>
			<th width="100"><?php echo JText::_( 'Provider' ); ?></th>
			<th width="75"><?php echo JText::_( 'Category' ); ?></th>
			<th width="30"><?php echo JText::_( 'Pre %' ).$order; ?></th>
			<th width="30"><?php echo JText::_( 'Post %' ).$order; ?></th>
			<th width="30"><?php echo JText::_( 'P/F' ); ?></th>
			<th width="75"><?php echo JText::_( 'Details' ).$order; ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->fid );

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td align="right"><?php echo ($i + $this->pageNav->limitstart + 1); ?>
		</td>
		<td><?php echo $checked; ?></td>
		<td><?php echo $row->fullname; ?></td>
		<td><?php echo date("m.d.y H:i",strtotime($row->ctime)); ?></td>
		<?php if ($cecfg['NEEDS_DEGREE']) echo '<td>'.$row->cb_degree.'</td>'; ?>
		<td><?php echo $row->cname; ?></td>
		<td><?php echo $row->pname; ?></td>
		<td><?php echo $row->catname; ?></td>
		<td><?php if ($row->course_haspre) echo $row->cmpl_prescore; ?></td>
		<td><?php if ($row->haseval) echo $row->cpercent; ?></td>
		<td><?php 
		if ($row->cpass == 'pass') echo '<font color="green">Pass</font>';
		if ($row->cpass == 'fail') echo '<font color="red">Fail</font>';
		?></td>
		<td><?php 
		if ($row->crecent) echo '<a href="index.php?option=com_continued&view=anscompl&course='.$row->course.'&fid='.$row->fid.'">Ans</a>';
		else echo 'Ans';
		?> <?php if ($row->cpass == 'pass' && $row->hascertif) echo ' - <a href="index.php?option=com_continued&controller=getcertif&task=getcertif&format=raw&course='.$row->id.'&user='.$row->user_id.'" target="_blank">Certif</a>'; ?>
		</td>
	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
	<tfoot>
		<td colspan="12"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>
</table>
<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="report" />

</form>
</div>

