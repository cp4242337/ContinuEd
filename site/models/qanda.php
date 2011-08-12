<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelQandA extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,cataloglink FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function loadQuestions($courseid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT q.*,u.username ';
		$query .= 'FROM #__ce_questions as q ';
		$query .= 'RIGHT JOIN #__users as u ON q.q_addedby = u.id ';
		$query .= 'WHERE q.course = '.$courseid.' && q.q_area="qanda" && q.published = 1 ';
		$query .= 'ORDER BY q.ordering ASC ';
		$db->setQuery( $query );
		$qdata = $db->loadObjectList();
		return $qdata;
	}
	function trackView($courseid) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","qaa","'.$sessionid.'","DoneNotTokenNeeded","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
}
