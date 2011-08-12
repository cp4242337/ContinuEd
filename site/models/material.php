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
class ContinuEdModelMaterial extends JModel
{

	function getMaterial($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,material,cname,hasfm,hasmat,haseval,cataloglink,course_haspre,course_hasinter,course_qanda FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function GoneToEval($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","mt","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	function checkSteped($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="fm" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function getReqAns($courseid,$reqids) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$q2='SELECT question FROM #__ce_evalans WHERE userid = "'.$userid.'" && ans_area = "inter" && course = "'.$courseid.'" && question IN('.implode(",",$reqids).')';
		$db->setQuery($q2);
		return $db->loadResultArray();
	}
	function getReQids($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT id FROM #__ce_questions as q WHERE q.q_area = "inter" && q.course = "'.$courseid.'" AND q.qreq = 1';
		$db->setQuery($q);
		$data = $db->loadResultArray();
		return $data;
	}
}
