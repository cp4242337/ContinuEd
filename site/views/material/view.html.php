<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewMaterial extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar( 'token' );
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$should=$model->checkSteped($courseid,$token);
		$mtext=$model->getMaterial($courseid);
		$hasfm = $mtext['hasfm'];
		$hasmat = $mtext['hasmat'];
		$haseval = $mtext['haseval'];
		$haspre = $mtext['course_haspre'];
		$numreq=0;
		$gte = JRequest::getVar( 'gte' );
		if ($username != 'Guest' && $token && $should) {
			if ($courseid && !$gte && $hasmat) {
				if ($mtext['course_hasinter']) {
					$reqids=$model->getReQids($courseid);
					$reqans=$model->getReqAns($courseid,$reqids);
					$numreq=count($reqids)-count($reqans);
				}
				$this->assignRef('mtext',$mtext);
				$this->assignRef('token',$token);
				$this->assignRef('numreq',$numreq);
				$this->assignRef('reqids',$reqids);
				$this->assignRef('reqans',$reqans);
				parent::display($tpl);
			}
			if ($courseid && ($gte || !$hasmat) && $haseval) {
				$fmtext=$model->GoneToEval($courseid,$token);
				$mainframe->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			}
			if ($courseid && ($gte || !$hasmat) && !$haseval) {
				$fmtext=$model->GoneToEval($courseid,$token);
				if ($haspre) $mainframe->redirect('index.php?option=com_continued&view=eval&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
				else $mainframe->redirect($mtext['cataloglink']);
			}

		} else if (!$hasfm && !$gte) { $this->assignRef('mtext',$mtext); parent::display($tpl);
		} else if (!$hasfm && $gte) { $mainframe->redirect($mtext['cataloglink']);
		} else { $mainframe->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid); }
	}
}
?>
