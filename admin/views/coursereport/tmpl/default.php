<?php defined('_JEXEC') or die('Restricted access');

$pf[0]=JHTML::_('select.option','pass','Pass');
$pf[1]=JHTML::_('select.option','fail','Fail');
$pf[2]=JHTML::_('select.option','','Both');

$monthsl[1] = JHTML::_('select.option',  '','--None--');
$monthsl[2] = JHTML::_('select.option',  '1','Jan');
$monthsl[3] = JHTML::_('select.option',  '2','Feb');
$monthsl[4] = JHTML::_('select.option',  '3','Mar');
$monthsl[5] = JHTML::_('select.option',  '4','Apr');
$monthsl[6] = JHTML::_('select.option',  '5','May');
$monthsl[7] = JHTML::_('select.option',  '6','Jun');
$monthsl[8] = JHTML::_('select.option',  '7','Jul');
$monthsl[9] = JHTML::_('select.option',  '8','Aug');
$monthsl[10] = JHTML::_('select.option',  '9','Sep');
$monthsl[11] = JHTML::_('select.option',  '10','Oct');
$monthsl[12] = JHTML::_('select.option',  '11','Nov');
$monthsl[13] = JHTML::_('select.option',  '12','Dec');

$yearsl[] = JHTML::_('select.option',  '','--None--');
for ($y=2007; $y <= date("Y"); $y++) {$yearsl[] = JHTML::_('select.option',  $y,$y);}
?>
<form action="" method="post" name="adminForm">
<table>
	<tr>
		<td align="left" width="100%"></td>
		<td nowrap="nowrap" align="right"><?php
		echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
		echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"');
		echo JText::_(' Pass/Fail:').JHTML::_('select.genericlist',$pf,'pf','onchange="submitform();"','value','text',$this->pf,'pf');
		?></td>
	</tr>
</table>
<div id="editcell">

<table class="adminlist">
	<thead>
		<tr>
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Course' ); ?></th>
			<th><?php echo JText::_( 'Completed On' ); ?></th>
			<th><?php echo JText::_( 'Score' ); ?></th>
			<?php
			foreach ($this->questions as $qu) {
				echo '<th>#'.$qu->ordering.' '.$qu->qtext.'</th>';
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
		<td><?php echo $row->cname; ?></td>
		<td><?php echo $row->ctime; ?></td>
		<td><?php 
			if ($this->area == 'post') echo $row->cpercent;
			if ($this->area == 'pre') echo $row->cmpl_prescore;
			if ($this->area == 'inter') echo 'n/a';
			 ?>
		</td>
		<?php
		foreach ($this->questions as $qu) {
			echo '<td>';
			$qnum = 'q'.$qu->id.'ans';
			if ($qu->qtype == 'multi' || $qu->qtype == 'dropdown') { echo $this->opts[$row->$qnum]; }
			if ($qu->qtype == 'textbox') { echo $row->$qnum; }
			if ($qu->qtype == 'textar') { echo $row->$qnum; }
			if ($qu->qtype == 'cbox') { if ($row->$qnum == 'on') echo 'Checked'; else echo 'Unchecked'; }
			if ($qu->qtype == 'mcbox') {
				$answers = explode(' ',$row->$qnum);
				foreach ($answers as $ans) {
					echo $this->opts[$ans].'<br />';
				}
			}
			if ($qu->qtype == 'yesno') { echo $row->$qnum; }
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