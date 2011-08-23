<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&cat_id='.(int) $this->item->cat_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_CAT_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_CAT_PURCHASE' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('purchase') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_CAT_FRONTMATTER' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('fmsettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
<?php foreach($this->form->getFieldset('fmcontent') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
<?php endforeach; ?>
		</fieldset>
		

	</div>
	<div>
		<input type="hidden" name="task" value="cat.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

