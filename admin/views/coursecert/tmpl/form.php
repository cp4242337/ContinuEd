<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="course"
	value="<?php echo $this->courseid; ?>"> <input type="hidden"
	name="course_id" value="<?php echo $this->courseid; ?>">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Certificate' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$this->certs,'cert_id',NULL,'crt_id','crt_name',NULL); ?>
		</td>
	</tr>


</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="id" value="<?php echo $this->coursecert->id; ?>" />
<input type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="coursecert" /></form>
