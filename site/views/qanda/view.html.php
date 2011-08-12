<?php


jimport( 'joomla.application.component.view');


class ContinuEdViewQandA extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$course = $model->getCourse($courseid);
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$model->trackView($courseid);
		if ($username != 'Guest') {
			$qanda=$model->loadQuestions($courseid);
			$this->assignRef('qanda',$qanda);
			$this->assignRef('cinfo',$course);
			parent::display($tpl);
		} else  { $mainframe->redirect($course['cataloglink']); }
	}
}
?>
