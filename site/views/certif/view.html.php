<?php
jimport( 'joomla.application.component.view');


class ContinuEdViewCertif extends JView
{
	function display($tpl = null)
	{
		global $mainframe;
		$courseid = JRequest::getVar( 'course' );
		$model =& $this->getModel('certif');
		$passinfo = $model->checkPassed($courseid);
		$passed = count($passinfo);
		if ($courseid && $passed) {
			$dummy=$model->trackView($courseid,'GetCertificate');
			$cinfo=$model->getCourseInfo($courseid);
			$uinfo=$model->getUserInfo();
			$cert=$model->getCertif($uinfo['cb_degree'],$cinfo['provider'],$courseid,$cinfo['defaultcertif']);
			$this->assignRef('cert',$cert);
			$this->assignRef('pinfo',$passinfo);
			$this->assignRef('cinfo',$cinfo);
			$this->assignRef('uinfo',$uinfo);
			parent::display($tpl);
		} else {
			$mainframe->redirect('index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ));
		}
	}
}
?>
