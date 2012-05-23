<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	pretest
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd PreTest Page View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	pretest
 * @since		always
 */
class ContinuEdViewPreTest extends JView
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
		$should=ContinuEdHelper::checkViewed("fm",$courseid,$token);
		$already=ContinuEdHelper::checkViewed("pre",$courseid,$token);
		
		// Logged in, token present, has viewed fm, not completed
		if ($user->id && $token && $should && !$already) {
			$part = JRequest::getVar( 'part' );
			//$evalstep = JRequest::getVar( 'evalstep' );
			//if (!$evalstep) 
			$evalstep = JRequest::getVar( 'stepnext' );
			$addans = JRequest::getVar( 'addans' );
			$hasans = JRequest::getVar( 'hasans' );
			
			//determine where user will go
			if (!$part) $part = 1;
			if ($evalstep=='prev') $pn = $part-1;
			if ($evalstep=='next') $pn = $part+1;
			if ($evalstep=='eval') $evaldone=1;
			
			//valid course, not done, not saving answers
			if ($courseid && !$evaldone && !$addans) { 
				
				//Gather pretest data
				$mtext=$model->getPreTest($courseid);
				$parti=$model->getPart($courseid,$part);
				$qdata=$model->getQuestions($courseid,$part);
				$adata=$model->getAnswered($courseid,$part,$token);
				
				//assign vars for view
				$this->assignRef('mtext',$mtext);
				$this->assignRef('qdata',$qdata);
				$this->assignRef('adata',$adata);
				$this->assignRef('token',$token);
				$this->assignRef('part',$part);
				$this->assignRef('parti',$parti);
				$this->assignRef('courseid',$courseid);
				$this->assignRef('itemid',$itemid);

				//show pretest
				parent::display($tpl);
			}
			
			//valid course, save answers, not done
			if ($courseid && $addans && !$evaldone) {
				//save answers to current part and take user to next or prev part
				$se=$model->savePreTest($courseid,$part,$token,$hasans);
				$app->redirect('index.php?option=com_continued&view=pretest&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$pn.'&token='.$token);

			}
			
			//valid course, done
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
			//take user to beginning if not properly stepped
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
