<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&q_id='.(int) $this->item->q_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-30 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_QUESTION_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
				<li><label id="jform_questiontags-lbl" for="jform_questiontags" class="hasTip" title="">Tags</label>
				<fieldset id="jform_questiontags" class="radio inputbox">
				<?php 
				foreach ($this->qtags as $t) {
					if (!empty($this->item->questiontags)) $checked = in_array($t->qt_id,$this->item->questiontags) ? ' checked="checked"' : '';
					else $checked = '';
					?>
					<input type="checkbox" name="jform[questiontags][]" value="<?php echo (int) $t->qt_id;?>" id="jform_questiontags<?php echo (int) $t->qt_id;?>"<?php echo $checked;?>/>
					<label for="jform_questiontags<?php echo $t->qt_id; ?>">
					<?php echo ' '.$t->qt_name; ?></label><br /><br />
					<?php 
				}
				?></fieldset></li>
			</ul>
		</fieldset>
	</div>
	<div class="width-70 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_QUESTION_CONTENT' ); ?></legend>
<?php foreach($this->form->getFieldset('content') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
				<div class="clr"></div>
<?php endforeach; ?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="question.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

