<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewParts extends JView
{
	function display($tpl = null)
	{
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		JToolBarHelper::title(   JText::_( 'ContinuEd Part Label Manager ['.$area.']' ), 'continued' );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::back('Course List','index.php?option=com_continued&view=courses');

		$items		= & $this->get( 'Data');



		$this->assignRef('items',		$items);
		$this->assignRef('area',		$area);
		$this->assignRef('courseid',$courseid);

		parent::display($tpl);
	}
}
