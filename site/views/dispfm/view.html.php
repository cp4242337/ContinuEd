<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewDispFM extends JView
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


		if ((strtotime($fmtext['enddate']."+ 1 day") <= strtotime("now")) && ($fmtext['enddate'] != '0000-00-00 00:00:00')) $expired=true; else $expired = false;
		if ($courseid && !$fmagree && $expired && $hasfm) {
			$dummy=$model->trackView($courseid,'ExpiredNoTokenNeeded');
			$this->assignRef('fmtext',$fmtext);
			$this->assignRef('passed',$passed);
			parent::display($tpl);
		} else if ($courseid && ($fmagree || !$hasfm) && $expired) {
			$mainframe->redirect('index.php?option=com_continued&view=dispmat&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		} else if ($courseid) {
			$mainframe->redirect($fmtext['cataloglink']);
		} else echo "You Shouldn't be here";
	}
}
?>
