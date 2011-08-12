<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewReports extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$option;
		JToolBarHelper::title(   JText::_( 'ContinuEd Reports - Completition' ), 'continued' );
		JToolBarHelper::deleteList('Are you sure you wish to delete these record(s)?');
		JToolBarHelper::custom('csvmeinit','archive','','CSV',false);

		$filename       = $title . '-' . date("Ymd");
		 
		// Get data from the model
		$model = $this->getModel('reports');
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$cat = $model->getState('cat');
		$course = $model->getState('course');
		$pf = $model->getState('pf');
		$prov = $model->getState('prov');
		$courses = $model->getCourseList();
		$cats = $model->getCatList();
		$provs = $model->getProvList();

		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',		$pagination);
		$this->assignRef('pf',$pf);
		$this->assignRef('prov',$prov);
		$this->assignRef('cat',$cat);
		$this->assignRef('course',$course);
		$this->assignRef('courses',		$courses);
		$this->assignRef('provs',		$provs);
		$this->assignRef('cats',		$cats);
		parent::display($tpl);
	}
}
