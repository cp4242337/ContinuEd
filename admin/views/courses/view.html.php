<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCourses extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		JToolBarHelper::title(   JText::_( 'ContinuEd Courses Manager' ), 'continued' );
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy.png', 'Copy', true, false );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		$model = $this->getModel('courses');
		$filter_cat = $model->getState('filter_cat');
		$filter_prov = $model->getState('filter_prov');
		$cats =  $model->getCatList();
		$provs = $model->getProvList();
		// Get data from the model

		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );

		$this->assignRef('filter_prov',$filter_prov);
		$this->assignRef('filter_cat',$filter_cat);
		$this->assignRef('cats',		$cats);
		$this->assignRef('provs',		$provs);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);

		parent::display($tpl);
	}
}
