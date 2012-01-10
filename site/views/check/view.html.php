<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	check
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Check Answers Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	check
 * @since		always
 */
class ContinuEdViewCheck extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$compagree = JRequest::getVar( 'compagree' );
		$token = JRequest::getVar('token');
		$editpart = JRequest::getVar('editpart');
		$editarea = JRequest::getVar('editarea');
		$user =& JFactory::getUser();
		
		//Check proper access
		$should=ContinuedHelper::checkViewed("qz",$courseid,$token);
		$done=ContinuedHelper::checkViewed("asm",$courseid,$token);
		
		//get total number of required questions
		$numreq = $model->getNumReq($courseid);
		
		//Logged in, Eval completed, Do not change ans, not assessed
		if ($user->id && $should && !$editpart && !$done) {
			
			//Valid course, no final agreement
			if ($courseid && !$compagree) {
				
				//get course info
				$cinfo=$model->getCourse($courseid);
				$haspre = $cinfo->course_haspre;
				$hasinter = $cinfo->course_hasinter;
				$haseval = $cinfo->course_haseval;
				
				//Gather Q&A Data
				if ($haseval) $qanda=$model->loadAnswers($courseid,'post',$token);
				if ($haspre) $preqa=$model->loadAnswers($courseid,'pre',$token);
				if ($hasinter) $intera=$model->loadAnswers($courseid,'inter',$token);
				
				//Check required answered
				if ($numreq > (count($qanda)+count($preqa)+count($intera))) $hasallreq = false;
				else $hasallreq = true;
				
				//Assign Vars
				$this->assignRef('qanda',$qanda);
				$this->assignRef('preqa',$preqa);
				$this->assignRef('intera',$intera);
				$this->assignRef('cinfo',$cinfo);
				$this->assignRef('token',$token);
				$this->assignRef('hasallreq',$hasallreq);
				$this->assignRef('haspre',$haspre);
				$this->assignRef('haseval',$haseval);
				$this->assignRef('hasinter',$hasinter);
				
				//Display
				parent::display($tpl);
			}
			
			//Valid course, Final Agreement
			if ($courseid && $compagree) {
				$tracked=ContinuedHelper::trackViewed("chk",$courseid,$token);
				$app->redirect('index.php?option=com_continued&view=assess&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
		} else if ($editpart && $should && $user->id && !$done) {
		//Change Ans, eval complete, loggedin, not assessed
			$res=$model->editPart($courseid,$token,$editarea);
			if ($editarea == 'pre') $dest="pretest";
			else $dest = "eval";
			$app->redirect('index.php?option=com_continued&view='.$dest.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$editpart.'&token='.$token);
		} else if (!$editpart && $should && $user->id && $done) {
		//Do not change ans, eval complete, Already assessed
			$app->redirect('index.php?option=com_continued&view=assess&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$editpart.'&token='.$token);
		} else {
		//Not properly stepped go back to beginning
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
