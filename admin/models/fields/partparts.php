<?php
/**
 * @version		$Id: ordering.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Administrator
 * @subpackage	com_weblinks
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * Supports an HTML select list of categories
 *
 * @package		Joomla.Administrator
 * @subpackage	com_mpoll
 * @since		1.6
 */
class JFormFieldPartParts extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'PartParts';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
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
	
		$area = $this->form->getValue('part_area');
		$courseId	= (int) $this->form->getValue('part_course');
		// Build the query for the ordering list.
		if ($area == "post" || $area == "pre") {
			$query = 'SELECT course_'.$area.'parts ' .
					' FROM #__ce_courses' .
					' WHERE course_id = '.$courseId;
			$db->setQuery($query);
			$nump = $db->loadResult(); 
			$html[] = JHtml::_('select.integerlist',1,$nump,1,$this->name,$attr,$this->value);
		} else {
			$html[] = '<input type="hidden" value="1" name="'.$this->name.'">';
			
		}

		return implode($html);
	}
}
