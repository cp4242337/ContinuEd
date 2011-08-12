<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelAnswers extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,cataloglink FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function loadAnswers($courseid,$sessionid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT q.*,q.id as ques_id,a.*,o.opttxt,o.optexpl,o.correct ';
		$query .= 'FROM #__ce_questions as q ';
		$query .= 'LEFT JOIN #__ce_evalans as a ON q.id = a.question ';
		$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.id ';
		$query .= 'LEFT JOIN #__ce_parts AS p ON p.part_part = q.qsec AND p.part_course = '.$courseid.' ';
		$query .= 'WHERE q.course = '.$courseid.' && a.userid="'.$userid.'" && a.sessionid = "'.$sessionid.'" ';
		$query .= 'GROUP BY q.id ';
		$query .= 'ORDER BY q.qsec ASC , q.ordering ASC ';
		$db->setQuery( $query );
		$qdata = $db->loadAssocList();
		return $qdata;
	}
	function checkDone($courseid) {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_completed WHERE user="'.$userid.'" && crecent=1 && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return $data;
	}
	function trackView($courseid) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","ans","'.$sessionid.'","DoneNotTokenNeeded","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
}
