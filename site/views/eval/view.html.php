<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	eval
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Eval Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	eval
 * @since		always
 */
class ContinuEdViewEval extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar('token');
		$itemid = JRequest::getVar( 'Itemid' );
		$user =& JFactory::getUser();
		
		//Check proper access
		$should=ContinuedHelper::checkViewed("mt",$courseid,$token);
		$already=ContinuedHelper::checkViewed("chk",$courseid,$token);
		
		//Get Course Information
		$mtext=$model->getEval($courseid);
		$haseval = $mtext->course_haseval;
		
		//Logged in, Has token, Material Complete, hasn't already done, eval exists
		if ($user->id && $token && $should && !$already && $haseval) {
			$part = JRequest::getVar( 'part' );
			$evalstep = JRequest::getVar( 'evalstep' );
			if (!$evalstep) $evalstep = JRequest::getVar( 'stepnext' );
			$addans = JRequest::getVar( 'addans' );
			$hasans = JRequest::getVar( 'hasans' );
			
			//determine where user will go
			if (!$part) $part = 1;
			if ($evalstep=='prev') $pn = $part-1;
			if ($evalstep=='next') $pn = $part+1;
			if ($evalstep=='eval') $evaldone=1;
			
			//Valid course, Eval not done, not saving answers
			if ($courseid && !$evaldone && !$addans) {
				//Get Q&A data
				$parti=$model->getPart($courseid,$part);
				$qdata=$model->getQuestions($courseid,$part);
				$adata=$model->getAnswered($courseid,$part,$token);
				
				//Assign vars
				$this->assignRef('mtext',$mtext);
				$this->assignRef('qdata',$qdata);
				$this->assignRef('adata',$adata);
				$this->assignRef('token',$token);
				$this->assignRef('part',$part);
				$this->assignRef('parti',$parti);
				$this->assignRef('courseid',$courseid);
				$this->assignRef('itemid',$itemid);
				
				//display
				parent::display($tpl);
			}
			
			//Valid Course, Save Answers, eval not done
			if ($courseid && $addans && !$evaldone) {
				//save answers to current part and take user to next or prev part
				$se=$model->saveEval($courseid,$part,$token,$hasans);
				$app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$pn.'&token='.$token);

			}
			
			//valid course, eval done
			if ($courseid && $evaldone) {
				//save final part and take to check step
				$se=$model->saveEval($courseid,$part,$token,$hasans);
				$fmtext=ContinuedHelper::trackViewed("qz",$courseid,$token);
				$app->redirect('index.php?option=com_continued&view=check&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
		} else if ($already && $should && $token && $haseval) {
			//take user to certif page id they have already completed and checked it
			$app->redirect('index.php?option=com_continued&view=assess&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
		} else if (!$haseval) {
			//take user to end when there is no eval but ther has been a pretest
			$fmtext=ContinuedHelper::trackViewed("qz",$courseid,$token);
			$app->redirect('index.php?option=com_continued&view=check&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
		} else {
			//take user to beginning if not properly stepped
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
