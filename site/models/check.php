<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCheck extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT course_id,course_name,course_haseval,course_haspre,course_changepre,course_hasinter FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}
	function loadAnswers($courseid,$area,$token)
	{
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT q.*,q.q_id as ques_id,a.*,o.opt_text,o.opt_expl,o.opt_correct ';
		$query .= 'FROM #__ce_questions as q ';
		$query .= 'LEFT JOIN #__ce_evalans as a ON q.q_id = a.question ';
		$query .= 'LEFT JOIN #__ce_questions_opts AS o ON a.answer = o.opt_id ';
		$query .= 'LEFT JOIN #__ce_parts AS p ON p.part_part = q.q_part AND p.part_course = '.$courseid.' ';
		$query .= 'WHERE q.q_type != "message" && q.q_area="'.$area.'" && q.q_course = '.$courseid.' && a.userid="'.$userid.'" && a.tokenid = "'.$token.'" ';
		$query .= 'GROUP BY q.q_id ';
		$query .= 'ORDER BY q.q_part ASC , q.ordering ASC ';
		$db->setQuery( $query ); 
		$qdata = $db->loadObjectList();
		return $qdata;
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
	function getNumReq($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT * FROM #__ce_questions WHERE published > 0 && q_type != "message" && q_course = "'.$courseid.'" && q_req = 1 && q_area != "qanda"';
		$db->setQuery($q);
		$data = $db->query();
		return $db->getNumRows();
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



}
