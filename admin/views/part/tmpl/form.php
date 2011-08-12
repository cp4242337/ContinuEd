<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="part_course"
	value="<?php echo $this->courseid; ?>"> <input type="hidden"
	name="part_area" value="<?php echo $this->answer->part_area; ?>"> <input
	type="hidden" name="course" value="<?php echo $this->courseid; ?>">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Name' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="part_name"
			id="part_name" size="90"
			value="<?php echo $this->answer->part_name;?>"></td>
	</tr>

	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Description' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="part_desc" id="part_desc"
			cols="60" rows="2"><?php echo $this->answer->part_desc;?></textarea>
		</td>
	</tr>

	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Part' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.integerlist',1,$this->numparts,1,'part_part','',$this->answer->part_part); ?>
		</td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="part_id"
	value="<?php echo $this->answer->part_id; ?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="part" /></form>
