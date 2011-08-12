<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewAnswers extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$course = $model->getCourse($courseid);
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$completed=$model->checkDone($courseid);
		$model->trackView($courseid);
		if ($username != 'Guest' && $completed) {
			$qanda=$model->loadAnswers($courseid,$completed['fsessionid']);
			$this->assignRef('qanda',$qanda);
			$this->assignRef('cinfo',$course);
			parent::display($tpl);
		} else  { $mainframe->redirect($course['cataloglink'],'Course incomplete or does not exist'); }
	}
}
?>
