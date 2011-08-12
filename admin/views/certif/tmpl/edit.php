<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&ctmpl_id='.(int) $this->item->ctmpl_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_CERTIF_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_CERTIF_CONTENT' ); ?></legend>
<?php foreach($this->form->getFieldset('content') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
				<div class="clr"></div><b>{Name}</b> - Name<br>
		<b>{Title}</b> - Activity Title<br>
		<b>{ADate}</b> - Activity Date<br>
		<b>{Credits}</b> - Number of credits<br>
		<b>{Faculty}</b> - Faculty Name(s)<br>
		<b>{IDate}</b> - Issue date<br>
		<b>{PNNum}</b> - Program Number, Nursing <b>{PPNum}</b> - Program
		Number, Pharmacy<br />
		<b>{LType}</b> - Learning Format<br /> 
		<b>{Address1}</b> - Address Line 1, <b>{City}</b> - City, <b>{State}</b>
		- State, <b>{Zip}</b> - Zip
<?php endforeach; ?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="certif.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

