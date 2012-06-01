<?php

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldCourses extends JFormField
{
	protected $type = 'Courses';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		$attr = '';
		$db = JFactory::getDBO();
		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		// Build the query for the ordering list.
		$query = 'SELECT course_id AS value, LEFT(course_name, 80) AS text' .
				' FROM #__ce_courses' .
				' ORDER BY course_name';
		$db->setQuery($query);
		$html[] = '<select name="'.$this->name.'" class="inputbox" '.$attr.'>';
		$html[] = '<option value="">'.JText::_('COM_CONTINUED_COURSE_SELECT_COURSE').'</option>';
		$html[] = JHtml::_('select.options',$db->loadObjectList(),"value","text",$this->value);
		$html[] = '</select>';
		

		return implode($html);
	}
}
