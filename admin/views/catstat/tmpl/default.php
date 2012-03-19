<?php defined('_JEXEC') or die('Restricted access');


$stepsl[0] = JHTML::_('select.option',  '','--None--');
$stepsl[1] = JHTML::_('select.option','fm','Front Matter');
$stepsl[2] = JHTML::_('select.option','menu','Menu');
$stepsl[3] = JHTML::_('select.option','det','Details');



?>
<form action="" method="post" name="adminForm">
<div id="editcell">
<table class="adminlist">
	<thead>
		<tr>
			<th align="left" width="50%"><?php
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
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Email' ); ?></th>
			<th><?php echo JText::_( 'Group' ); ?></th>
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


		$sessionlink = '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_session.value=\''.$row->sessionid.'\'; submitform(); return false;">'.$row->sessionid.'</a>';

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $i + 1 + $this->pagination->limitstart; ?></td>
		<td><?php echo $row->cat_name; ?></td>
		<td><?php echo $row->tdhit; ?></td>
		<td><?php 
		echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_user.value='.$row->user.'; submitform(); return false;">'.$this->users[$row->user]->name.'</a>';
		?></td>
		<td><?php 
		echo $this->users[$row->user]->email;
		?></td>
		<td><?php 
		echo $this->users[$row->user]->usergroup;
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
