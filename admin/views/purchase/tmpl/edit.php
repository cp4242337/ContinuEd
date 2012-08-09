<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&purchase_id='.(int) $this->item->purchase_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_PURCHASE_DETAILS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
	</div>
	<div class="width-100 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_PURCHASE_HISTORY' ); ?></legend>
			
<?php 

foreach($this->history as $h) { 
	echo $h->pl_time.'<br /><br />'.nl2br(htmlentities($h->pl_resarray));
	echo '<hr size="1">';
} ?>
			
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="purchase.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

