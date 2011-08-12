<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewCheck extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$compagree = JRequest::getVar( 'compagree' );
		$token = JRequest::getVar('token');
		$editpart = JRequest::getVar('editpart');
		$editarea = JRequest::getVar('editarea');
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$should=$model->checkSteped($courseid,$token);
		$done=$model->checkDone($courseid,$token);
		$numreq = $model->getNumReq($courseid);
		if ($username != 'Guest' && $should && !$editpart && !$done) {
			if ($courseid && !$compagree) {
				$cinfo=$model->getCourse($courseid);
				$haspre = $cinfo['course_haspre'];
				$hasinter = $cinfo['course_hasinter'];
				$haseval = $cinfo['haseval'];
				if ($haseval) $qanda=$model->loadAnswers($courseid,'post');
				if ($haspre) $preqa=$model->loadAnswers($courseid,'pre');
				if ($hasinter) $intera=$model->loadAnswers($courseid,'inter');
				if ($numreq > (count($qanda)+count($preqa)+count($intera))) $hasallreq = false;
				else $hasallreq = true;
				$this->assignRef('qanda',$qanda);
				$this->assignRef('preqa',$preqa);
				$this->assignRef('intera',$intera);
				$this->assignRef('cinfo',$cinfo);
				$this->assignRef('token',$token);
				$this->assignRef('hasallreq',$hasallreq);
				$this->assignRef('haspre',$haspre);
				$this->assignRef('haseval',$haseval);
				$this->assignRef('hasinter',$hasinter);
				parent::display($tpl);
			}
			if ($courseid && $compagree) {
				$qanda=$model->assessMe($courseid,$token);
				$mainframe->redirect('index.php?option=com_continued&view=assess&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
		} else if ($editpart && $should && $username != 'Guest' && !$done) {
			$res=$model->editPart($courseid,$token,$editarea);
			if ($editarea == 'pre') $dest="pretest";
			else $dest = "eval";
			$mainframe->redirect('index.php?option=com_continued&view='.$dest.'&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$editpart.'&token='.$token);
		} else if (!$editpart && $should && $username != 'Guest' && $done) {
			$mainframe->redirect('index.php?option=com_continued&view=assess&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&part='.$editpart.'&token='.$token);
		} else {
			$mainframe->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid);
		}
	}
}
?>
