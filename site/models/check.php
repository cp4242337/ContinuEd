<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCheck extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,course_compcourse,haseval,course_haspre,course_changepre,course_hasinter FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function loadAnswers($courseid,$area)
	{
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT q.*,q.id as ques_id,a.*,o.opttxt,o.optexpl,o.correct ';
		$query .= 'FROM #__ce_questions as q ';
		$query .= 'LEFT JOIN #__ce_evalans as a ON q.id = a.question ';
		$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.id ';
		$query .= 'LEFT JOIN #__ce_parts AS p ON p.part_part = q.qsec AND p.part_course = '.$courseid.' ';
		$query .= 'WHERE q.q_area="'.$area.'" && q.course = '.$courseid.' && a.userid="'.$userid.'" && a.sessionid = "'.$sessionid.'" ';
		$query .= 'GROUP BY q.id ';
		$query .= 'ORDER BY q.qsec ASC , q.ordering ASC ';
		$db->setQuery( $query );
		$qdata = $db->loadAssocList();
		return $qdata;
	}
	function checkSteped($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="qz" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function checkDone($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="asm" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function editPart($courseid,$token,$area) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		if ($area=="pre") {
			$query = 'DELETE FROM #__ce_track WHERE step="pre" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
			$db->setQuery($query);
			$data = $db->query();
		}
		if ($area=="post") {
			$query = 'DELETE FROM #__ce_track WHERE step="qz" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
			$db->setQuery($query);
			$data = $db->query();
		}
		return count($data);
	}
	function assessMe($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","chk","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	function getNumReq($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT * FROM #__ce_questions WHERE course = "'.$courseid.'" AND qreq = 1 AND q_area != "qanda"';
		$db->setQuery($q);
		$data = $db->query();
		return $db->getNumRows();
	}


}
