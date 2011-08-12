<?php
/**
 *
 */

jimport( 'joomla.application.component.view');


class ContinuEdViewAssess extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar('token');
		$rating = JRequest::getVar('addrating');
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$should=$model->checkSteped($courseid,$token);
		if ($username != 'Guest' && $should) {
			if ($courseid) {
				$cinfo=$model->getCourse($courseid);
				$qanda=$model->loadAnswers($courseid,$token,$cinfo['course_evaltype'],$cinfo['course_haspre'],$cinfo['haseval']);
				$this->assignRef('qanda',$qanda);
				$this->assignRef('cinfo',$cinfo);
				$this->assignRef('courseid',$courseid);
				$this->assignRef('token',$token);
				$cando = $model->checkDegree($courseid);
				$this->assignRef('cando',$cando);
				$usercertif=$model->getUserCertif($cinfo['defaultcertif']);
				$this->assignRef('usercertif',$usercertif);
				$defaultcertif=$model->getCourseCertif($cinfo['defaultcertif']);
				$this->assignRef('defaultcertif',$defaultcertif);
				if ($cinfo['course_allowrate']) {
					if ($model->checkRated($courseid)) { $carate=false; }
					else {
						$canrate=true;
						if ($rating) {
							$model->addRating($courseid,$rating);
							$canrate=false;
						}

					}
				} else $canrate=false;
				$this->assignRef('canrate',$canrate);
				parent::display($tpl);
			}
		} else { $mainframe->redirect('index.php?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid); }
	}
}
?>
