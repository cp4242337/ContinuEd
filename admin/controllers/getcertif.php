<?php
/**
 * Hello Controller for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdControllerGetCertif extends ContinuEdController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}

	function getCertif() {
		$courseid = JRequest::getVar( 'course' );
		$userid = JRequest::getVar( 'user' );
		$model =& $this->getModel('getcertif');
		$cinfo=$model->getCourseInfo($courseid);
		$uinfo=$model->getUserInfo($userid);
		$cert=$model->getCertif($uinfo['cb_degree'],$cinfo['provider'],$courseid,$cinfo['defaultcertif']);
		$cc=$model->checkPassed($courseid,$userid);
		if ($cc) {
			$certhtml = $cert['ctmpl_content'];
			$certhtml = str_replace('{Title}',$cinfo['certifname'],$certhtml);
			$certhtml = str_replace('{Faculty}',$cinfo['faculty'],$certhtml);
			$certhtml = str_replace('{Credits}',$cinfo['credits'],$certhtml);
			$certhtml = str_replace('{IDate}',date("F d, Y",strtotime($cc['ctime'])),$certhtml); //Time Course Taken
			$certhtml = str_replace('{ADate}',date("F d, Y", strtotime($cinfo['actdate'])),$certhtml); //Activity Date
			$certhtml = str_replace('{Name}',$uinfo['firstname'].' '.$uinfo['lastname'],$certhtml);
			$certhtml = str_replace('{LicNum}',$uinfo['cb_licnum'],$certhtml);
			$certhtml = str_replace('{PNNum}',$cinfo['cneprognum'],$certhtml);
			$certhtml = str_replace('{PPNum}',$cinfo['cpeprognum'],$certhtml);
			$certhtml = str_replace('{Address1}',$this->uinfo['cb_address'],$certhtml);
			$certhtml = str_replace('{City}',$this->uinfo['cb_city'],$certhtml);
			$certhtml = str_replace('{State}',$this->uinfo['cb_state'],$certhtml);
			$certhtml = str_replace('{Zip}',$this->uinfo['cb_zipcode'],$certhtml);
			echo '<html><head></head><body>';
			if (!$certhtml) echo $cecfg['NODEG_MSG'];
			echo $certhtml;
			echo '</body></html>';
		}
	}
}
?>
