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
class ContinuEdModelPreTest extends JModel
{

	function getPreTest($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,cname,course_preparts FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function getPart($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_parts WHERE part_course = '.$courseid.' && part_area = "pre" && part_part = '.$part;
		$db->setQuery( $query );
		$mtext = $db->loadAssoc();
		return $mtext;
	}
	function getQuestions($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE course = '.$courseid.' && qsec = '.$part.' && q_area = "pre"ORDER BY ordering ASC';
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
		$query = 'SELECT * FROM #__ce_evalans WHERE ans_area = "pre" && part="'.$part.'" && userid="'.$userid.'" && sessionid="'.$sessionid.'" && course="'.$courseid.'"';
		if (!$cecfg['EVAL_ANSRPT']) $query .= ' && tokenid="'.$token.'"';
		$db->setQuery($query);
		$data = $db->loadAssocList();
		return $data;

	}
	function savePreTest($courseid,$part,$tokenid,$hasans) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		if ($hasans) $queryd = 'DELETE FROM #__ce_evalans WHERE ans_area = "pre" && part="'.$part.'" && userid="'.$userid.'" && sessionid="'.$sessionid.'" && course="'.$courseid.'"';
		$db->setQuery($queryd);
		$db->query();
		$query = 'SELECT id,course,qsec,qtype FROM #__ce_questions WHERE course = '.$courseid.' && q_area = "pre" && qsec = '.$part;
		$db->setQuery( $query );
		$qdata = $db->loadAssocList();
		foreach ($qdata as $ques) {
		 if ($ques['qtype'] == 'textar' || $ques['qtype'] == 'textbox') {
		 	$ans = $db->getEscaped(JRequest::getVar('p'.$part.'q'.$ques['id'])); $ansarr = "megan";
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","pre")';
		 } else if ($ques['qtype'] != 'mcbox') {
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.JRequest::getVar('p'.$part.'q'.$ques['id']).'","'.$sessionid.'","'.$tokenid.'","pre")';
		 }else {
		 	$ansarr = JRequest::getVar('p'.$part.'q'.$ques['id']);
		 	$ans = implode(' ',$ansarr);
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques['id'].'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","pre")';
		 }
		 $db->setQuery( $q );
		 $db->query();

		}
		return 0;
	}
	function PreTestDone($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track (user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","pre","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
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
	function checkStepedN($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_track WHERE step="pre" && user="'.$userid.'" && sessionid="'.$sessionid.'" && token="'.$token.'" && course="'.$courseid.'"';
		$db->setQuery($query);
		$data = $db->loadAssoc();
		return count($data);
	}

}
