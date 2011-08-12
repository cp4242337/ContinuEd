<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Degree' ); ?>:
		</label></td>
		<td><?php echo $this->degree; ?></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Certificate' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$this->certifs,'certa','onchange="submitform();"','crt_id','crt_name',$this->cert,'certa'); ?>
		Will save on change of Certificate</td>
	</tr>

</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="edit" value="true" /> <input type="hidden"
	name="view" value="degree" /></form>
