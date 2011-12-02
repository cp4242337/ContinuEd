<?php


jimport( 'joomla.application.component.view');


class ContinuEdViewQandA extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model =& $this->getModel();
		$courseid = JRequest::getVar( 'course' );
		$cinfo = $model->getCourse($courseid);
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$qanda=ContinuedHelper::trackViewed("qaa",$courseid,"NoTokenNeeded");
		$redirurl = $cinfo->course_cataloglink;
		if (!$redirurl) $redirurl = 'index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ).'&cat='.$cinfo->course_cat;
		if ($username != 'Guest') {
			$qanda=$model->loadQuestions($courseid);
			$this->assignRef('qanda',$qanda);
			$this->assignRef('cinfo',$cinfo);
			$this->assignRef('redirurl',$redirurl);
			parent::display($tpl);
		} else  { 
			$app->redirect($redirurl); 
		}
	}
}
?>
