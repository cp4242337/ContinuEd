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
class ContinuEdViewCourseReport extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$option;
		//$qid = JRequest::getVar('question');
		$cid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		JToolBarHelper::title(   JText::_( 'ContinuEd Course Report' ), 'continued' );
		$tbar =& JToolBar::getInstance('toolbar');
		$tbar->appendButton('Link','archive','Export CSV','index.php?option=com_continued&controller=coursereport&task=csvme&course='.$cid.'&area='.$area.'&format=raw');
		JToolBarHelper::back('Courses','index.php?option=com_continued&view=courses');
		// Get data from the model
		$model = $this->getModel('coursereport');
		$questions = $model->getQuestions($cid,$area);
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );
		$startdate = $model->getState('startdate');
		$enddate = $model->getState('enddate');
		$pf=$model->getState('pf');

		$options = $model->getOptions();
		$this->assignRef('startdate',$startdate);
		$this->assignRef('enddate',$enddate);
		$this->assignRef('area',$area);
		$this->assignRef('pf',$pf);
		$this->assignRef('opts',		$options);
		$this->assignRef('questions',		$questions);
		$this->assignRef('items',		$items);
		$this->assignRef('pagination',	$pagination);
		parent::display($tpl);
	}
}
