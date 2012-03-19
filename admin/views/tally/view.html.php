<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewTally extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'ContinuED Report' ), 'continued' );
		JToolBarHelper::back('Coursess','index.php?option=com_continued&view=courses');
		$model = $this->getModel('tally');
		// Get data from the model
		$course = JRequest::getVar( 'course' );
		$year = JRequest::getVar( 'year' );
		$area = JRequest::getVar( 'area' );
		$postqdata=$model->getQuestions($course,'post');
		$preqdata=$model->getQuestions($course,'pre');
		$interqdata=$model->getQuestions($course,'inter');
		$cdata=$model->getCourse($course);
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('preqdata',$preqdata);
		$this->assignRef('postqdata',$postqdata);
		$this->assignRef('interqdata',$interqdata);
		$this->assignRef('cdata',$cdata);
		$this->assignRef('year',$year);
		$this->assignRef('area',$area);

		parent::display($tpl);
	}
}
