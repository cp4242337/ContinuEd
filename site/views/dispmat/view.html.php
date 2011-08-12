<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewDispMat extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$courseid = JRequest::getVar( 'course' );
		$model =& $this->getModel();
		$fmtext=$model->getMaterial($courseid);
		$expired = $model->checkExp($fmtext['enddate']);
		if ($courseid && $expired) {
			if ($fmtext['course_hasinter']) {
				$reqids=$model->getReQids($courseid);
				$this->assignRef('reqids',$reqids);
			}
			$dummy=$model->trackView($courseid,'ExpiredNoTokenNeeded');
			$this->assignRef('fmtext',$fmtext);
			$this->assignRef('expired',$expired);
			parent::display($tpl);
		} else {
			$mainframe->redirect($fmtext['cataloglink']);
		}
	}
}
?>
