<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&view=prereqs'); ?>" method="post" name="adminForm">
		<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			
		</div>
		<div class="filter-select fltrt">
			<select name="filter_course" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_CONTINUED_QUESTION_SELECT_COURSE');?></option>
				<?php echo $html[] = JHtml::_('select.options',$this->clist,"value","text",$this->state->get('filter.course')); ?>
			</select>
		</div>
	</fieldset>
	
	<div class="clr"> </div>
	
	<table class="adminlist">
		<thead><?php echo $this->loadTemplate('head');?></thead>
		<tfoot><?php echo $this->loadTemplate('foot');?></tfoot>
		<tbody><?php echo $this->loadTemplate('body');?></tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

