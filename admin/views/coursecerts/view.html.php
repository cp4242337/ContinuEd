<?php
/**
 * Hellos View for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdViewCourseCerts extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$courseid = JRequest::getVar('course');
		JToolBarHelper::title(   JText::_( 'ContinuEd Avaiable Certificates for Course Manager' ), 'continued' );
		JToolBarHelper::deleteList();
		//JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::back('Courses','index.php?option=com_continued&view=courses');

		// Get data from the model
		$items		= & $this->get( 'Data');

		$this->assignRef('items',		$items);
		$this->assignRef('courseid',$courseid);

		parent::display($tpl);
	}
}
