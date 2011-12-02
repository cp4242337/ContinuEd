<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewMaterial extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar( 'token' );
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$mtext=$model->getMaterial($courseid);
		
		//Check Validity
		if ((strtotime($mtext->course_enddate."+ 1 day") <= strtotime("now")) && ($mtext->course_enddate != '0000-00-00 00:00:00')) {
			$expired=true; 
		} else {
			$expired = false;
		}
		
		//Check Passed
		$passed = ContinuEdHelper::passedCourse($courseid);
		if ($passed) {
			$what = "fmp";
		} else if ($expired) {
			$what = "fme";
		} else {
			$what = "fm";
		}
		
		$should=ContinuedHelper::checkViewed($what,$courseid,$token);
		$hasfm = $mtext->course_hasfm;
		$hasmat = $mtext->course_hasmat;
		$haseval = $mtext->course_haseval;
		$haspre = $mtext->course_haspre;
		$numreq=0;
		$gte = JRequest::getVar( 'gte' );
		if ($username != 'Guest' && $token && $should) {
			if ($courseid && !$gte && $hasmat) {
				JPluginHelper::importPlugin('contined');
				$results = $dispatcher->trigger('onContentPrepare', array(&$mtext->course_material));
				if ($mtext->course_hasinter) {
					$reqids=$model->getReQids($courseid);
					$reqans=$model->getReqAns($courseid,$reqids);
					$numreq=count($reqids)-count($reqans);
				}
				$this->assignRef('mtext',$mtext);
				$this->assignRef('token',$token);
				$this->assignRef('passed',$passed);
				$this->assignRef('expired',$expired);
				$this->assignRef('numreq',$numreq);
				$this->assignRef('reqids',$reqids);
				$this->assignRef('reqans',$reqans);
				parent::display($tpl);
			}
			if ($courseid && ($gte=='eval' || !$hasmat) && $haseval) {
				$fmtext=ContinuedHelper::trackViewed('mt',$courseid,$token);
				$app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
			if ($courseid && ($gte=='eval' || !$hasmat) && !$haseval) {
				$fmtext=ContinuedHelper::trackViewed('mt',$courseid,$token);
				if ($haspre) $app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
				else $app->redirect($mtext->course_cataloglink);
			}
			if ($gte=="return") {
			
				if ($passed) {
					ContinuedHelper::trackViewed('mtp',$courseid,$token);
					$pass = 'complete';
				} else if ($expired) {
					ContinuedHelper::trackViewed('vo',$courseid,$token);
					$pass = 'complete';
				} else {
					$pass = 'complete';
				} 
				
				$res = ContinuEdHelper::endSession($courseid,$token,0,0,$pass);
				$redirurl = $mtext->course_cataloglink;
				if (!$redirurl) $redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$mtext->course_cat;
				$app->redirect($redirurl);
				
			}

		} else if (!$hasfm && !$gte) { $this->assignRef('mtext',$mtext); parent::display($tpl);
		} else if (!$hasfm && $gte) { $app->redirect($mtext->cataloglink);
		} else { $app->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid,"Error"); }
	}
}
?>
