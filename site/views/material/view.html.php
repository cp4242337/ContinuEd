<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	material
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Material Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	material
 * @since		always
 */
class ContinuEdViewMaterial extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar( 'token' );
		$nocredit = JRequest::getVar('nocredit','0');
		$user =& JFactory::getUser();
		
		//Get material data
		$mtext=$model->getMaterial($courseid);
		$matpages = $model->getMatPages($courseid);
		
		//Get user material page data
		foreach ($matpages as $mp) {
			$mparr[]=$mp->mat_id;
		}
		$mpdata=ContinuedHelper::userMatData($mparr);
		
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
		if ($nocredit != 0) { $what = "fmn"; }
		
		//Check Viewed
		$viewed=ContinuedHelper::checkViewed($what,$courseid,$token);
		
		//Parse Material Info and set vars
		$hasfm = $mtext->course_hasfm;
		$hasmat = $mtext->course_hasmat;
		$haseval = $mtext->course_haseval;
		$haspre = $mtext->course_haspre;
		$numreq=0;
		$gte = JRequest::getVar( 'gte' );
		
		//check if you can proceed, when you try to
		$gotoeval=false;
		$jumpedover=false;
		if ($gte == 'eval') { 
			$gotoeval=true;
			foreach ($matpages as $mpd) {
				//If only 1 mat page, end view if its only text/html
				if (count($matpages) == 1) {
					if ($mpdata[$matpages[0]->mat_id] && $matpages[0]->mat_type == "text" && $mpdata[$matpages[0]->mat_id]->mu_status != "complete") { 
						ContinuEdHelper::endMat($matpages[0]->mat_id);
						$mpdata[$matpages[0]->mat_id]->mu_status="complete";
					}
				}
				//check for incomplete and existing views
				if ($mpdata[$mpd->mat_id]) {
					if ($mpdata[$mpd->mat_id]->mu_status != 'complete') $gotoeval=false;
				} else {
					$gotoeval=false;
				}
			}
			//let user know they if they try to jump ahead
			if (!$gotoeval) $jumpedover=true;
		}
		
		//Token exists, had done appropriate steps
		if ($token && $viewed) {
			//Valid Course, Not done with material, course has material, cannot go to eval
			if ($courseid && !$gotoeval  && $hasmat) {
				//Run plugin on text data
				JPluginHelper::importPlugin('contined');
				if (count($matpages) == 1) $results = $dispatcher->trigger('onContinuEdPrepare', array(&$matpages[0]->mat_content));
				if ($mtext->course_material) $results = $dispatcher->trigger('onContinuEdPrepare', array(&$mtext->course_material));				
				
				//Get intermediated question counts if needed
				if ($mtext->course_hasinter) {
					$reqids=$model->getReQids($courseid);
					$reqans=$model->getReqAns($courseid,$reqids);
					$numreq=count($reqids)-count($reqans);
				}
		
				//If only 1 material page, start mat view if not already started
				if (count($matpages) == 1) {
					if (!$mpdata[$matpages[0]->mat_id]) ContinuEdHelper::startMat($matpages[0]->mat_id);
				}
				
				//Assign Vars
				$this->assignRef('mtext',$mtext);
				$this->assignRef('matpages',$matpages);
				$this->assignRef('mpdata',$mpdata);
				$this->assignRef('token',$token);
				$this->assignRef('passed',$passed);
				$this->assignRef('expired',$expired);
				$this->assignRef('numreq',$numreq);
				$this->assignRef('reqids',$reqids);
				$this->assignRef('reqans',$reqans);
				$this->assignRef('jumpedover',$jumpedover);
				$this->assignRef('nocredit',$nocredit);
				parent::display($tpl);
			}
			//Valid course, can go to eval or course has no material, course has eval
			if ($courseid && ($gotoeval || !$hasmat) && $haseval) {
				$fmtext=ContinuedHelper::trackViewed('mt',$courseid,$token);
				$app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
			//valid course, can go to eval or course has no material, course has no eval
			if ($courseid && ($gotoeval  || !$hasmat) && !$haseval) {
				$fmtext=ContinuedHelper::trackViewed('mt',$courseid,$token);
				if ($haspre) $app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
				else $app->redirect($mtext->course_cataloglink);
			}
			//return to cat page or cat link, end session
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
				
				if ($nocredit != 0) { $pass= 'audit'; ContinuedHelper::trackViewed('mtn',$courseid,$token); }
				
				$res = ContinuEdHelper::endSession($courseid,$token,0,0,$pass);
				$redirurl = $mtext->course_cataloglink;
				if (!$redirurl) $redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$mtext->course_cat;
				$app->redirect($redirurl);
				
			}

		} else if (!$hasfm && !$gotoeval) {
		//No frontmatter, cannot move on -- SHOULD NOT FIRE
			$this->assignRef('mtext',$mtext); parent::display($tpl);
		} else if (!$hasfm && $gotoeval) {
		//No frontmatter, return to cat page or catlink -- SHOULD NOT FIRE
			$res = ContinuEdHelper::endSession($courseid,$token,0,0,$pass);
			$redirurl = $mtext->course_cataloglink;
			if (!$redirurl) $redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$mtext->course_cat;
			$app->redirect($redirurl);
		} else {
		//Go back to beginning, not stepped properly 
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid); 
		}
	}
}
?>
