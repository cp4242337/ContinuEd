<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&mat_id='.(int) $this->item->mat_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-40 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_MATERIAL_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
<?php if (ContinuEdHelper::getConfig()->mams) { ?>
	
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_MATERIAL_CONTENT' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('media') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
	
<?php } ?>
	</div>
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_MATERIAL_CONTENT' ); ?></legend>
<?php foreach($this->form->getFieldset('content') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
				<div class="clr"></div>
<?php endforeach; ?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="material.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

