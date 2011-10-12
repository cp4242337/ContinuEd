<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewFrontMatter extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar( 'course' );
		$fmagree = JRequest::getVar( 'fmagree' );
		$token = JRequest::getVar('token');
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$aid = $user->getAuthorisedViewLevels();
		$model =& $this->getModel();
		$fmtext=$model->getFrontMatter($courseid);
		//is program/cat bought
		$catinfo=$model->getCatInfo($fmtext->course_cat);
		if ($catinfo->cat_free == 0) {
			$bought=ContinuEdHelper::SKUCheck($user->id,$catinfo->cat_sku);
		} else { $bought=true; }
		if (!$bought) { $app->redirect($fmtext->course_cataloglink); }

		//Course Purchase Check
		if ($fmtext->course_purchase) {
			if ($fmtext->course_purchasesku) $paid = ContinuEdHelper::SKUCheck($user->id,$fmtext->course_purchasesku);
			else $paid = ContinuEdHelper::PurchaseCheck($user->id,$fmtext->course_id);
		}
		else $paid = true;
		//if (!$paid) $app->redirect($fmtext->course_purchaselink);

		if ((strtotime($fmtext->course_enddate."+ 1 day") <= strtotime("now")) && ($fmtext->course_enddate != '0000-00-00 00:00:00')) $expired=true; else $expired = false;
		$hasfm = $fmtext->course_hasfm;
		$ctd = date(Ymdhis);
		if (empty($token)) $token=md5($ctd);
		ContinuedHelper::startSession($courseid,$token);
		if ($fmtext->course_prereq != 0) $cantake = $model->checkPreReq($fmtext->course_prereq);
		else $cantake = true;
		if (!in_array($fmtext->access,$aid) || $fmtext->published < 1) { $cantake=false; }
		if (!$cantake) $app->redirect($fmtext->course_cataloglink);
		$haspassed = $model->hasPassed($courseid);
		if ($haspassed) $app->redirect('index.php?option=com_continued&view=fmpass&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		if ($courseid && !$fmagree && $hasfm) {
			$this->assignRef('fmtext',$fmtext);
			$this->assignRef('token',$token);
			$this->assignRef('paid',$paid);
			parent::display($tpl);
		}
		if ($expired) {
			$app->redirect('index.php?option=com_continued&view=dispmat&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		} else if ($courseid && ($fmagree || !$hasfm)) {
			$proceed=ContinuEdHelper::trackViewed("fm",$courseid,$token);
			if ($fmtext->course_haspre) {
				$nextstep='pretest';
				if ($username == 'Guest') {
					$url = base64_encode('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
					$app =& JFactory::getApplication();
					$app->redirect('index.php?option=com_user&view=login&return='.$url,'You must login first');
					$proceed=false;
				}
			}
			else $nextstep = 'material';
			if ($proceed) $app->redirect('index.php?option=com_continued&view='.$nextstep.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			else echo 'A database error has occured and you cannot continue. Please contact a site admin.';
		}
	}
}
?>
