<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelEval extends JModel
{

	function getEval($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT course_id,course_name,course_postparts,course_haseval FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}
	
	function getPart($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_parts WHERE part_course = '.$courseid.' && part_area = "pre" && part_part = '.$part;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}
	
	function getQuestions($courseid,$part)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_course = '.$courseid.' && q_part = '.$part.' && q_area = "post" ORDER BY ordering ASC';
		$db->setQuery( $query );
		$qdata = $db->loadObjectList();
		foreach ($qdata as &$q) {
			if ($q->q_type == "mcbox" || $q->q_type == "dropdown" || $q->q_type == "multi") {
				$qo = 'SELECT * FROM #__ce_questions_opts WHERE published >= 1  && opt_question = '.$q->q_id.' ORDER BY ordering ASC';
				$db->setQuery( $qo );
				$q->options = $db->loadObjectList();
			}
		}
		return $qdata;
	}
	
	function getAnswered($courseid,$part,$token) {
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_evalans WHERE ans_area = "post" && part="'.$part.'" && userid="'.$userid.'" && sessionid="'.$sessionid.'" && course="'.$courseid.'"';
		$query .= ' && tokenid="'.$token.'"';
		$db->setQuery($query); 
		$data = $db->loadObjectList();
		if (!$data && $cecfg->EVAL_ANSRPT) {
			$query = 'SELECT * FROM #__ce_evalans WHERE ans_area = "post" && part="'.$part.'" && userid="'.$userid.'" && course="'.$courseid.'"';
			$query .= ' ORDER BY anstime DESC';
			$db->setQuery($query); 
			$data = $db->loadObjectList();
		}
		return $data;

	}
	
	function saveEval($courseid,$part,$tokenid,$hasans) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		
		$queryd = 'DELETE FROM #__ce_evalans WHERE ans_area = "post" && part="'.$part.'" && userid="'.$userid.'" && tokenid="'.$tokenid.'" && sessionid="'.$sessionid.'" && course="'.$courseid.'"';
		$db->setQuery($queryd);
		$db->query();
		
		$query = 'SELECT q_id,q_course,q_part,q_type FROM #__ce_questions WHERE q_type != "message" && q_course = '.$courseid.' && q_area = "post" && q_part = '.$part;
		$db->setQuery( $query );
		$qdata = $db->loadObjectList();
		
		foreach ($qdata as $ques) {
		 if ($ques->q_type == 'textar' || $ques->q_type == 'textbox') {
		 	$ans = $db->getEscaped(JRequest::getVar('p'.$part.'q'.$ques->q_id)); $ansarr = "mgn";
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques->q_id.'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","post")';
		 } else if ($ques->q_type != 'mcbox') {
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques->q_id.'","'.$part.'","'.JRequest::getVar('p'.$part.'q'.$ques->q_id).'","'.$sessionid.'","'.$tokenid.'","post")';
		 }else {
		 	$ansarr = JRequest::getVar('p'.$part.'q'.$ques->q_id);
		 	$ans = implode(' ',$ansarr);
		 	$q = 'INSERT INTO #__ce_evalans	(userid,course,question,part,answer,sessionid,tokenid,ans_area) VALUES ("'.$userid.'","'.$courseid.'","'.$ques->q_id.'","'.$part.'","'.$ans.'","'.$sessionid.'","'.$tokenid.'","post")';
		 }
		 $db->setQuery( $q );
		 $db->query();

		}
		return 0;
	}
}
