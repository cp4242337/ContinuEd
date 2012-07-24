<?php

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$params = $this->form->getFieldsets('params');
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&layout=edit&course_id='.(int) $this->item->course_id); ?>" method="post" name="adminForm" id="continued-form" class="form-validate">
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_SETTINGS' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('details') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_FRONTMATTER' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('fmsettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
<?php foreach($this->form->getFieldset('fmcontent') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
<?php endforeach; ?>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_MATERIAL' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('matsettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
<?php foreach($this->form->getFieldset('matcontent') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
<?php endforeach; ?>
		</fieldset>
	</div>
	<div class="width-50 fltlft">
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_CERTIFICATE' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('certsettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			<li><label id="jform_coursecerts-lbl" for="jform_coursecerts" class="hasTip" title="">Course Certificates</label>
			<fieldset id="jform_coursecerts" class="radio inputbox">
			<?php 
			foreach ($this->ctypes as $ct) {
				if (!empty($this->item->coursecerts)) $checked = in_array($ct->crt_id,$this->item->coursecerts) ? ' checked="checked"' : '';
				else $checked = '';
				?>
				<input type="checkbox" name="jform[coursecerts][]" value="<?php echo (int) $ct->crt_id;?>" id="jform_coursecert<?php echo (int) $ct->crt_id;?>"<?php echo $checked;?>/>
				<label for="jform_coursecert<?php echo $ct->crt_id; ?>">
				<?php echo ' '.$ct->crt_name; ?></label><br /><br />
				<?php 
			}
			?></fieldset></li>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_PRETEST' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('presettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_EVAL' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('evalsettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_CATLINK' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('catlinksettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_EXTLINK' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('extlinksettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
		</fieldset>
		<fieldset class="adminform">
			<legend><?php echo JText::_( 'COM_CONTINUED_COURSE_PURCHASE' ); ?></legend>
			<ul class="adminformlist">
<?php foreach($this->form->getFieldset('purchasesettings') as $field): ?>
				<li><?php echo $field->label;echo $field->input;?></li>
<?php endforeach; ?>
			</ul>
<?php foreach($this->form->getFieldset('purchasecontent') as $field): ?>
				<?php echo '<div>'.$field->label.'<div class="clr"></div>'.$field->input.'</div>';?>
<?php endforeach; ?>
		</fieldset>
	</div>
	<div>
		<input type="hidden" name="task" value="course.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

