<?php defined('_JEXEC') or die('Restricted access');
$pf[0]=JHTML::_('select.option','pass','Pass');
$pf[1]=JHTML::_('select.option','fail','Fail');
$pf[2]=JHTML::_('select.option','incomplete','Incomplete');
$pf[3]=JHTML::_('select.option','audit','Audit');
$pf[4]=JHTML::_('select.option','complete','Complete');
$pf[5]=JHTML::_('select.option','','- All -');


$type[0]=JHTML::_('select.option','','- All -');
$type[1]=JHTML::_('select.option','nonce','Non CE');
$type[2]=JHTML::_('select.option','ce','CE');
$type[3]=JHTML::_('select.option','review','Review');
$type[4]=JHTML::_('select.option','expired','Expired');
$type[5]=JHTML::_('select.option','viewed','Viewed');

$area[0]=JHTML::_('select.option','','- All -');
$area[1]=JHTML::_('select.option','pre','Pre Test');
$area[2]=JHTML::_('select.option','post','Post Test');
$area[3]=JHTML::_('select.option','inter','Intermediate');
$area[4]=JHTML::_('select.option','qanda','Q & A');

?>
<form action="" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%"></td>
		<td nowrap="nowrap" align="right"><?php
		echo 'Date Range: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
		echo ' - '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"').'<br/>';
		echo JText::_(' Category:').JHTML::_('select.genericlist',$this->catlist,'cat','onchange="submitform();"','value','text',$this->cat,'cat').'<br />';
		echo JText::_(' Course:').JHTML::_('select.genericlist',$this->courselist,'course','onchange="submitform();"','value','text',$this->course,'course').'<br />';
		if ($this->course) {
			echo JText::_(' Question Group:').JHTML::_('select.genericlist',$this->qgrouplist,'qgroup','onchange="submitform();"','value','text',$this->qgroup,'qgrouplist');
			echo JText::_(' Question Area:').JHTML::_('select.genericlist',$area,'qarea','onchange="submitform();"','value','text',$this->qarea,'area');
		}
		echo JText::_(' User Group:').JHTML::_('select.genericlist',$this->grouplist,'usergroup','onchange="submitform();"','value','text',$this->usergroup,'grouplist');
		echo JText::_(' Status:').JHTML::_('select.genericlist',$pf,'pf','onchange="submitform();"','value','text',$this->pf,'pf');
		echo JText::_(' View Type:').JHTML::_('select.genericlist',$type,'type','onchange="submitform();"','value','text',$this->type,'type');
		?></td>
	</tr>
</table>
<div id="editcell">

<table class="adminlist">
	<thead>
		<tr>
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Group' ); ?></th>
			<?php 
			if (!$this->course) {
				echo '<th>'.JText::_( 'Course' ).'</th>';
				echo '<th>'.JText::_( 'Category' ).'</th>'; 
			} ?>
			<th><?php echo JText::_( 'Status' ); ?></th>
			<th><?php echo JText::_( 'View Type' ); ?></th>
			<th><?php echo JText::_( 'Last Step' ); ?></th>
			<th><?php echo JText::_( 'Start' ); ?></th>
			<th><?php echo JText::_( 'End' ); ?></th>
			<th><?php echo JText::_( 'Pre Score' ); ?></th>
			<th><?php echo JText::_( 'Post Score' ); ?></th>
			<?php
			//Fill in questions if we are showing only 1 course
			if ($this->course) {
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
			}
			?>
		</tr>
	</thead>
	<tbody>
	<?php
	$k = 0;
	$cq = 1;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $this->userlist[$row->rec_user]->name; ?></td>
		<td><?php echo $this->userlist[$row->rec_user]->usergroup; ?></td>
		<?php 
		if (!$this->course) {
			echo '<td>'.$row->course_name.'</td>';
			echo '<td>'.$row->cat_name.'</td>';
		} ?>
		<td><?php 
			switch ($row->rec_pass) {
				case 'pass': echo 'Pass'; break;
				case 'fail': echo 'Fail'; break;
				case 'incomplete': echo 'Incomplete'; break;
				case 'audit': echo 'Audit'; break;
				case 'complete': echo 'Completed'; break;
			} ?>
		</td>
		<td><?php 
			switch ($row->rec_type) {
				case 'nonce': echo 'Non-CE'; break;
				case 'ce': echo 'CE'; break;
				case 'review': echo 'Review'; break;
				case 'expired': echo 'Expired'; break;
				case 'viewed'	: echo 'Viewed'; break;
				
			} ?>
		</td>
		<td><?php 
			switch ($row->rec_laststep) {
				case 'fm': echo 'Front Matter'; break;
				case 'mt': echo 'Material'; break;
				case 'qz': echo 'Evaluation'; break;
				case 'chk': echo 'Check'; break;
				case 'asm': echo 'Assess (Grading)'; break;
				case 'crt': echo 'Certificate'; break;
				case 'vo': echo 'Material - Expired'; break;
				case 'fmp': echo 'FM - Passed'; break;
				case 'mtp': echo 'Material - Passed'; break;
				case 'ans': echo 'View Answers'; break;
				case 'pre': echo 'PreTest'; break;
				case 'lnk': echo 'Entry Link'; break;
				case 'fme': echo 'Front Matter - Exp'; break;
			}
			?>
		</td>
		<td><?php echo $row->rec_start; ?></td>
		<td><?php echo $row->rec_end; ?></td>
		<td><?php if ($row->rec_prescore == -1 || !$row->rec_user || $row->rec_type != 'ce') echo 'N/A'; else echo $row->rec_prescore; ?> </td>
		<td><?php if ($row->rec_postscore == -1 || !$row->rec_user || $row->rec_type != 'ce') echo 'N/A'; else echo $row->rec_postscore; ?> </td>
		<?php
		//Fill in answers if we are showing only 1 course
		if ($this->course) {
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
		}
		?>

	</tr>
	<?php
	$k = 1 - $k;
	$cq = $row->disporder+1;
	}
	$colcount = 11 + count($qinter) + count($qpre) + count($qpost);
	if ($this->course) $count = $count - 2;
	?>
	</tbody>
	<tfoot>
		<tr>
			<?php echo '<td colspan="'.$colcount.'"> '.$this->pagination->getListFooter().'</td>'; ?>
		</tr>
	</tfoot>
</table>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="boxchecked" value="0" /> <input type="hidden" name="controller"
	value="coursereport" /></div>