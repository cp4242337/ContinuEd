<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
<div class="col100">
<fieldset class="adminform"><legend><?php echo JText::_( 'Details' ); ?></legend>

<table class="admintable">
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Certificate Type' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$this->certlist,'ctmpl_cert',NULL,'crt_id','crt_name',$this->answer->ctmpl_cert); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Provider' ); ?>:
		</label></td>
		<td><?php echo JHTML::_('select.genericlist',$this->prolist,'ctmpl_prov',NULL,'pid','pname',$this->answer->ctmpl_prov); ?>
		</td>
	</tr>
	<tr>
		<td width="100" align="right" class="key"><label for="greeting"> <?php echo JText::_( 'Certificate Content' ); ?>:
		</label></td>
		<td><textarea class="text_area" name="ctmpl_content"
			id="ctmpl_content" cols="80" rows="25"><?php echo $this->answer->ctmpl_content;?></textarea>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><b>{Name}</b> - Name<br>
		<b>{Title}</b> - Activity Title<br>
		<b>{ADate}</b> - Activity Date<br>
		<b>{Credits}</b> - Number of credits<br>
		<b>{Faculty}</b> - Faculty Name(s)<br>
		<b>{IDate}</b> - Issue date<br>
		<b>{PNNum}</b> - Program Number, Nursing <b>{PPNum}</b> - Program
		Number, Pharmacy<br />
		<b>{LType}</b> - Learning Format<br /> 
		<b>{Address1}</b> - Address Line 1, <b>{City}</b> - City, <b>{State}</b>
		- State, <b>{Zip}</b> - Zip</td>
	</tr>
</table>
</fieldset>
</div>
<div class="clr"></div>

<input type="hidden" name="option" value="com_continued" /> <input
	type="hidden" name="ctmpl_id"
	value="<?php echo $this->answer->ctmpl_id; ?>" /> <input type="hidden"
	name="task" value="" /> <input type="hidden" name="controller"
	value="certif" /></form>
