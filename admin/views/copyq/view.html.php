<?php
/**
 * Hello View for Hello World Component
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
 * Hello View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdViewCopyQ extends JView
{
	/**
	 * display method of Hello view
	 * @return void
	 **/
	function display($tpl = null)
	{
		//get the hello
		$courseid = JRequest::getVar('course');
		$model = $this->getModel('copyq');
		$courses = $model->getCourseList();
		$questions = $model->getQuestions();
		JToolBarHelper::title(JText::_( 'ContinuEd Question Copy' ));
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy.png', 'Copy', false, false );
		JToolBarHelper::cancel();


		$this->assignRef('questions',		$questions);
		$this->assignRef('courseid',$courseid);
		$this->assignRef('courses',$courses);
		parent::display($tpl);
	}
}
