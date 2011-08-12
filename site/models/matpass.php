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
class ContinuEdModelMatPass extends JModel
{

	function getMaterial($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,material,cname,cataloglink,hascertif,course_hasinter FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		return $fmtext;
	}
	function checkPassed($courseid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_completed WHERE user="'.$userid.'" && cpass="pass" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function trackView($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","mtp","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
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
