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
class ContinuEdModelEval extends JModel
{

	function getEval($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,evalparts,haseval FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function getPart($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_parts WHERE part_course = '.$courseid.' && part_part = '.$part;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function getQuestions($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_area = "post" && course = '.$courseid.' && qsec = '.$part.' ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qdata = $db->loadAssocList();
		return $qdata;
	}
	function getAnswered($courseid,$part,$token) {
		global $cecfg;
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_evalans WHERE ans_area = "post" && part="'.$part.'" && userid="'.$userid.'" && sessionid="'.$sessionid.'" && course="'.$courseid.'"';
		if (!$cecfg['EVAL_ANSRPT']) $query .= ' && tokenid="'.$token.'"';
		$db->setQuery($query);
		$data = $db->loadAssocList();
		return $data;

	}
	function saveEval($courseid,$part,$tokenid,$hasans) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		
		//remove previous answers
		$queryd = 'DELETE FROM #__ce_evalans WHERE ans_area = "post" && part="'.$part.'" && userid="'.$userid.'" && course="'.$courseid.'"';
		$db->setQuery($queryd);
		$db->query();
		
		//get questions
		$query = 'SELECT id,course,qsec,qtype FROM #__ce_questions WHERE q_area = "post" && course = '.$courseid.' && qsec = '.$part;
		$db->setQuery( $query );
		$qdata = $db->loadAssocList();
		
		//submit answer
		foreach ($qdata as $ques) {
		 if ($ques['qtype'] == 'textar' || $ques['qtype'] == 'textbox') {
		 	$ans = $db->getEscaped(JRequest::getVar('p'.$part.'q'.$ques['id']));
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","post")';
		 } else if ($ques['qtype'] != 'mcbox') {
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.JRequest::getVar('p'.$part.'q'.$ques['id']).'","'.$sessionid.'","'.$tokenid.'","post")';
		 }else {
		 	$ansarr = JRequest::getVar('p'.$part.'q'.$ques['id']);
		 	$ans = implode(' ',$ansarr);
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","post")';
		 }
		 $db->setQuery( $q );
		 $db->query();

		}
		return 0;
	}
	function EvalDone($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","qz","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
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
		$query = 'SELECT * FROM #__ce_track WHERE step="mt" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}
	function checkStepedN($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="chk" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}

}
