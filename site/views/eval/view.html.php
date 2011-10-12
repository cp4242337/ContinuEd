<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewEval extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar('token');
		$itemid = JRequest::getVar( 'Itemid' );
		//check if logged in, logging in is REQUIRED
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$should=ContinuedHelper::checkViewed("mt",$courseid,$token);
		$already=ContinuedHelper::checkViewed("chk",$courseid,$token);
		$mtext=$model->getEval($courseid);
		$haseval = $mtext->course_haseval;
		if ($username != 'Guest' && $token && $should && !$already && $haseval) {
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
			if ($courseid && !$evaldone && !$addans) {
				//show part
				$parti=$model->getPart($courseid,$part);
				$qdata=$model->getQuestions($courseid,$part);
				$adata=$model->getAnswered($courseid,$part,$token);
				$this->assignRef('mtext',$mtext);
				$this->assignRef('qdata',$qdata);
				$this->assignRef('adata',$adata);
				$this->assignRef('token',$token);
				$this->assignRef('part',$part);
				$this->assignRef('parti',$parti);
				$this->assignRef('courseid',$courseid);

				$this->assignRef('itemid',$itemid);
				parent::display($tpl);
			}
			if ($courseid && $addans && !$evaldone) {
				//save answers to current part and take user to next or prev part
				$se=$model->saveEval($courseid,$part,$token,$hasans);
				$app->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$pn.'&token='.$token);

			}
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
			//take user to frontmatter if they have not viewed frontmatter, material, or aren't logged in
			$app->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
