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
			$cinfo=$model->getCourseInfo($courseid);
			$uinfo=$model->getUserInfo();
			$cert=$model->getCertif($uinfo['cb_degree'],$cinfo['provider'],$courseid,$cinfo['defaultcertif']);
			$user =& JFactory::getUser();
			$username = $user->name;
			$certhtml = $cert['ctmpl_content'];
			$certhtml = str_replace('{Title}',$cinfo['certifname'],$certhtml);
			$certhtml = str_replace('{Faculty}',$cinfo['faculty'],$certhtml);
			$certhtml = str_replace('{Credits}',$cinfo['credits'],$certhtml);
			$certhtml = str_replace('{IDate}',date("F d, Y",strtotime($pinfo['ctime'])),$certhtml);
			$certhtml = str_replace('{ADate}',date("F d, Y", strtotime($cinfo['actdate'])),$certhtml);
			$certhtml = str_replace('{Name}',$username,$certhtml);
			$certhtml = str_replace('{LicNum}',$uinfo['cb_licnum'],$certhtml);
			$certhtml = str_replace('{PNNum}',$cinfo['cneprognum'],$certhtml);
			$certhtml = str_replace('{PPNum}',$cinfo['cpeprognum'],$certhtml);

			$document = &JFactory::getDocument();
			$document->setTitle('');
			$document->setName('cert');
			$document->setDescription('');
			$document->setHeader('');

			echo $certhtml;
		} else {
			$mainframe->redirect('index.php?option=com_continued&view=continued&Itemid='.JRequest::getVar( 'Itemid' ));
		}
	}


}
?>
