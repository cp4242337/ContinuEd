<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&uf_id='.(int) $this->item->uf_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_UFIELD_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-70 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_UFIELD_CONTENT' ); ?></legend>
<?php foreach($this->form->getFieldset('content') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
				<div class="clr"></div>
<?php endforeach; ?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="ufield.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

