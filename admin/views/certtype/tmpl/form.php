<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Certificate Type' ); ?>:
		</label></td>
		<td><input type="text" class="text_area" name="crt_name" id="crt_name"
			size="50" maxlen="50" value="<?php echo $this->answer->crt_name;?>">
		</td>
	</tr>


</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="crt_id"
	value="<?php echo $this->answer->crt_id; ?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="certtype" /></form>
