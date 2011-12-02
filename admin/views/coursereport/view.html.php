<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCourseReport extends JView
{
	function display($tpl = null)
	{
		$cid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		JToolBarHelper::title(   JText::_( 'ContinuEd Course Report' ), 'generic.png' );
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&controller=coursereport&task=csvme&course='.$cid.'&area='.$area.'&format=raw');
		JToolBarHelper::back('Courses','index.php?option=com_continued&view=courses');
		// Get data from the model
		$model = $this->getModel('coursereport');
		//$questions = $model->getQuestions($cid,$area);
		$qpre = $model->getQuestions($cid,'pre');
		$qpost = $model->getQuestions($cid,'post');
		$qinter = $model->getQuestions($cid,'inter');
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$pf=$model->getState('pf');
		$type=$model->getState('type');

		$options = $model->getOptions();
		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('area',$area);
		$this->assignRef('pf',$pf);
		$this->assignRef('type',$type);
		$this->assignRef('opts',		$options);
		//$this->assignRef('questions',		$questions);
		$this->assignRef('qpre',$qpre);
		$this->assignRef('qinter',$qinter);
		$this->assignRef('qpost',$qpost);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		parent::display($tpl);
	}
}
