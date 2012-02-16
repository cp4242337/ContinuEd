<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$saveOrder	= $listOrder == 'q.ordering';

$area[0]=JHTML::_('select.option','pre','Pre Test');
$area[1]=JHTML::_('select.option','post','Post Test');
$area[2]=JHTML::_('select.option','inter','Intermediate');
$area[3]=JHTML::_('select.option','qanda','Q & A');


?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&view=questions'); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			
		</div>
		<div class="filter-select fltrt">
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
			</select>
			<select name="filter_course" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_CONTINUED_QUESTION_SELECT_COURSE');?></option>
				<?php echo $html[] = JHtml::_('select.options',$this->clist,"value","text",$this->state->get('filter.course')); ?>
			</select>
			<select name="filter_qgroup" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_CONTINUED_QUESTION_SELECT_QGROUP');?></option>
				<?php echo $html[] = JHtml::_('select.options',$this->qgroups,"value","text",$this->state->get('filter.qgroup')); ?>
			</select>
			<select name="filter_area" class="inputbox" onchange="this.form.submit()">
				<?php echo $html[] = JHtml::_('select.options',$area,"value","text",$this->state->get('filter.area')); ?>
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


