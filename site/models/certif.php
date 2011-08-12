<?php
/**
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelCertif extends JModel
{

	function getCourseInfo($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		return $fmtext;
	}
	function getCertif($degree,$provider,$courseid,$defcert)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT cert FROM #__ce_degreecert WHERE degree = "'.$degree.'"';
		$db->setQuery($q);
		$cn = $db->loadAssoc();
		if ($this->checkDegree($courseid,$cn['cert'])) $usecert = $cn['cert'];
		else $usecert = $defcert;
		//get that certificate
		$query = 'SELECT * FROM #__ce_certiftempl WHERE ctmpl_cert = '.$usecert.' && ctmpl_prov = '.$provider;
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		return $fmtext;
	}
	function getUserInfo() {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__comprofiler WHERE user_id="'.$userid.'"';
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		return $futext;
	}
	function checkDegree($courseid,$usercert) {
		$coursecerts=$this->getCourseDegrees($courseid);
		$cando = in_array($usercert,$coursecerts);
		return $cando;
	}
	function getCourseDegrees($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cert_id FROM #__ce_coursecerts WHERE course_id = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	function checkPassed($courseid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_completed WHERE user="'.$userid.'" && cpass="pass" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return $data;
	}
	function trackView($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","crt","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}

}
