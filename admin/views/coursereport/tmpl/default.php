<?php defined('_JEXEC') or die('Restricted access');
$pf[0]=JHTML::_('select.option','pass','Pass');
$pf[1]=JHTML::_('select.option','fail','Fail');
$pf[2]=JHTML::_('select.option','incomplete','Incomplete');
$pf[3]=JHTML::_('select.option','audit','Audit');
$pf[4]=JHTML::_('select.option','complete','complete');
$pf[5]=JHTML::_('select.option','','All');


$type[0]=JHTML::_('select.option','','All');
$type[1]=JHTML::_('select.option','','Non CE');
$type[2]=JHTML::_('select.option','','CE');
$type[3]=JHTML::_('select.option','','Review');
$type[4]=JHTML::_('select.option','','Expired');
$type[5]=JHTML::_('select.option','','Viewed');

?>
<form action="" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%"></td>
		<td nowrap="nowrap" align="right"><?php
		echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
		echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"');
		echo JText::_(' Pass/Fail:').JHTML::_('select.genericlist',$pf,'pf','onchange="submitform();"','value','text',$this->pf,'pf');
		echo JText::_(' Type:').JHTML::_('select.genericlist',$type,'type','onchange="submitform();"','value','text',$this->type,'type');
		?></td>
	</tr>
</table>
<div id="editcell">

<table class="adminlist">
	<thead>
		<tr>
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Group' ); ?></th>
			<th><?php echo JText::_( 'Course' ); ?></th>
			<th><?php echo JText::_( 'Type' ); ?></th>
			<th><?php echo JText::_( 'Status' ); ?></th>
			<th><?php echo JText::_( 'Start' ); ?></th>
			<th><?php echo JText::_( 'End' ); ?></th>
			<th><?php echo JText::_( 'Pre Score' ); ?></th>
			<th><?php echo JText::_( 'Post Score' ); ?></th>
			<?php
			//pretest
			foreach ($this->qpre as $qu) {
				echo '<th>#'.$qu->ordering.' '.$qu->q_text.'</th>';
			}
			//inter
			foreach ($this->qinter as $qu) {
				echo '<th>#'.$qu->ordering.' '.$qu->q_text.'</th>';
			}
			//posttest
			foreach ($this->qpost as $qu) {
				echo '<th>#'.$qu->ordering.' '.$qu->q_text.'</th>';
			}
			?>
		</tr>
	</thead>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $row->fullname; ?></td>
		<td><?php echo $row->ug_name; ?></td>
		<td><?php echo $row->course_name; ?></td>
		<td><?php echo $row->rec_type; ?></td>
		<td><?php echo $row->rec_pass; ?></td>
		<td><?php echo $row->rec_start; ?></td>
		<td><?php echo $row->rec_end; ?></td>
		<td><?php echo $row->rec_prescore; ?> </td>
		<td><?php echo $row->rec_postscore; ?> </td>
		<?php
		//pretest
		foreach ($this->qpre as $qu) {
			echo '<td>';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') { 
				if ($qu->q_cat == 'assess') {
					if ($this->opts[$row->$qnum]->correct) echo '<span style="color:#008000">';
					else echo '<span style="color:#800000">';
				}
				echo $this->opts[$row->$qnum]->text; 
				if ($qu->q_cat == 'assess') echo '</span>';
			}
			if ($qu->q_type == 'textbox') { echo $row->$qnum; }
			if ($qu->q_type == 'textar') { echo $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					echo $this->opts[$ans]->text.'<br />';
				}
			}
			if ($qu->q_type == 'yesno') { echo $row->$qnum; }
			echo '</td>';
		}
		//inter
		foreach ($this->qinter as $qu) {
			echo '<td>';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') { 
				if ($qu->q_cat == 'assess') {
					if ($this->opts[$row->$qnum]->correct) echo '<span style="color:#008000">';
					else echo '<span style="color:#800000">';
				}
				echo $this->opts[$row->$qnum]->text; 
				if ($qu->q_cat == 'assess') echo '</span>';
			}
			if ($qu->q_type == 'textbox') { echo $row->$qnum; }
			if ($qu->q_type == 'textar') { echo $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					echo $this->opts[$ans]->text.'<br />';
				}
			}
			if ($qu->q_type == 'yesno') { echo $row->$qnum; }
			echo '</td>';
		}
		//posttest
		foreach ($this->qpost as $qu) {
			echo '<td>';
			$qnum = 'q'.$qu->q_id.'ans';
			if ($qu->q_type == 'multi' || $qu->q_type == 'dropdown') { 
				if ($qu->q_cat == 'assess') {
					if ($this->opts[$row->$qnum]->correct) echo '<span style="color:#008000">';
					else echo '<span style="color:#800000">';
				}
				echo $this->opts[$row->$qnum]->text; 
				if ($qu->q_cat == 'assess') echo '</span>';
			}
			if ($qu->q_type == 'textbox') { echo $row->$qnum; }
			if ($qu->q_type == 'textar') { echo $row->$qnum; }
			if ($qu->q_type == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
			if ($qu->q_type == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					echo $this->opts[$ans]->text.'<br />';
				}
			}
			if ($qu->q_type == 'yesno') { echo $row->$qnum; }
			echo '</td>';
		}
		?>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	?>
</table>

<p align="center"><?php echo $this->pagination->getListFooter();  ?></p>
<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="coursereport" /></div>