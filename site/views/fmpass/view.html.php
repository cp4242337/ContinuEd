<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewFMPass extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$courseid = JRequest::getVar( 'course' );
		$fmagree = JRequest::getVar( 'fmagree' );
		$model =& $this->getModel();
		$passed = $model->checkPassed($courseid);
		$fmtext=$model->getFrontMatter($courseid);
		$hasfm = $fmtext['hasfm'];

		//Course Purchase Check
		if ($fmtext['course_purchase']) {
			if ($fmtext['course_purchasesku']) $paid = ContinuEdHelperCourse::SKUCheck($user->id,$fmtext['course_purchasesku']);
			else $paid = ContinuEdHelperCourse::PurchaseCheck($user->id,$fmtext['id']);
		}
		else $paid = true;
		if (!$paid) $mainframe->redirect($fmtext['course_purchaselink']);


		if ($courseid && !$fmagree && $passed && $hasfm) {
			$dummy=$model->trackView($courseid,'PassedNoTokenNeeded');
			$this->assignRef('fmtext',$fmtext);
			parent::display($tpl);
		} else if ($courseid && ($fmagree || !$hasfm) && $passed) {
			$mainframe->redirect('index.php?option=com_continued&view=matpass&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		} else if ($courseid) {
			$mainframe->redirect($fmtext['cataloglink']);
		} else echo "You Shouldn't be here";
	}
}
?>
