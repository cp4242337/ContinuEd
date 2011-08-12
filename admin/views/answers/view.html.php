<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );


class ContinuEdViewAnswers extends JView
{
	function display($tpl = null)
	{
		$model = $this->getModel();
		$courseid = JRequest::getVar('course');
		$questionid = JRequest::getVar('question');
		JToolBarHelper::title(   JText::_( 'ContinuEd Answer Manager' ), 'continued' );
		JToolBarHelper::publishList('crctpublish','Correct');
		JToolBarHelper::unpublishList('crctunpublish','Wrong');
		JToolBarHelper::custom( 'copy', 'copy.png', 'copy.png', 'Copy', true, true );
		JToolBarHelper::deleteList();
		JToolBarHelper::editListX();
		JToolBarHelper::addNewX();
		JToolBarHelper::back('Questions','index.php?option=com_continued&view=questions&area='.JRequest::getVar('area').'&course='.$courseid);

		// Get data from the model
		$items		= & $this->get( 'Data');
		$pagination = & $this->get( 'Pagination' );

		$this->assignRef('items',		$items);
		$this->assignRef('questionid',$questionid);
		$this->assignRef('courseid',$courseid);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('cname',$model->getCourseName($courseid));
		$this->assignRef('ques',$model->getQuestion($questionid));

		parent::display($tpl);
	}
}
