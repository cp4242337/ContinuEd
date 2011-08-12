<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewFrontMatter extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$courseid = JRequest::getVar( 'course' );
		$fmagree = JRequest::getVar( 'fmagree' );
		$token = JRequest::getVar('token');
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$aid = $user->aid;
		$model =& $this->getModel();
		$fmtext=$model->getFrontMatter($courseid);
		//is program/cat bought
		$catinfo=$model->getCatInfo($fmtext['ccat']);
		if ($catinfo['catfree'] == 0) {
			$bought=ContinuEdHelperCourse::SKUCheck($user->id,$catinfo['catsku']);
		} else { $bought=true; }
		if (!$bought) { $mainframe->redirect($fmtext['cataloglink']); }

		//Course Purchase Check
		if ($fmtext['course_purchase']) {
			if ($fmtext['course_purchasesku']) $paid = ContinuEdHelperCourse::SKUCheck($user->id,$fmtext['course_purchasesku']);
			else $paid = ContinuEdHelperCourse::PurchaseCheck($user->id,$fmtext['id']);
		}
		else $paid = true;
		//if (!$paid) $mainframe->redirect($fmtext['course_purchaselink']);

		if ((strtotime($fmtext['enddate']."+ 1 day") <= strtotime("now")) && ($fmtext['enddate'] != '0000-00-00 00:00:00')) $expired=true; else $expired = false;
		$hasfm = $fmtext['hasfm'];
		$ctd = date(Ymdhis);
		if (empty($token)) $token=md5($ctd);
		if ($fmtext['prereq'] != 0) $cantake = $model->checkPreReq($fmtext['prereq']);
		else $cantake = true;
		if ($fmtext['access'] > $aid || $fmtext['published'] != 1) { $cantake=false; }
		if (!$cantake) $mainframe->redirect($fmtext['cataloglink']);
		$haspassed = $model->hasPassed($courseid);
		if ($haspassed) $mainframe->redirect('index.php?option=com_continued&view=fmpass&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		if ($courseid && !$fmagree && $hasfm) {
			$this->assignRef('fmtext',$fmtext);
			$this->assignRef('token',$token);
			$this->assignRef('paid',$paid);
			parent::display($tpl);
		}
		if ($expired) {
			$mainframe->redirect('index.php?option=com_continued&view=dispmat&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		} else if ($courseid && ($fmagree || !$hasfm)) {
			$proceed=$model->AgreedFM($courseid,$token);
			if ($fmtext['course_haspre']) {
				$nextstep='pretest';
				if ($username == 'Guest') {
					$url = base64_encode('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
					$app =& JFactory::getApplication();
					$app->redirect('index.php?option=com_user&view=login&return='.$url,'You must login first');
					$proceed=false;
				}
			}
			else $nextstep = 'material';
			if ($proceed) $mainframe->redirect('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			else echo 'A database error has occured and you cannot continue. Please contact a site admin.';
		}
	}
}
?>
