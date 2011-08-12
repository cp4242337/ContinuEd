<?php defined('_JEXEC') or die('Restricted access');


$stepsl[0] = JHTML::_('select.option',  '','--All--');
$stepsl[1] = JHTML::_('select.option','fm','Front Matter');
$stepsl[2] = JHTML::_('select.option','mt','Material');
$stepsl[3] = JHTML::_('select.option','qz','Evaluation');
$stepsl[4] = JHTML::_('select.option','chk','Check');
$stepsl[5] = JHTML::_('select.option','asm','Assess (Grading)');
$stepsl[6] = JHTML::_('select.option','crt','Certificate');
$stepsl[7] = JHTML::_('select.option','vo','Material - Expired');
$stepsl[8] = JHTML::_('select.option','fmp','FM - Passed');
$stepsl[9] = JHTML::_('select.option','mtp','Material - Passed');
$stepsl[10] = JHTML::_('select.option','ans','View Answers');
$stepsl[11] = JHTML::_('select.option','rate','Rating');
$stepsl[12] = JHTML::_('select.option','pre','PreTest');
$stepsl[13] = JHTML::_('select.option','lnk','Entry Link');
$stepsl[14] = JHTML::_('select.option','fme','Front Matter - Exp');

?>
<form action="" method="post" name="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th align="left" width="50%"><?php
			echo '<div class="button2-left"><div class="page">';
			echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.format.value=\'raw\'; submitbutton(\'csvme\'); return false;">';
			echo 'Generate CSV</a></div></div>';
			if ($this->user) {
				echo '<div class="button2-left"><div class="page">';
				echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_user.value=\'\'; submitform(); return false;">';
				echo 'Clear User</a></div></div>';
			}
			if ($this->session) {
				echo '<div class="button2-left"><div class="page">';
				echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_session.value=\'\'; submitform(); return false;">';
				echo 'Clear Session</a></div></div>';
			}
			?></th>
			<th align="right" width="50%"><?php
			echo 'Start: '.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','onchange="submitform();"');
			echo ' End: '.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','onchange="submitform();"').'<br />';
			echo ' Category: '.JHTML::_('select.genericlist',$this->catlist,'filter_cat','onchange="submitform();"','value','text',$this->cat);
			echo ' What: '.JHTML::_('select.genericlist',$stepsl,'filter_step','onchange="submitform();"','value','text',$this->step);
			echo '<br>';
			echo ' Course: '.JHTML::_('select.genericlist',$this->courselist,'filter_course','onchange="submitform();"','value','text',$this->course);
			?></th>
		</tr>
	</thead>
</table>
<table class="adminlist">
	<thead>
		<tr>
			<th width="60"><?php echo JText::_( 'NUM' ); ?></th>
			<th><?php echo JText::_( 'Course' ); ?></th>
			<th><?php echo JText::_( 'Category' ); ?></th>
			<th><?php echo JText::_( 'When' ); ?></th>
			<th><?php echo JText::_( 'Who' ); ?></th>
			<th><?php echo JText::_( 'What' ); ?></th>
			<th width="70"><?php echo JText::_( 'Session' ); ?></th>
			<th width="70"><?php echo JText::_( 'IP Address' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		if ($row->user == 0) $row->username='Guest';


		$userlink = '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_user.value='.$row->user.'; submitform(); return false;">'.$row->username.'</a>';
		$sessionlink = '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_session.value=\''.$row->sessionid.'\'; submitform(); return false;">'.$row->sessionid.'</a>';

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $i + 1 + $this->pagination->limitstart; ?></td>
		<td><?php echo $row->cname; ?></td>
		<td><?php echo $row->catname; ?></td>
		<td><?php echo $row->tdhit; ?></td>
		<td><?php 
		if ($row->user != 0) echo $userlink;
		else echo $row->username;
		?></td>
		<td><?php 
		switch ($row->step) {
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
			case 'rate': echo 'Rating: '.$row->token; break;
			case 'pre': echo 'PreTest'; break;
			case 'lnk': echo 'Entry Link'; break;
			case 'fme': echo 'Front Matter - Exp'; break;
		}
		?></td>
		<td><?php echo $sessionlink; ?></td>
		<td><?php echo $row->track_ipaddr; ?></td>

	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<td colspan="8"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="filter_user" value="<?php echo $this->user; ?>" />
<input type="hidden" name="filter_session"
	value="<?php echo $this->session; ?>" /> <input type="hidden"
	name="format" value="html" /> <input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="controller" value="coursestate" /></form>
