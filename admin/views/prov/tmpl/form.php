<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Provider Name' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="pname" id="pname"
			width="40" maxlen="50" value="<?php echo $this->answer->pname;?>"></td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Logo FileName:' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="plogo" id="plogo"
			width="40" maxlen="50" value="<?php echo $this->answer->plogo;?>"><br>
		Upload logos' image file to: images/continued/provider/</td>
	</tr>

</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="pid" value="<?php echo $this->answer->pid; ?>" /> <input
	type="hidden" name="task" value="" /> <input type="hidden"
	name="controller" value="prov" /></form>
