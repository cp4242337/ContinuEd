<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewPreTest extends JView
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
		$should=ContinuEdHelper::checkViewed("fm",$courseid,$token);
		$already=ContinuEdHelper::checkViewed("pre",$courseid,$token);
		if ($username != 'Guest' && $token && $should && !$already) {
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
				$mtext=$model->getPreTest($courseid);
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
				$se=$model->savePreTest($courseid,$part,$token,$hasans);
				$app->redirect('index.php?option=com_continued&view=pretest&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$pn.'&token='.$token);

			}
			if ($courseid && $evaldone) {
				//save final part and take to material
				$se=$model->savePreTest($courseid,$part,$token,$hasans);
				$fmtext=ContinuEdHelper::trackViewed("pre",$courseid,$token);
				$app->redirect('index.php?option=com_continued&view=material&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
		} else if ($already && $should && $token) {
			//take user to material page id they have already completed pre test
			$app->redirect('index.php?option=com_continued&view=material&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
		} else {
			//take user to frontmatter if they have not viewed frontmatter, material, or aren't logged in
			$app->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
