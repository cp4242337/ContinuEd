<?php defined('_JEXEC') or die('Restricted access');


$stepsl[0] = JHTML::_('select.option',  '','--None--');
$stepsl[1] = JHTML::_('select.option','fm','Front Matter');
$stepsl[2] = JHTML::_('select.option','menu','Menu');
$stepsl[3] = JHTML::_('select.option','det','Details');

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
for ($y=2008; $y <= date("Y"); $y++) {$yearsl[] = JHTML::_('select.option',  $y,$y);}
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
			echo 'Month: '.JHTML::_('select.genericlist',$monthsl,'filter_month','onchange="submitform();"','value','text',$this->month);
			echo ' Year: '.JHTML::_('select.genericlist',$yearsl,'filter_year','onchange="submitform();"','value','text',$this->year);
			echo ' Category: '.JHTML::_('select.genericlist',$this->catlist,'filter_cat','onchange="submitform();"','value','text',$this->cat);
			echo ' What: '.JHTML::_('select.genericlist',$stepsl,'filter_step','onchange="submitform();"','value','text',$this->step);
			?></th>
		</tr>
	</thead>
</table>
<table class="adminlist">
	<thead>
		<tr>
			<th width="60"><?php echo JText::_( 'NUM' ); ?></th>
			<th><?php echo JText::_( 'Category' ); ?></th>
			<th><?php echo JText::_( 'When' ); ?></th>
			<th><?php echo JText::_( 'Who' ); ?></th>
			<th><?php echo JText::_( 'What' ); ?></th>
			<th width="70"><?php echo JText::_( 'Session' ); ?></th>
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
		<td><?php echo $row->catname; ?></td>
		<td><?php echo $row->tdhit; ?></td>
		<td><?php 
		if ($row->user != 0) echo $userlink;
		else echo $row->username;
		?></td>
		<td><?php 
		switch ($row->viewed) {
			case 'fm': echo 'Front Matter'; break;
			case 'menu': echo 'Menu'; break;
			case 'det': echo 'Details'; break;
		}
		?></td>
		<td><?php echo $sessionlink; ?></td>

	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<td colspan="7"><?php echo $this->pagination->getListFooter(); ?></td>
	</tfoot>
</table>
</div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="filter_user" value="<?php echo $this->user; ?>" />
<input type="hidden" name="filter_session"
	value="<?php echo $this->session; ?>" /> <input type="hidden"
	name="format" value="html" /> <input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" /> <input type="hidden"
	name="controller" value="catstate" /></form>
