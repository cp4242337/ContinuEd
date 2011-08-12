<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelDispMat extends JModel
{

	function getMaterial($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,material,cname,cataloglink,enddate,course_hasinter FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		return $fmtext;
	}
	function checkExp($enddate) {
		if ($enddate == '0000-00-00') return false;
		if (strtotime($enddate) <= strtotime("now")) return true;
		else return false;
	}
	function trackView($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","vo","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}

	function getReQids($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT id FROM #__ce_questions as q WHERE q.q_area = "inter" && q.course = "'.$courseid.'" AND q.qreq = 1';
		$db->setQuery($q);
		$data = $db->loadResultArray();
		return $data;
	}

}
