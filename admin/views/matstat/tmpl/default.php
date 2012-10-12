<?php defined('_JEXEC') or die('Restricted access');


$typesl[0] = JHTML::_('select.option',  '','-- Select Type --');
$typesl[1] = JHTML::_('select.option','view','First View');
$typesl[2] = JHTML::_('select.option','review','Review');
$typesl[3] = JHTML::_('select.option','nocredit','No Credit');

?>
<form action="" method="post" name="adminForm">
<fieldset id="filter-bar">
	<div class="filter-search fltlft">
	<?php
		if ($this->user) {
			echo '<div class="button2-left"><div class="page">';
			echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_user.value=\'\'; submitform(); return false;">'.'<br />';
			echo 'Clear User</a></div></div>';
		}
		echo '<label class="filter-search-lbl" for="startdate">Start:</label>'.JHTML::_('calendar',$this->startdate,'startdate','startdate','%Y-%m-%d','');
		echo '<label class="filter-search-lbl" for="enddate">End:</label>'.JHTML::_('calendar',$this->enddate,'enddate','enddate','%Y-%m-%d','');
		?>	<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
	</div>
	<div class="filter-select fltrt"><?php
		echo JHTML::_('select.genericlist',$this->catlist,'filter_cat','onchange="submitform();"','value','text',$this->cat);
		echo JHTML::_('select.genericlist',$typesl,'filter_type','onchange="submitform();"','value','text',$this->type);
		echo '<br>';
		echo JHTML::_('select.genericlist',$this->courselist,'filter_course','onchange="submitform();"','value','text',$this->course);
		?>
	</div>
</fieldset>
<div class="clr"> </div>
<table class="adminlist">
	<thead>
		<tr>
			<th width="60"><?php echo JText::_( 'NUM' ); ?></th>
			<th><?php echo JText::_( 'Material' ); ?></th>
			<th><?php echo JText::_( 'Course' ); ?></th>
			<th><?php echo JText::_( 'Category' ); ?></th>
			<th><?php echo JText::_( 'When' ); ?></th>
			<th><?php echo JText::_( 'Name' ); ?></th>
			<th><?php echo JText::_( 'Email' ); ?></th>
			<th><?php echo JText::_( 'Group' ); ?></th>
			<th><?php echo JText::_( 'Type' ); ?></th>
			<th width="70"><?php echo JText::_( 'Session' ); ?></th>
			<th width="70"><?php echo JText::_( 'IP Address' ); ?></th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)
	{
		$row = &$this->items[$i];
		


	
		$sessionlink = $row->mt_session;

		?>
	<tr class="<?php echo "row$k"; ?>">
		<td><?php echo $i + 1 + $this->pagination->limitstart; ?></td>
		<td><?php echo $row->mat_title; ?></td>
		<td><?php echo $row->course_name; ?></td>
		<td><?php echo $row->cat_name; ?></td>
		<td><?php echo $row->mt_time; ?></td>
		<td><?php 
		if ($row->mt_user == 0) echo "Guest";
		else echo '<a href="javascript:void(0);" onclick="javascript: document.adminForm.filter_user.value='.$row->user.'; submitform(); return false;">'.$this->users[$row->mt_user]->name.'</a>';
		?></td>
		<td><?php 
		if ($row->mt_user == 0) echo "Guest";
		else echo $this->users[$row->mt_user]->email;
		?></td>
		<td><?php 
		if ($row->mt_user == 0) echo "Guest";
		else echo $this->users[$row->mt_user]->usergroup;
		?></td>
		<td><?php 
		switch ($row->mt_type) {
			case 'view': echo 'First View'; break;
			case 'review': echo 'Review'; break;
			case 'nocredit': echo 'No Credit'; break;
		}
		?></td>
		<td><?php echo $sessionlink; ?></td>
		<td><?php echo $row->mt_ipaddr; ?></td>

	</tr>
	<?php
	$k = 1 - $k;
	}
	?>
	<tfoot>
		<td colspan="11"><?php echo $this->pagination->getListFooter(); ?></td>
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
