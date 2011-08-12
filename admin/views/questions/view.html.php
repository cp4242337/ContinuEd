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
class ContinuEdViewQuestions extends JView
{
	/**
	 * Hellos view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
		$model = $this->getModel();
		$courseid = JRequest::getVar('course');
		$filter_part = JRequest::getVar('filter_part');
		$area = JRequest::getVar('area');

		JToolBarHelper::title(   JText::_( 'ContinuEd Question Manager ['.$area.']' ), 'continued' );
		JToolBarHelper::publishList('publish','Publish');
		JToolBarHelper::unpublishList('unpublish','Unpublish');
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy.png', 'Copy', true, true );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::back('Courses','index.php?option=com_continued&view=courses');

		// Get data from the model
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );

		$parts = $model->getParts($courseid,$area);

		$this->assignRef('items',		$items);
		$this->assignRef('courseid',$courseid);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('parts',$parts);
		$this->assignRef('area',$area);
		$this->assignRef('filter_part',$filter_part);
		$this->assignRef('cname',$model->getCourseName($courseid));

		parent::display($tpl);
	}
}
