<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
// load tooltip behavior
JHtml::_('behavior.tooltip');

$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_continued&view=purchases'); ?>" method="post" name="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_CONTINUED_SEARCH_IN_PURCHASE'); ?>" />
			<?php 
				echo '<label for="filter_start">Date Range:</label> '.JHTML::_('calendar',$this->state->get('filter.start'),'filter_start','filter_start','%Y-%m-%d','');
				echo ' '.JHTML::_('calendar',$this->state->get('filter.end'),'filter_end','filter_end','%Y-%m-%d','');
			?>
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';document.id('filter_start').value='<?php echo date("Y-m-d",strtotime("-1 months")); ?>';document.id('filter_end').value='<?php echo date("Y-m-d"); ?>';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			<select name="filter_course" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('COM_CONTINUED_PURCHASE_SELECT_COURSE');?></option>
				<?php 
					echo $html[] = JHtml::_('select.options',$this->clist,"value","text",$this->state->get('filter.course')); 
				?>
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


