<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="" method="post" name="adminForm" id="adminForm"><input
	type="hidden" name="question" value="<?php echo $this->questionid; ?>">
<input type="hidden" name="course"
	value="<?php echo $this->courseid; ?>">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Option' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="opttxt" id="opttxt" cols="60"
			rows="2"><?php echo $this->answer->opttxt;?></textarea></td>
	</tr>

	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Explanation' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="optexpl" id="optexpl" cols="60"
			rows="2"><?php echo $this->answer->optexpl;?></textarea></td>
	</tr>

	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Correct' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.booleanlist','correct','',$this->answer->correct,'Yes','No','correct'); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Order' ); ?>:
		</label></td>
		<td><?php echo $this->answer->ordering;?> <input type="hidden"
			name="ordering" value="<?php echo $this->answer->ordering; ?>"></td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>


<input type="hidden" name="option" value="com_continued" />
<input type="hidden" name="id" value="<?php echo $this->answer->id; ?>" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="area" value="<?php echo JRequest::getVar('area'); ?>" />
<input type="hidden" name="controller" value="answer" /></form>
