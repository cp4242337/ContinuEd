<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewMatPass extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$courseid = JRequest::getVar( 'course' );
		$model =& $this->getModel();
		$passed = $model->checkPassed($courseid);
		$fmtext=$model->getMaterial($courseid);
		if ($courseid && $passed) {
			if ($fmtext['course_hasinter']) {
				$reqids=$model->getReQids($courseid);
				$this->assignRef('reqids',$reqids);
			}
			$dummy=$model->trackView($courseid,'PassedNoTokenNeeded');
			$this->assignRef('fmtext',$fmtext);
			parent::display($tpl);
		} else {
			$mainframe->redirect($fmtext['cataloglink']);
		}
	}
}
?>
